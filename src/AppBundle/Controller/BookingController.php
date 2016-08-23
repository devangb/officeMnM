<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\BookingAddForm;
use Symfony\Component\HttpFoundation\Request;

class BookingController extends Controller {
	
	/**
	 * @Route("/booking/add", name="booking_add")
	 */
	public function addAction(Request $request) {
		$form = $this->createForm(BookingAddForm::class);
		
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$booking = $form->getData();
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($booking);
			$em->flush();
			
			return $this->redirectToRoute('room_show');
		}
		
		return $this->render('booking/add.html.twig', [
				'bookingForm' => $form->createView()
		]);
	}
}