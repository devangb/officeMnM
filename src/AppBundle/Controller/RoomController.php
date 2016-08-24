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
use AppBundle\Entity\Building;
use AppBundle\Form\SearchForm;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
	 * @Route("/room/{roomId}/edit", name="room_edit")
	 * @param Request $request
	 */
	public function editAction(Request $request, $roomId) {
		$em = $this->getDoctrine()->getManager();
		$room = $em->getRepository('AppBundle:Room')
				->findOneBy(['id' => $roomId]);
		$form = $this->createForm(RoomAddForm::class, $room);
		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$room = $form->getData();
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($room);
			$em->flush();
			
			$this->addFlash('success', 'Room updated!');
			
			return $this->redirectToRoute('room_list');
		}
		
		return $this->render('room/edit.html.twig', [
				'roomForm' =>$form->createView()
		]);
	}
	
	/**
	 * @Route("/room/{roomId}", name="room_show")
	 */
	public function showAction($roomId) {
		
		$em = $this->getDoctrine()->getManager();
		
		$room = $em->getRepository('AppBundle:Room')
				->findOneBy(['id' => $roomId]);
		$building = $em ->getRepository('AppBundle:Building')
				->findOneBy(['id' => $room->getBuilding()]);
				
		if(!$room || $building->getOrganisation() != $this->getUser()->getUserOrganisation()) {
			throw $this->createNotFoundException('Room has gone missing!');
		}
		
		//To-Do Caching and logging
		
		return $this->render('room/show.html.twig', [
				'room' => $room
		]);
		
		
	}
	
	/**
	 * @Route("/room", name="room_list")
	 */
	public function listAction() {
		$em = $this->getDoctrine()->getManager();
		
		$rooms = $em
				->createQueryBuilder()->select('r')->from('AppBundle:Room', 'r')
				->innerjoin('r.building', 'b', 'WITH', 'b.id = r.building')
				->where('b.organisation = :organisation')
				->setParameter('organisation',$this->getUser()->getUserOrganisation())
				->getQuery()->getResult();
// 		$rooms = $em->getRepository('AppBundle:Room')	
// 			->findBy(array('building' => $this->getUser()->getUserOrganisation()));
		
		return $this->render('room/list.html.twig', [
				'rooms' => $rooms
		]);
	}
	
	/**
	 * @Route("/", name="homepage")
	 */
	public function searchAction(Request $request) {
		$buildings = $this->getUser()->getUserOrganisation()->getBuildings();
		$buildingNames = array();
		foreach($buildings as $building){
			$temp = $building->getBuildingName();
			$buildingNames["$temp"] = $building;
		}
		$form = $this->createFormBuilder()
				->add('building', ChoiceType::class, array('choices'=> $buildingNames))
				->add('capacity', IntegerType::class)
				->add('start_time', DateTimeType::class)
				->add('end_time', DateTimeType::class)
				->add('search', SubmitType::class)
				->getForm();
		
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$search = $form->getData();
			
			$em = $this->getDoctrine()->getManager();
			$rooms = $em
					->createQueryBuilder()->select('r')->from('AppBundle:Room', 'r')
					->innerjoin('r.building', 'b', 'WITH', 'b.id = r.building')
					->where('b.organisation = :organisation')
					->setParameter('organisation',$this->getUser()->getUserOrganisation())
					->andWhere('r.building = :building')
					->setParameter('building', $search["building"])
					->andWhere('r.capacity >= :capacity')
					->setParameter('capacity', $search["capacity"])
					->getQuery()->getResult();
			
			$unavailable_rooms = array();
			foreach ($rooms as $room) {
				foreach ($room->getRoomBookings() as $booking){
					if($booking->getEndTime() < $search["start_time"] &&
							$booking->getStartTime() > $search["start_time"]) {
						array_push($unavailable_rooms,$room);
						break;
					}
					elseif($booking->getStartTime() < $search["end_time"] &&
							$booking->getEndTime() > $search["end_time"]) {
						array_push($unavailable_rooms, $room);
						break;
					}
					elseif($booking->getStartTime() > $search["start_time"] && 
							$booking->getEndTime() < $search["end_time"]) {
						array_push($unavailable_rooms, $room);
						break;
					}
					elseif($booking->getStartTime() < $search["start_time"] &&
							$booking->getEndTime() > $search["end_time"]) {
							array_push($unavailable_rooms, $room);
							break;
					}
				}
			}
			dump($unavailable_rooms);
			$available_rooms = array_diff($rooms, $unavailable_rooms);
			
			dump($available_rooms);
			return $this->render('room/list.html.twig', [
				'rooms' => $available_rooms
			]);
		}
		
		return $this->render('room/search.html.twig',[
				'searchForm' => $form->createView()
		]);
	}
	
	
	/**
	 * @Route("/room/{roomId}/bookings", name="room_show_bookings")
	 * @Method("GET")
	 */
	public function getBookingsAction(Room $room) {
		
		$bookings = [];
		
		foreach ($room->getRoomBookings() as $booking) {
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