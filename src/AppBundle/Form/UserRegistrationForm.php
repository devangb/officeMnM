<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use AppBundle\Entity\User;

class UserRegistrationForm extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('email', EmailType::class)
			->add('plainPassword', RepeatedType::class, [
					'type' => PasswordType::class
			]);
	}
	
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults([
				'data_class' => User::class,
				'validation_groups' => ['Default', 'Registration']
		]);
	}
}