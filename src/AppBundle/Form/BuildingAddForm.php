<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Building;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class BuildingAddForm extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('organisation')
			->add('building_name', TextType::class)
			->add('save', SubmitType::class, array('label' => 'Add building'));
	}
	
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
				'data_class' => Building::class
		]);
	}
}