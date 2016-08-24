<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\BookingAddForm;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Booking;
use AppBundle\Entity\Room;

class BookingController extends Controller {
	
	
	/**
	 * @Route("room/{roomId}/booking/add", name="booking_add")
	 */
	public function addAction(Request $request, $roomId) {
		$em = $this->getDoctrine()->getManager();
		$room = $em->getRepository('AppBundle:Room')
				->findOneBy(array('id' => $roomId));
		
		$booking = new Booking();
		$booking->setOrganiser($this->getUser());
		$booking->setRoom($room);
		$form = $this->createForm(BookingAddForm::class, $booking);
		
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$booking = $form->getData();
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($booking);
			$em->flush();
			
			return $this->redirectToRoute('room_show',array('roomId' => $roomId));
		}
		
		return $this->render('booking/add.html.twig', [
				'bookingForm' => $form->createView()
		]);
	}
	
	/**
	 * @Route("room/{roomId}/booking/edit", name="booking_edit")
	 */
	public function editAction(Request $request, $roomId) {
		$em = $this->getDoctrine()->getManager();
		$booking = $em->getRepository('AppBundle:Booking')
				->findOneBy(array('id' => $roomId));
		$form = $this->createForm(BookingAddForm::class, $booking);
		
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$booking = $form->getData();
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($booking);
			$em->flush();
			
			$this->addFlash('success', 'Booking updated');
			
			return $this->redirectToRoute('room_show',array('roomId' => $roomId));
		}
		
		return $this->render('booking/add.html.twig', [
				'bookingForm' => $form->createView()
		]);
	}
	
}