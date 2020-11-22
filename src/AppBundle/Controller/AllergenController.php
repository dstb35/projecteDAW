<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Allergen;
use AppBundle\Form\AllergenType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\User\UserInterface;

class AllergenController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction($id)
    {
        $title = 'Listado de alérgenos';
        $em = $this->getDoctrine()->getManager();
        $allergen_repo = $em->getRepository('AppBundle:Allergen');
        $allergens = $allergen_repo->findAll();

        return $this->render('allergen.html.twig', array(
            'title' => $title,
            'allergens' => $allergens,
            'id' => $id
        ));
    }

    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para editar alérgenos');
        $title = 'Listado de alérgenos';
        $em = $this->getDoctrine()->getManager();
        $allergen_repo = $em->getRepository('AppBundle:Allergen');
        $allergens = $allergen_repo->findAll();

        return $this->render('allergensEdit.html.twig', array(
            'title' => $title,
            'allergens' => $allergens,
        ));
    }

    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para crear alérgenos');
        $title = 'Añadir alérgeno';
        $allergen = new Allergen();
        $form = $this->createForm(AllergenType::class, $allergen);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $allergen = $form->getData();
            if ($form->isValid()) {
                $imageFile = $form->get('image')->getData();
                if ($imageFile) {
                    $filename = uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('allergens_images'),
                            $filename
                        );
                    } catch (FileException $e) {
                        $status = 'No se ha podido guardar la imagen' . $e->getMessage();
                        $this->session->getFlashBag()->add('danger', $status);
                    }
                    $allergen->setImage($filename);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($allergen);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = 'El alérgeno se ha creado correctamente';
                    $this->session->getFlashBag()->add('success', $status);
                    return $this->redirectToRoute('allergen_admin');
                } else {
                    $status = 'El alérgeno NO se ha creado correctamente';
                    $this->session->getFlashBag()->add('danger', $status);
                }
            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->session->getFlashBag()->add('danger', $error->getMessage());
                }
            }
        }

        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
            'allergenAdd' => true,
            'title' => $title
        ));
    }

    public function removeAction($allergenid)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para borrar alérgenos');
        if (is_numeric($allergenid) && $allergenid > 0) {
            $em = $this->getDoctrine()->getManager();
            $allergen_repo = $em->getRepository('AppBundle:Allergen');
            $allergen = $allergen_repo->find($allergenid);

            if ($allergen) {
                try {
                    $fs = new Filesystem();
                    $fs->remove($this->getParameter('allergens_images') . '/' . $allergen->getImage());
                } catch (IOException $e) {
                    $status = 'No se ha podido borrar el archivo de imagen' . $e->getMessage();
                    $this->session->getFlashBag()->add('danger', $status);
                }
                $em->remove($allergen);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = 'El alérgeno se ha borrado correctamente';
                    $this->session->getFlashBag()->add('success', $status);
                } else {
                    $status = 'El alérgeno NO se ha borrado correctamente';
                    $this->session->getFlashBag()->add('danger', $status);
                }
            } else {
                $status = 'No se ha encontrado el alérgeno con id: ' . $allergenid;
                $this->session->getFlashBag()->add('danger', $status);
            }
            return $this->redirectToRoute('allergen_admin');
        }
    }

    public function productsByAllergenAction(UserInterface $user = null, $id, $allergenid)
    {
        $em = $this->getDoctrine()->getManager();
        $allergen = $em->getRepository('AppBundle:Allergen')->find(($allergenid));
        $restaurant = $em->getRepository('AppBundle:Restaurant')->find($id);
        $title = 'Productos con ' . $allergen->getname() . ' para restaurante ' . $restaurant->getName();

        $product_repo = $em->getRepository('AppBundle:Product');
        if ($user != null && $user->getRestaurantid() == $id) {
            $products = $product_repo->findBy(array('restaurantid' => $id));
        }else{
            $products = $product_repo->findBy(array('restaurantid' => $id, 'published' => 1));
        }
        $productsbyallergen = array();
        foreach ($products as $product){
            if ($product->getAllergens()->contains($allergen)){
                $productsbyallergen[] = $product;
            }
        }

        $categoriesSet = $em->getRepository('AppBundle:Category')->findAll();

        $categories = array();
        foreach ($productsbyallergen as $product){
            foreach ($categoriesSet as $category){
                if ($category == $product->getCategory()){
                    $categories[$category->getName()][] = $product;
                    unset($product);
                    break;
                }
            }
            if (isset($product)){
                $uncategorized[] = $product;
            }
        }

        if (isset($uncategorized)){
            $categories ['Sin categoría'] = $uncategorized;
        }

        return $this->render('products.html.twig', array(
            'title' => $title,
            'categories' => $categories,
            'id' => $id,
            'restaurant' => $restaurant
        ));
    }



    public function editAction(Request $request, $allergenid){
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para editar alérgenos');
        $em = $this->getDoctrine()->getManager();
        $allergen = $em->getRepository('AppBundle:Allergen')->find($allergenid);
        $title = 'Editar alérgeno';
        $form = $this->createForm(AllergenType::class, $allergen);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $allergen = $form->getData();
            if ($form->isValid()) {
                $imageFile = $form->get('image')->getData();
                if ($imageFile){
                    $filename = uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $fs = new Filesystem();
                    $fs->remove($this->getParameter('allergens_images') . '/' . $allergen->getImage());
                    $imageFile->move(
                        $this->getParameter('allergen_images'),
                        $filename
                    );
                } catch (FileException $e) {
                    $status = 'No se ha podido renombrar la imagen' . $e->getMessage();
                    $this->session->getFlashBag()->add('danger', $status);
                }
                    $allergen->setImage($filename);
                }
                $em = $this->getDoctrine()->getManager();
                $em->persist($allergen);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = 'El alérgeno se ha creado correctamente';
                    $this->session->getFlashBag()->add('success', $status);
                    return $this->redirectToRoute('allergen_admin');
                } else {
                    $status = 'El alérgeno NO se ha creado correctamente';
                    $this->session->getFlashBag()->add('danger', $status);
                }
            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->session->getFlashBag()->add('danger', $error->getMessage());
                }
            }
        }

        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
            'allergenAdd' => true,
            'title' => $title
        ));
    }
}
