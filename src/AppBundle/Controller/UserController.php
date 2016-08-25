<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller {
	
	/* 
	 * User registration
	 * 
	 * @param Request $request
	 */
	/**
	 * @Route("/register", name="user_register")
	 */
	public function registerAction(Request $request) {
		if ($this->getUser ()) {
			return $this->render ( 'user/loggedin.html.twig', [ 
					'user' => $this->getUser () 
			] );
		}
		$form = $this->createForm ( UserRegistrationForm::class );
		
		$form->handleRequest ( $request );
		if ($form->isValid ()) {
			/**@var User user **/
			$user = $form->getData ();
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $user );
			$em->flush ();
			
			$this->addFlash ( 'success', 'Welcome ' . $user->getEmail () );
			
			return $this->get ( 'security.authentication.guard_handler' )->authenticateUserAndHandleSuccess ( $user, $request, $this->get ( 'app.security.login_form_authenticator' ), 'main' );
		}
		
		return $this->render ( 'user/register.html.twig', [ 
				'form' => $form->createView () 
		] );
	}
	
	/* 
	 * Edit User profile
	 * 
	 * @param Request @request
	 */
	/**
	 * @Route("/editprofile", name="edit_profile")
	 * 
	 * @param Request $request        	
	 */
	public function editAction(Request $request) {
		if (! $this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		$form = $this->createForm ( UserRegistrationForm::class, $this->getUser () );
		
		$form->handleRequest ( $request );
		if ($form->isValid ()) {
			/**@var User user **/
			$user = $form->getData ();
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $user );
			$em->flush ();
			
			$this->addFlash ( 'success', 'Welcome ' . $user->getEmail () );
			
			return $this->redirectToRoute ( 'homepage' );
		}
		
		return $this->render ( 'user/register.html.twig', [ 
				'form' => $form->createView () 
		] );
	}
	
	
	/*
	 * Get all user bookings
	 * 
	 * 
	 */
	/**
	 * @Route("/mybookings", name="user_bookings")
	 */
	public function getUserBookingsAction() {
		if (! $this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		$bookings = $this->getUser ()->getUserBookings ();
		
		return $this->render ( 'user/bookings.html.twig', [ 
				'bookings' => $bookings
		] );
		
		// $bookings = [];
		
		// foreach ($this->getUser()->getUserBookings() as $booking) {
		// array_push($bookings, [
		// 'id' => $booking->getId(),
		// 'organiser' => $booking->getOrganiser(),
		// 'room' => $booking->getRoom(),
		// 'startTime' => $booking->getStartTime(),
		// 'endTime' => $booking->getEndTime(),
		// 'extendedTime' => $booking->getExtendedTime(),
		// ]);
		// }
		// $data = [
		// 'bookings' => $bookings
		// ];
		
		// return new JsonResponse($data);
	}
}