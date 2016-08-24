<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Booking;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingAddForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			//->add('room')
			//->add('organiser')
			->add('purpose', TextareaType::class)
			->add('start_time', DateTimeType::class)
			->add('end_time', DateTimeType::class)
			->add('extended_time', DateTimeType::class)
			->add('save', SubmitType::class, array('label' => 'Book it!'));
	}
	
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
				'data_class' => Booking::class
		]);
	}
}