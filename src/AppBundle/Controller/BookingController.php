<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\BookingAddForm;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Booking;
use AppBundle\Entity\Room;
use AppBundle\Form\BookingEditForm;

class BookingController extends Controller {
	
	/**
	 * @Route("room/{roomId}/booking/add", name="booking_add")
	 */
	public function addAction(Request $request, $roomId, $capacity = 0) {
		if (!$this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		$em = $this->getDoctrine ()->getManager ();
		$room = $em->getRepository ( 'AppBundle:Room' )->findOneBy ( array (
				'id' => $roomId 
		) );
		
		$booking = new Booking ();
		$booking->setOrganiser ( $this->getUser () );
		$booking->setRoom ( $room );
		$form = $this->createForm ( BookingAddForm::class, $booking );
		
		$form->handleRequest ( $request );
		if ($form->isSubmitted () && $form->isValid ()) {
			$booking = $form->getData ();
			$availability = 1;
			foreach ( $room->getRoomBookings() as $checkbooking ) {
				if ($checkbooking->getExtendedTime() < $booking->getStartTime()
						&& $checkbooking->getStartTime () > $booking->getStartTime()) {
					$availability = 0;
					break;
				} elseif ($checkbooking->getStartTime() < $booking->getExtendedTime()
						&& $checkbooking->getExtendedTime() > $booking->getExtendedTime()) {
					$availability = 0;
					break;
				} elseif ($checkbooking->getStartTime() > $booking->getStartTime() && $checkbooking->getExtendedTime() < $booking->getExtendedTime()) {
					$availability = 0;
					break;
				} elseif ($checkbooking->getStartTime() < $booking->getStartTime() && $checkbooking->getExtendedTime() > $booking->getExtendedTime()) {
					$availability = 0;
					break;
				}
			}
			if($booking->getEndTime() < $booking->getStartTime()){
				$this->addFlash('warning', 'End time cannot be before start time');
			}
			elseif ($availability == 0) {
				$this->addFlash('warning', 'Room is already booked for this time');
			}
			else {
			$booking->setExtendedTime($booking->getEndTime());
			
			try {
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $booking );
			$em->flush ();
			}
			catch (\Exception $e) {
				error_log($e->getMessage());
				$this->addFlash('notice', 'Something went wrong');
			}
			
			return $this->redirectToRoute ( 'room_show', array (
					'roomId' => $roomId 
			) );
			}
		}
		
		return $this->render ( 'booking/add.html.twig', [ 
				'bookingForm' => $form->createView (), 'room' => $room
		] );
	}
	
	/**
	 * @Route("/booking/{bookingId}/edit", name="booking_edit")
	 */
	public function editAction(Request $request, $bookingId) {
		if (! $this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		$em = $this->getDoctrine ()->getManager ();
// 		$room = $em->getRepository ( 'AppBundle:Room' )->findOneBy ( array (
// 				'id' => $roomId
// 		) );
		$booking = $em->getRepository ( 'AppBundle:Booking' )->findOneBy ( array (
				'id' => $bookingId 
		) );
		$form = $this->createForm ( BookingEditForm::class, $booking );
		
		$form->handleRequest ( $request );
		if ($form->isSubmitted () && $form->isValid ()) {
			$booking = $form->getData ();
			
			// Reallocation for conflicting bookings
			/*********************************
			if($booking->getExtendedTime() != $booking->getEndTime()) {
				$bookings = $booking->getRoom()->getRoomBookings();
				foreach ($bookings as $checkbooking) {
					if ($checkbooking->getStartTime() > $booking->getEndTime() 
							&& $checkbooking->getStartTime() < $booking->getExtendedTime()) {
								
								$em = $this->getDoctrine ()->getManager ();
								$rooms = $em->getRepository('AppBundle:Room')
									->findBy([ 'building' => $booking->getRoom()->getBuilding()]);
								
								$unavailable_rooms = array ();
								foreach ( $rooms as $room ) {
									if ($room->getCapacity() < $booking->getCapacity()){
										array_push($unavailable_rooms, $room);
									}
									else {
									foreach ( $room->getRoomBookings() as $booking ) {
										if ($booking->getExtendedTime() < $checkbooking->getStartTime() && $booking->getStartTime () > $checkbooking->getStartTime()) {
											array_push ( $unavailable_rooms, $room );
											break;
										} elseif ($booking->getStartTime() < $checkbooking->getExtendedTime() && $booking->getExtendedTime() > $checkbooking->getExtendedTime()) {
											array_push ( $unavailable_rooms, $room );
											break;
										} elseif ($booking->getStartTime() > $checkbooking->getStartTime() && $booking->getExtendedTime() < $checkbooking->getExtendedTime()) {
											array_push ( $unavailable_rooms, $room );
											break;
										} elseif ($booking->getStartTime() < $checkbooking->getStartTime() && $booking->getExtendedTime() > $checkbooking->getExtendedTime()) {
											array_push ( $unavailable_rooms, $room );
											break;
										}
									}
									}
								}
										
								$available_rooms = array_diff ( $rooms, $unavailable_rooms );
								if(!$available_rooms) {
									$this->addFlash('notice', 'Extension not possible');
								}
							}
				}
			}
			*******************************/
			
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $booking );
			$em->flush ();
			
			$this->addFlash ( 'success', 'Booking updated' );
			
			return $this->redirectToRoute ( 'room_show', array (
					'roomId' => $booking->getRoom()->getRoomId()
			) );
		}
		
		return $this->render ( 'booking/edit.html.twig', [ 
				'bookingForm' => $form->createView (), 'room' => $booking->getRoom()
		] );
	}
	
		/**
	 * @Route("/booking/{bookingId}/delete", name="booking_delete")
	 */
	public function deleteAction($bookingId) {
		if (! $this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		$booking = $em->getRepository ( 'AppBundle:Booking' )->findOneBy ( [ 
				'id' => $bookingId 
		] );
		if ($booking->getOrganiser () == $this->getUser ()) {
			$em->remove ( $booking );
			$em->flush ();
		} 

		else {
			$this->addFlash ( 'notice', 'Not authorised to delete this booking' );
		}
		return $this->redirectToRoute ( 'user_bookings' );
	}
}