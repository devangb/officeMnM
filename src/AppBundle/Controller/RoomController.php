<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Room;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller {
	
	/**
	 * @Route("/room/add", name="room_add")
	 */
	public function addAction() {
		$room = new Room();
		$room->setRoomName('Room'.rand(1,100));
		$room->setRoomFloor(rand(0,4));
		$room->setCapacity(rand(5,40));
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($room);
		$em->flush();
		
		return new Response('<html><body>Room Created</body></html>');
	}
	
	/**
	 * @Route("/room/{roomName}", name="room_show")
	 */
	public function showAction($roomName) {
		
		$em = $this->getDoctrine()->getManager();
		
		$room = $em->getRepository('AppBundle:Room')
				->findOneBy(['name' => $roomName]);
		
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