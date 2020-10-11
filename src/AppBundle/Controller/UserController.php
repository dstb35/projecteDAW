<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Restaurant;
use AppBundle\Form\RestaurantType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller {

	private $session;

	public function __construct() {
		$this->session = new Session();
	}

	public function loginAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {

		$authenticationUtils = $this->get('security.authentication_utils');
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		$restaurant = new Restaurant();
		$form = $this->createForm(RestaurantType::class, $restaurant);
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			/* $validator = Validation::createValidatorBuilder()
			  ->addYamlMapping('../src/AppBundle/Resources/config/validation.yml')
			  ->getValidator(); */
			$restaurant = $form->getData();
			//$errors = $validator->validate($restaurant);
			/* if (count($errors) !== 0) {
			  $status = (string) $errors;
			  } elseif ($form->isValid()) {
			 * 
			 */
			if ($form->isValid()) {
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

}
