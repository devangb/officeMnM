<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\LoginForm;

class SecurityController extends Controller {
	
	/*
	 * Login handling
	 */
	/**
	 * @Route("/login", name="security_login")
	 */
	public function loginAction() {
		if ($this->getUser ()) {
			return $this->render ( 'user/loggedin.html.twig', [ 
					'user' => $this->getUser () 
			] );
		}
		
		$authenticationUtils = $this->get( 'security.authentication_utils' );
		
		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();
		
		$form = $this->createForm ( LoginForm::class, [ 
				'_username' => $lastUsername 
		] );
		
		return $this->render ( 'security/login.html.twig', array (
				'form' => $form->createView (),
				'error' => $error 
		) );
	}
	
	/*
	 * Logout action
	 * Should never be reached
	 */
	/**
	 * @Route("/logout", name="security_logout")
	 */
	public function logoutAction() {
		throw new \Exception ( "This should not be reached" );
	}
}