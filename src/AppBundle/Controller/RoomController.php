<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Room;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\RoomAddForm;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomController extends Controller {
	
	/**
	 * @Route("/room/add", name="room_add")
	 */
	public function addAction(Request $request) {
		$form = $this->createForm(RoomAddForm::class);
		
		$form->handleRequest($request); 
		if($form->isSubmitted() && $form->isValid()){
			$room = $form->getData();
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($room);
			$em->flush();
		
			return $this->redirectToRoute('room_list');
		}
		
		return $this->render('room/add.html.twig',[
				'roomForm' => $form->createView()
		]);
	}
	
	/**
	 * @Route("/room/{roomId}", name="room_show")
	 */
	public function showAction($roomId) {
		
		$em = $this->getDoctrine()->getManager();
		
		$room = $em->getRepository('AppBundle:Room')
				->findOneBy(['id' => $roomId]);
		
		if(!$room) {
			throw $this->createNotFoundException('Room has gone missing!');
		}
		
		//To-Do Caching and logging
		
		return $this->render('room/show.html.twig', array(
				'room' => $room
		));
		
		
	}
	
	/**
	 * @Route("/room", name="room_list")
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();
		
		$rooms = $em->getRepository('AppBundle:Room')
				->findBy(array('building' => $this->getUser()->getPrimaryBuilding()));
		
		return $this->render('room/list.html.twig', [
				'rooms' => $rooms
		]);
	}
	
	public function searchAction() {
		
	}
	
	/**
	 * @Route("/room/{roomId}/bookings", name="room_show_bookings")
	 * @Method("GET")
	 */
	public function getBookingsAction(Room $room) {
		
		$bookings = [];
		
		foreach ($room->getBookings() as $booking) {
			array_push($bookings, [
					'id' => $booking->getId(),
					'organiser' => $booking->getOrganiser(),
					'room' => $booking->getRoom(),
					'startTime' => $booking->getStartTime(),
					'endTime' => $booking->getEndTime(),
					'extendedTime' => $booking->getExtendedTime(),
			]);
		}
		$data = [
			'bookings' => $bookings
		];
		
		return new JsonResponse($data);
	}
}