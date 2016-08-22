<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Organisation;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrganisationAddForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('organisation_name', TextType::class)
			->add('organisation_domain', TextType::class)
			->add('save', SubmitType::class, array('label' => 'Register'));
			
	}
	
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
				'data_class' => Organisation::class
		]);
	}
}