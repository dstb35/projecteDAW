<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Table;
use AppBundle\Form\TableType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Factory\QrCodeFactory;


class TableController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(UserInterface $user = null, $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $table_repo = $em->getRepository("AppBundle:Table");
        $tables = array();
        if ($request->isXMLHttpRequest()) {
            $restaurantid = $request->query->get('restaurantid');
            $tablesset = $table_repo->findBy(array('restaurantid' => $restaurantid));
            foreach ($tablesset as $tableset){
                $table['name'] = $tableset->getName();
                $table['tableid'] = $tableset->getTableid();
                $tables[] = $table;
            }
            return new JsonResponse(array('tables' => $tables));
        }else{
            $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para ver mesas');
            if ($user->getRestaurantid() != $id) {
                $status = 'No puedes ver mesas de este restaurante';
                $this->session->getFlashBag()->add('danger', $status);
                return $this->redirectToRoute('homepage');
            }
            $tables = $table_repo->findAll();
            $restaurant = $em->getRepository('AppBundle:Restaurant')->find($id);
            if (isset($restaurant)) {
                $title = 'Mesas para restaurante ' . $restaurant->getName();
            } else {
                $title = 'Restaurante no encontrado para id: ' . $id;
            }

            return $this->render("table.html.twig", array(
                "tables" => $tables,
                'title' => $title,
                'id' => $id
            ));
        }
    }

    public function addAction(Request $request, UserInterface $user = null, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para crear mesas');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes añadir mesas de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        $title = "Añadir mesa";
        $em = $this->getDoctrine()->getManager();
        $table = new Table();
        $form = $this->createForm(TableType::class, $table);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $table = $form->getData();
            if ($user) {
                $table->setRestaurantid($user);
                if ($form->isValid()) {
                    $em->persist($table);
                    $flush = $em->flush();

                    if ($flush == null) {
                        $status = "La mesa se ha creado correctamente";
                        $this->session->getFlashBag()->add("success", $status);
                        return $this->redirectToRoute("table_index", array('id' => $id));
                    } else {
                        $status = "La mesa NO se ha creado correctamente";
                        $this->session->getFlashBag()->add("danger", $status);
                    }
                } else {
                    $this->session->getFlashBag()->add("danger", "Form no válido");
                    unset($table);
                }
            } else {
                $status = "El restaurante no es válido o el usuario no está identificado";
                $this->session->getFlashBag()->add("danger", $status);
            }
        }

        return $this->render("add.html.twig", array(
            "form" => $form->createView(),
            'tableAdd' => true,
            "title" => $title
        ));
    }

    public function editAction(Request $request, UserInterface $user = null, $tableid, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para editar mesas');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes editar mesas de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        $title = 'Editar mesa: ';
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('AppBundle:Table')->find($tableid);
        $form = $this->createForm(TableType::class, $table);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($user) {
                if ($form->isValid()) {
                    $em->persist($table);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $status = 'La mesa se ha editado correctamente';
                        $this->session->getFlashBag()->add('success', $status);
                        return $this->redirectToRoute('table_index', array('id' => $id));
                    } else {
                        $status = 'La mesa NO se ha editado correctamente';
                        $this->session->getFlashBag()->add('danger', $status);
                    }
                } else {
                    $this->session->getFlashBag()->add('danger', 'Form no válido');
                    unset($table);
                }
            } else {
                $status = 'El restaurante no es válido o el usuario no está identificado';
                $this->session->getFlashBag()->add('danger', $status);
            }
        }

        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
            'title' => $title,
            'tableAdd' => true,
            'id' => $id
        ));
    }

    public function removeAction(UserInterface $user = null, $id, $tableid){
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para borrar mesas');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes borrar mesas de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        if (is_numeric($tableid) && $tableid > 0) {
            $em = $this->getDoctrine()->getManager();
            $table_repo = $em->getRepository("AppBundle:Table");
            $table = $table_repo->find($tableid);

            if ($table) {
                $em->remove($table);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "La mesa se ha borrado correctamente";
                    $this->session->getFlashBag()->add("success", $status);
                } else {
                    $status = "La mesa NO se ha borrado correctamente";
                    $this->session->getFlashBag()->add("danger", $status);
                }
            } else {
                $status = "No se ha encontrado la mesa con id: " . $id;
                $this->session->getFlashBag()->add("danger", $status);
            }
        }
        return $this->redirectToRoute("table_index", array('id' => $id ));
    }

    public function qrAction (UserInterface $user = null, $tableid, $id){
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para generar QR');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes generar qr de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }

        $qrCode = new QrCode($this->generateUrl("product_index", array('id' => $id, 'tableid' => $tableid), UrlGeneratorInterface::ABSOLUTE_URL));
        $qr = $qrCode->writeDataUri();
        return $this->render("qr.html.twig", array(
            "qr" => $qr,
            "tableid" => $tableid
        ));
    }
}