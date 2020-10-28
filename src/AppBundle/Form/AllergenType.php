<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class AllergenType extends AbstractType {

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('name', TextType::class, array(
                    'label' => 'Nombre',
                    'required' => true, 'attr' => array(
						'class' => 'form-name form-control'
			)))
				->add('image', FileType::class, array(
                    'label' => 'Imagen',
                    'required' => false,
					'attr' => array(
						'class' => 'form-name form-control',
                    ),
					// unmapped means that this field is not associated to any entity property
					'mapped' => false,
					// unmapped fields can't define their validation using annotations
					// in the associated entity, so you can use the PHP constraint classes
					'constraints' => [
						new File([
							'maxSize' => '2048k',
							'maxSizeMessage' => 'Archivo demasiado grande (max 2mb)',
							'mimeTypes' => [
								'image/png',
								'image/jpeg'
							],
							'mimeTypesMessage' => 'Formato de archivo no válido',
								])
					]
				))
				->add('Guardar', SubmitType::class, array('attr' => array(
						'class' => 'form-submit btn btn-success',
		)));
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Allergen'
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix() {
		return 'appbundle_allergen';
	}

}
