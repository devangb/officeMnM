<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Booking;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BookingAddForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->
		// ->add('room')
		// ->add('organiser')
		add ( 'purpose', TextareaType::class )->add('capacity', IntegerType::class)->add ( 'start_time', DateTimeType::class, [ 
				'widget' => 'single_text',
				'attr' => [ 
						'class' => 'form-control input-inline datetimepicker',
						'data-provide' => 'datepicker',
						'data-date-format' => 'YYYY-MM-DD hh:mm' 
				] 
		] )->add ( 'end_time', DateTimeType::class, [ 
				'widget' => 'single_text',
				'attr' => [ 
						'class' => 'form-control input-inline datetimepicker',
						'data-provide' => 'datepicker',
						'data-date-format' => 'YYYY-MM-DD hh:mm' 
				] 
		] );
		// ->add('extended_time', DateTimeType::class);
	}
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults ( [ 
				'data_class' => Booking::class 
		] );
	}
}