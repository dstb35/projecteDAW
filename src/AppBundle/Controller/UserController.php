<?php

namespace AppBundle\Controller;

use AppBundle\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Restaurant;
use AppBundle\Form\RestaurantType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction()
    {
        $title = 'Restaurantes disponibles';
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('r')
            ->from('AppBundle:Restaurant', 'r')
            ->where('r.paid = 1 AND r.roles = \'ROLE_USER\'')
            ->orderBy('r.name', 'ASC');
        $query = $qb->getQuery();
        $restaurants = $query->getResult();
        return $this->render("restaurants.html.twig", array(
            "restaurants" => $restaurants,
            'title' => $title,
        ));
    }

    public function loginAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $restaurant = new Restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $restaurant = $form->getData();
            if ($form->isValid()) {
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    /*$originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $originalFilename = substr($originalFilename, 0, 6);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();*/
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('restaurants_images'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $status = 'No se ha podido guardar la imagen' . $e->getMessage();
                        $this->session->getFlashBag()->add('danger', $status);
                    }
                    $restaurant->setImage($newFilename);
                }
                $restaurant->setCreated(new \DateTime());
                $password = $passwordEncoder->encodePassword($restaurant, $restaurant->getPassword());
                $restaurant->setPassword($password);
                $restaurant->setRoles('ROLE_USER');
                $em = $this->getDoctrine()->getManager();
                $em->persist($restaurant);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = "El usuario se ha creado correctamente";
                    $this->session->getFlashBag()->add("success", $status);
                    return $this->redirectToRoute('product_index', array('id' => $restaurant->getRestaurantid()));
                } else {
                    $status = "El usuario NO se ha creado correctamente";
                    $this->session->getFlashBag()->add("danger", $status);
                }
            } else {
                $status = "Registro no válido.";
                $this->session->getFlashBag()->add("danger", $status);
                $validator = $this->get('validator');
                $errors = $validator->validate($restaurant);
                if (count($errors) > 0) {
                    foreach ($errors as $violation) {
                        $error .= $violation->getMessage();
                        $this->session->getFlashBag()->add("warning", $error);
                    }
                }
            }
        }

        return $this->render('login.html.twig', array(
            'error' => $error,
            'last_username' => $lastUsername,
            'form' => $form->createView()
        ));
    }

    public function editAction(Request $request, UserInterface $user = null, $id, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para editar restaurantes');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes editar este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('restaurant_index');
        }
        $title = 'Editar Restaurante: ';
        $em = $this->getDoctrine()->getManager();
        $restaurant = $em->getRepository('AppBundle:Restaurant')->find($id);
        $form = $this->createForm(RestaurantType::class, $restaurant)
                ->remove('password');

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($user) {
                if ($form->isValid()) {
                    $imageFile = $form->get('image')->getData();
                    if ($imageFile) {
                        /*$originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $originalFilename = substr($originalFilename, 0, 6);*/
                        // this is needed to safely include the file name as part of the URL
                        //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                        //$newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                        $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                        try {
                            $fs = new Filesystem();
                            if ($restaurant->getImage() != NULL ){
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
                        $status = 'El restaurante se ha modificado correctamente';
                        $this->session->getFlashBag()->add('success', $status);
                        return $this->redirectToRoute('product_index', array('id' => $id));
                    } else {
                        $status = 'El restaurante NO se ha modificado correctamente';
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
