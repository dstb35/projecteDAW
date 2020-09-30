<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Employee;
use AppBundle\Form\EmployeeType;
use AppBundle\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;

class EmployeeController extends Controller {

	private $session;

	public function __construct() {
		$this->session = new Session();
	}

	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$employee_repo = $em->getRepository("AppBundle:Employee");
		$employees = $employee_repo->findAll();

		return $this->render("employee.html.twig", array(
					"employees" => $employees
		));
	}

	public function addAction(Request $request, UserInterface $user = null) {
		$title = "Añadir empleado";
		$employee = new Employee();
		$form = $this->createForm(EmployeeType::class, $employee);

		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			$employee->setName($form->get('name')->getData());
			$em = $this->getDoctrine()->getManager();
			$restaurant_repo = $em->getRepository("AppBundle:Restaurant");
			//$restaurant = $restaurant_repo->findByRestaurantId($user->getId);
			if ($user) {
				$employee->setRestaurantid($user);
				if ($form->isValid()) {
					$em = $this->getDoctrine()->getManager();
					$em->persist($employee);
					$flush = $em->flush();
					if ($flush == null) {
						$status = "El empleado se ha creado correctamente";
						$this->session->getFlashBag()->add("success", $status);
						return $this->redirectToRoute("employee_index");
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

	public function removeAction($id) {
		if (is_numeric($id) && $id > 0) {
			$em = $this->getDoctrine()->getManager();
			$employee_repo = $em->getRepository("AppBundle:Employee");
			$employee = $employee_repo->find($id);

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
				$status = "No se ha encontrado el empleado con id: " . $id;
				$this->session->getFlashBag()->add("danger", $status);
			}
			return $this->redirectToRoute("employee_index");
		}
	}

}
