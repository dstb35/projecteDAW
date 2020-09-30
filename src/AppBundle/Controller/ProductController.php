<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
//use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class ProductController extends Controller {

	private $session;

	public function __construct() {
		$this->session = new Session();
	}

	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$product_repo = $em->getRepository("AppBundle:Product");
		$products = $product_repo->findAll();

		return $this->render("product.html.twig", array(
					"products" => $products
		));
	}

	public function addAction(Request $request, UserInterface $user = null) {
		$title = "Añadir producto";
		$product = new Product();
		$form = $this->createForm(ProductType::class, $product);

		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			$product = $form->getData();
			$em = $this->getDoctrine()->getManager();
			if ($user) {
				$product->setRestaurantid($user);
				if ($form->isValid()) {
					$imageFile = $form->get('image')->getData();
					if ($imageFile) {
						$originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
						// this is needed to safely include the file name as part of the URL
						$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
						$newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
						try {
							$imageFile->move(
									$this->getParameter('products_images'),
									$newFilename
							);
						} catch (FileException $e) {
							$status = "No se ha podido guardar la imagen" . $e->getMessage();
							$this->session->getFlashBag()->add("danger", $status);
						}
						$product->setImage($newFilename);
					}
					$em = $this->getDoctrine()->getManager();
					$em->persist($product);
					$flush = $em->flush();
					if ($flush == null) {
						$status = "El producto se ha creado correctamente";
						$this->session->getFlashBag()->add("success", $status);
						return $this->redirectToRoute("product_index");
					} else {
						$status = "El producto NO se ha creado correctamente";
						$this->session->getFlashBag()->add("danger", $status);
					}
				} else {
					/*$errors = $form->getErrors(true);
					foreach ($errors as $error) {
						$this->session->getFlashBag()->add("danger", $error->getMessage());
					}*/
					$this->session->getFlashBag()->add("danger", "Form no válido");
					unset($product);
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
			$product_repo = $em->getRepository("AppBundle:Product");
			$product = $product_repo->find($id);

			if ($product) {
				try {
					$fs = new Filesystem();
					$fs->remove($this->getParameter('products_images') . '/' . $product->getImage());
				} catch (IOException $e) {
					$status = "No se ha podido borrar el archivo de imagen" . $e->getMessage();
					$this->session->getFlashBag()->add("danger", $status);
				}
				$em->remove($product);
				$flush = $em->flush();

				if ($flush == null) {
					$status = "El producto se ha borrado correctamente";
					$this->session->getFlashBag()->add("success", $status);
				} else {
					$status = "El producto NO se ha borrado correctamente";
					$this->session->getFlashBag()->add("danger", $status);
				}
			} else {
				$status = "No se ha encontrado el producto con id: " . $id;
				$this->session->getFlashBag()->add("danger", $status);
			}
			return $this->redirectToRoute("product_index");
		}
	}

}
