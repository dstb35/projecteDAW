<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
				->add('name', TextType::class, array(
					"required" => true, 
					"label" => "Nombre de la categoría",
					"attr" => array(
						"class" => "form-control",
						
			)))
				->add('Guardar', SubmitType::class, array("attr" => array(
						"class" => "form-submit btn btn-success",
		)));
	}

	/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Category'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_category';
    }


}
