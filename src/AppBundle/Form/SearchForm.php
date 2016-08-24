<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Room;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Building;

class SearchForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('building', array('choices'=> $options['buildings']))
			->add('capacity', IntegerType::class)
			->add('start_time', DateTimeType::class)
			->add('end_time', DateTimeType::class)
			->add('search', SubmitType::class);
	}
	
	public function configureOptions(OptionsResolver $resolver){
		$resolver->setDefaults([
		]);
	}
}