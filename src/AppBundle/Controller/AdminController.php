<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Restaurant;
use AppBundle\Form\RestaurantType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexClientsAction()
    {
        $title = 'Clientes disponibles';
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('r')
            ->from('AppBundle:Restaurant', 'r')
            ->where('r.roles = \'ROLE_USER\'')
            ->orderBy('r.created', 'ASC');
        $query = $qb->getQuery();
        $restaurants = $query->getResult();
        return $this->render("clients.html.twig", array(
            "restaurants" => $restaurants,
            'title' => $title,
        ));
    }

    public function editClientsAction(Request $request, UserInterface $user = null, $id, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para editar restaurantes');
        $title = 'Editar Cliente: ';
        $em = $this->getDoctrine()->getManager();
        $restaurant = $em->getRepository('AppBundle:Restaurant')->find($id);
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->add('paid', CheckboxType::class,
            array(
                "required" => "required",
                "attr" => array(
                    "class" => "form-check-input",
                )));
        $form->remove('password');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($user) {
                if ($form->isValid()) {
                    $imageFile = $form->get('image')->getData();
                    if ($imageFile) {
                        $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                        try {
                            $fs = new Filesystem();
                            if ($restaurant->getImage() != NULL) {
                                $fs->remove($this->getParameter('restaurants_images') . '/' . $restaurant->getImage());
                            }
                            $imageFile->move(
                                $this->getParameter('restaurants_images'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            $status = 'No se ha podido renombrar la imagen' . $e->getMessage();
                            $this->session->getFlashBag()->add('danger', $status);
                        }
                        $restaurant->setImage($newFilename);
                    }
                    $password = $passwordEncoder->encodePassword($restaurant, $restaurant->getPassword());
                    $restaurant->setPassword($password);
                    $em->persist($restaurant);
                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = 'El cliente se ha modificado correctamente';
                        $this->session->getFlashBag()->add('success', $status);
                        return $this->redirectToRoute('restaurant_index');
                    } else {
                        $status = 'El cliente NO se ha modificado correctamente';
                        $this->session->getFlashBag()->add('danger', $status);
                    }
                } else {
                    $this->session->getFlashBag()->add('danger', 'Form no válido');
                    unset($restaurant);
                }
            } else {
                $status = 'El restaurante no es válido o el usuario no está identificado';
                $this->session->getFlashBag()->add('danger', $status);
            }
        }

        return $this->render('restaurantEdit.html.twig', array(
            'form' => $form->createView(),
            'title' => $title,
            'restaurant' => $restaurant,
            'id' => $id
        ));
    }
}
