<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Table;
use AppBundle\Form\TableType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;


class TableController extends Controller {

	private $session;

	public function __construct() {
		$this->session = new Session();
	}

	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$table_repo = $em->getRepository("AppBundle:Table");
		$tables = $table_repo->findAll();

		return $this->render("table.html.twig", array(
					"tables" => $tables
		));
	}

	public function addAction(Request $request, UserInterface $user = null) {
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
						return $this->redirectToRoute("table_index");
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
					"title" => $title
		));
	}

	public function removeAction($id) {
		if (is_numeric($id) && $id > 0) {
			$em = $this->getDoctrine()->getManager();
			$table_repo = $em->getRepository("AppBundle:Table");
			$table = $table_repo->find($id);

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
			return $this->redirectToRoute("table_index");
		}
	}

}
