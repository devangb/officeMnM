<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Room;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\RoomAddForm;
use Symfony\Component\HttpFoundation\Request;

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
	 * @Route("/room/{roomName}", name="room_show")
	 */
	public function showAction($roomName) {
		
		$em = $this->getDoctrine()->getManager();
		
		$room = $em->getRepository('AppBundle:Room')
				->findOneBy(['room_name' => $roomName]);
		
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
				->findAll();
		
		return $this->render('room/list.html.twig', [
				'rooms' => $rooms
		]);
	}
	
	public function searchAction() {
		
	}
}