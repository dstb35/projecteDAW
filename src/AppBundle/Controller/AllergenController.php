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

class AllergenController extends Controller {

	//private $em;
	private $session;

	public function __construct() {
		$this->session = new Session();
		//$this->em = $this->getDoctrine()->getManager();
	}

	public function indexAction() {
		$em = $this->getDoctrine()->getManager();
		$allergen_repo = $em->getRepository('AppBundle:Allergen');
		$allergens = $allergen_repo->findAll();

		return $this->render('allergen.html.twig', array(
					'allergens' => $allergens
		));
	}

	public function addAction(Request $request) {
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
					/* Copiado de la documentación, nombre seguro pero para alérgenos no hace falta
					 * $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
					  // this is needed to safely include the file name as part of the URL
					  $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
					  $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension(); */
					$filename = $imageFile->getClientOriginalName();
					try {
						/* $imageFile->move(
						  $this->getParameter('allergens_images'),
						  $newFilename

						  ); */
						$imageFile->move(
								$this->getParameter('allergens_images'),
								$filename
						);
					} catch (FileException $e) {
						$status = 'No se ha podido guardar la imagen' . $e->getMessage();
						$this->session->getFlashBag()->add('danger', $status);
						// ... handle exception if something happens during file upload
					}
					//$allergen->setImage($newFilename);
					$allergen->setImage($filename);
				}
				$em = $this->getDoctrine()->getManager();
				$em->persist($allergen);
				$flush = $em->flush();
				if ($flush == null) {
					$status = 'El alérgeno se ha creado correctamente';
					$this->session->getFlashBag()->add('success', $status);
					return $this->redirectToRoute('allergen_index');
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
					'title' => $title
		));
	}

	public function removeAction($id) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'No tienes acceso para borrar alérgenos');
        if (is_numeric($id) && $id > 0) {
			$em = $this->getDoctrine()->getManager();
			$allergen_repo = $em->getRepository('AppBundle:Allergen');
			$allergen = $allergen_repo->find($id);

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
				$status = 'No se ha encontrado el alérgeno con id: '.$id;
				$this->session->getFlashBag()->add('danger', $status);
			}
			return $this->redirectToRoute('allergen_index');
		}
	}
}
