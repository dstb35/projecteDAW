<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class RestaurantType extends AbstractType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('cif', TextType::class, array("required"=>true, "attr" => array(
					"class" => "form-name form-control",
				)))
				->add('name', TextType::class, array("required"=>"required", "attr" => array(
					"class" => "form-name form-control",
				)))
				->add('password', PasswordType::class, array("required"=>"required", "attr" => array(
					"class" => "form-name form-control",
				)))
				->add('address', TextType::class, array("required"=>"required", "attr" => array(
					"class" => "form-name form-control",
				)))
				->add('email', EmailType::class, array("required"=>"required", "attr" => array(
					"class" => "form-email form-control",
				)))
				->add('phone', TelType::class, array("required"=>"required", "attr" => array(
					"class" => "form-name form-control",
				)))
				->add('manager', TextType::class, array("required"=>"required", "attr" => array(
					"class" => "form-name form-control",
				)))
				->add('Registrar', SubmitType::class, array("attr" => array(
					"class" => "form-submit btn btn-success",
				)));
	}

/**
	 * {@inheritdoc}
	 */

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Restaurant'
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix() {
		return 'appbundle_restaurant';
	}

}
