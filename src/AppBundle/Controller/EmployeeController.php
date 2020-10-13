<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Employee;
use AppBundle\Form\EmployeeType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class EmployeeController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(UserInterface $user = null, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para ver empleados');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes ver empleados de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $employee_repo = $em->getRepository("AppBundle:Employee");
        $employees = $employee_repo->findBy(array('restaurantid' => $id));
        $restaurant = $em->getRepository('AppBundle:Restaurant')->find($id);
        if (isset($restaurant)) {
            $title = 'Empleados para restaurante ' . $restaurant->getName();
        } else {
            $title = 'Restaurante no encontrado para id: ' . $id;
        }

        return $this->render("employee.html.twig", array(
            'employees' => $employees,
            'title' => $title,
            'id' => $id
        ));
    }

    public function addAction(Request $request, UserInterface $user = null, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para crear empleados');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes añadir empleados de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        $title = "Añadir empleado";
        $employee = new Employee();
        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $employee->setName($form->get('name')->getData());
            if ($user) {
                $employee->setRestaurantid($user);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($employee);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $status = "El empleado se ha creado correctamente";
                        $this->session->getFlashBag()->add("success", $status);
                        return $this->redirectToRoute("employee_index", array('id' => $id));
                    } else {
                        $status = "El empleado NO se ha creado correctamente";
                        $this->session->getFlashBag()->add("danger", $status);
                    }
                } else {
                    $errors = $form->getErrors(true);
                    foreach ($errors as $error) {
                        $this->session->getFlashBag()->add("danger", $error->getMessage());
                    }
                }
            } else {
                $status = "El restaurante no es válido o el usuario no está identificado";
                $this->session->getFlashBag()->add("danger", $status);
            }
        }

        return $this->render("add.html.twig", array(
            "form" => $form->createView(),
            "title" => $title
        ));
    }

    public function editAction(Request $request, UserInterface $user = null, $employeeid, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para editar empleados');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes editar empleados de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        $title = 'Editar empleado: ';
        $em = $this->getDoctrine()->getManager();
        $employee = $em->getRepository('AppBundle:Employee')->find($employeeid);
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($user) {
                if ($form->isValid()) {
                    $em->persist($employee);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $status = 'El empleado se ha editado correctamente';
                        $this->session->getFlashBag()->add('success', $status);
                        return $this->redirectToRoute('employee_index', array('id' => $id));
                    } else {
                        $status = 'El empleado NO se ha editado correctamente';
                        $this->session->getFlashBag()->add('danger', $status);
                    }
                } else {
                    $this->session->getFlashBag()->add('danger', 'Form no válido');
                    unset($employee);
                }
            } else {
                $status = 'El restaurante no es válido o el usuario no está identificado';
                $this->session->getFlashBag()->add('danger', $status);
            }
        }

        return $this->render('add.html.twig', array(
            'form' => $form->createView(),
            'title' => $title,
            'id' => $id
        ));
    }

    public function removeAction(UserInterface $user = null, $id, $employeeid)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'No tienes acceso para borrar empleados');
        if ($user->getRestaurantid() != $id) {
            $status = 'No puedes borrar empleados de este restaurante';
            $this->session->getFlashBag()->add('danger', $status);
            return $this->redirectToRoute('homepage');
        }
        if (is_numeric($employeeid) && $employeeid > 0) {
            $em = $this->getDoctrine()->getManager();
            $employee_repo = $em->getRepository("AppBundle:Employee");
            $employee = $employee_repo->find($employeeid);

            if ($employee) {
                $em->remove($employee);
                $flush = $em->flush();

                if ($flush == null) {
                    $status = "El empleado se ha borrado correctamente";
                    $this->session->getFlashBag()->add("success", $status);
                } else {
                    $status = "El empleado NO se ha borrado correctamente";
                    $this->session->getFlashBag()->add("danger", $status);
                }
            } else {
                $status = "No se ha encontrado el empleado con id: " . $employeeid;
                $this->session->getFlashBag()->add("danger", $status);
            }
        }
        return $this->redirectToRoute("employee_index", array('id' => $id));
    }

}
