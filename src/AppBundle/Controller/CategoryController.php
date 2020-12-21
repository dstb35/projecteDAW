<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\CategoryType;
use Symfony\Component\HttpFoundation\Session\Session;

class CategoryController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function adminAction()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para editar categorías');
        $title = 'Listado de categorías';
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('categoriesEdit.html.twig', array(
            'title' => $title,
            'categories' => $categories,
        ));
    }

    public function addAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para crear categorías');
        $title = 'Añadir categoría';
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $category = $form->getData();
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "La categoría se ha creado correctamente";
                    $this->session->getFlashBag()->add("success", $status);
                    return $this->redirectToRoute("category_admin");
                } else {
                    $status = "La categoría NO se ha creado correctamente";
                    $this->session->getFlashBag()->add("danger", $status);
                }
            } else {
                $this->session->getFlashBag()->add("danger", "Form no válido");
                unset($category);
            }
        }

        return $this->render("add.html.twig", array(
            "form" => $form->createView(),
            "categoryAdd" => true,
            "title" => $title
        ));
    }

    public function removeAction($categoryid)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para borrar categorías');
        if (is_numeric($categoryid) && $categoryid > 0) {
            $em = $this->getDoctrine()->getManager();
            $category = $em->getRepository('AppBundle:Category')->find($categoryid);

            if ($category) {
                $em->remove($category);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = 'La categoría se ha borrado correctamente';
                    $this->session->getFlashBag()->add('success', $status);
                } else {
                    $status = 'La categoría NO se ha borrado correctamente';
                    $this->session->getFlashBag()->add('danger', $status);
                }
            } else {
                $status = 'No se ha encontrado la categoría con id: ' . $categoryid;
                $this->session->getFlashBag()->add('danger', $status);
            }
            return $this->adminAction();
        }
    }

    public function editAction(Request $request, $categoryid)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para editar categorías');
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($categoryid);
        $title = 'Editar categoría';
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $category = $form->getData();
            if ($form->isValid()) {
                $em->persist($category);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = 'La categoría se ha modificado correctamente';
                    $this->session->getFlashBag()->add('success', $status);
                    return $this->redirectToRoute('category_admin');
                } else {
                    $status = 'La categoría NO se ha modificado correctamente';
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
            "categoryAdd" => true,
            'title' => $title
        ));
    }
}
