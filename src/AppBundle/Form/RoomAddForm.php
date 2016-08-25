<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Room;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class RoomAddForm extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('building')
			->add('room_name', TextType::class)
			->add('capacity', IntegerType::class)
			->add('room_floor', IntegerType::class)
			->add('has_projector', CheckboxType::class)
			->add('has_whiteboard', CheckboxType::class)
			->add('save', SubmitType::class, array('label' => 'Add room'));
	}
	
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'data_class' => Room::class	
		]);
	}
}