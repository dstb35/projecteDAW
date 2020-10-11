<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;


class OrderController extends Controller {

	private $session;

	public function __construct() {
		$this->session = new Session();
	}

	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$order_repo = $em->getRepository("AppBundle:Order");
		$orders = $order_repo->findAll();

		return $this->render("order.html.twig", array(
					"orders" => $orders
		));
	}

	public function addAction(Request $request, UserInterface $user = null) {
		$title = "Añadir pedido";
		/*$em = $this->getDoctrine()->getManager();
		$order = new Order();
		$form = $this->createForm(OrderType::class, $order);

		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			$order = $form->getData();
			if ($user) {
				$order->setRestaurantid($user);
				if ($form->isValid()) {
					$imageFile = $form->get('image')->getData();
					if ($imageFile) {
						$originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
						// this is needed to safely include the file name as part of the URL
						$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
						$newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
						try {
							$imageFile->move(
									$this->getParameter('orders_images'),
									$newFilename
							);
						} catch (FileException $e) {
							$status = "No se ha podido guardar la imagen" . $e->getMessage();
							$this->session->getFlashBag()->add("danger", $status);
						}
						$order->setImage($newFilename);
					}
					
					$em->persist($order);
					$flush = $em->flush();

					if ($flush == null) {
						$status = "El pedido se ha creado correctamente";
						$this->session->getFlashBag()->add("success", $status);
						return $this->redirectToRoute("order_index");
					} else {
						$status = "El pedido NO se ha creado correctamente";
						$this->session->getFlashBag()->add("danger", $status);
					}
				} else {
					$this->session->getFlashBag()->add("danger", "Form no válido");
					unset($order);
				}
			} else {
				$status = "El restaurante no es válido o el usuario no está identificado";
				$this->session->getFlashBag()->add("danger", $status);
			}
		}*/

		return $this->render("add.html.twig", array(
					"form" => $form->createView(),
					"title" => $title
		));
	}

	public function removeAction($id) {
		if (is_numeric($id) && $id > 0) {
			$em = $this->getDoctrine()->getManager();
			$order_repo = $em->getRepository("AppBundle:Order");
			$order = $order_repo->find($id);

			if ($order) {
				$em->remove($order);
				$flush = $em->flush();

				if ($flush == null) {
					$status = "El pedido se ha borrado correctamente";
					$this->session->getFlashBag()->add("success", $status);
				} else {
					$status = "El pedido NO se ha borrado correctamente";
					$this->session->getFlashBag()->add("danger", $status);
				}
			} else {
				$status = "No se ha encontrado el pedido con id: " . $id;
				$this->session->getFlashBag()->add("danger", $status);
			}
			return $this->redirectToRoute("order_index");
		}
	}

}
