<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Room;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\RoomAddForm;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Building;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 *
 * @author devang
 *        
 */
class RoomController extends Controller {
	
	/*
	 * Room add form
	 */
	/**
	 * @Route("/room/add", name="room_add")
	 */
	public function addAction(Request $request) {
		if (! $this->getUser ()) {
			$this->addFlash( 'notice', 'Login please!' );
			return $this->redirectToRoute('security_login');
		}
		$form = $this->createForm( RoomAddForm::class );
		
		$form->handleRequest ( $request );
		if ($form->isSubmitted () && $form->isValid ()) {
			$room = $form->getData ();
			
			try {
				$em = $this->getDoctrine ()->getManager ();
				$em->persist ( $room );
				$em->flush ();
			}
			catch (\Exception $e){
				error_log($e->getMessage());
				$this->addFlash('notice', 'Something went wrong!');
			}
			
			return $this->redirectToRoute( 'room_list' );
		}
		
		return $this->render( 'room/add.html.twig', [ 
				'roomForm' => $form->createView () 
		]);
	}
	
	
	/*
	 * Edit room form
	 */
	/**
	 * @Route("/room/{roomId}/edit", name="room_edit")
	 *
	 * @param Request $request        	
	 */
	public function editAction(Request $request, $roomId) {
		if (! $this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		try {
			$em = $this->getDoctrine()->getManager();
			$room = $em->getRepository( 'AppBundle:Room' )->findOneBy( [ 
					'id' => $roomId 
			]);
		}
		catch (\Exception $e) {
			error_log($e->getMessage());
			$this->addFlash('notice', 'Room has gone missing!');
		}
		$form = $this->createForm ( RoomAddForm::class, $room );
		
		$form->handleRequest( $request );
		if ($form->isSubmitted() && $form->isValid()) {
			$room = $form->getData();
			
			try {
				$em = $this->getDoctrine()->getManager();
				$em->persist( $room );
				$em->flush();
			
				$this->addFlash( 'success', 'Room updated!' );
			}
			catch (\Exception $e){
				error_log($e->getMessage());
				$this->addFlash('notice', 'Something went wrong!');
			}
			
			return $this->redirectToRoute('room_list');
		}
		
		return $this->render ( 'room/edit.html.twig', [ 
				'roomForm' => $form->createView() 
		] );
	}
	
	
	/*
	 * Show room for corresponding to roomId
	 */
	/**
	 * @Route("/room/{roomId}", name="room_show")
	 */
	public function showAction($roomId) {
		if (! $this->getUser()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		$em = $this->getDoctrine()->getManager();
		try {
			$room = $em->getRepository ( 'AppBundle:Room' )->findOneBy ( [ 
					'id' => $roomId 
			] );
		}
		catch (\Exception $e)
		{
			error_log($e->getMessage());
			$this->addFlash('notice', 'Something went wrong!');
		}
		try{
			$building = $em->getRepository('AppBundle:Building')->findOneBy( [ 
					'id' => $room->getBuilding () 
			]);
		}
		catch(\Exception $e) {
			error_log($e->getMessage());
			$this->addFlash('notice', 'Something went wrong!');
		}
		if (!$room || $building->getOrganisation () != $this->getUser ()->getUserOrganisation ()) {
			throw $this->createNotFoundException( 'Room has gone missing!' );
		}
		
		// To-Do Caching and logging
		
		return $this->render ( 'room/show.html.twig', [ 
				'room' => $room, 'currentUser' => $this->getUser()
		] );
	}
	
	
	/*
	 * Lists all the rooms in currentUsers organisation
	 */
	/**
	 * @Route("/room", name="room_list")
	 */
	public function listAction() {
		if (!$this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		try {
		$em = $this->getDoctrine ()->getManager ();
		$rooms = $em->createQueryBuilder ()
				->select ( 'r' )->from ( 'AppBundle:Room', 'r' )
				->innerjoin ( 'r.building', 'b', 'WITH', 'b.id = r.building' )
				->where ( 'b.organisation = :organisation' )
				->setParameter( 'organisation', $this->getUser ()->getUserOrganisation () )
				->getQuery ()->getResult ();
			// $rooms = $em->getRepository('AppBundle:Room')
			// ->findBy(array('building' => $this->getUser()->getUserOrganisation()));
		}
		catch (\Exception $e) {
			error_log($e->getMessage());
			$this->addFlash('notice', 'Something went wrong!');
		}
		return $this->render ( 'room/list.html.twig', [ 
				'rooms' => $rooms, 'currentUser' =>$this->getUser()
		]);
	}
	
	/**
	 * @Route("/", name="homepage")
	 */
	public function searchAction(Request $request) {
		if (! $this->getUser ()) {
			// $this->addFlash('notice', 'Login please!');
			return $this->redirectToRoute ( 'security_login' );
		}
		
		$buildings = $this->getUser()->getUserOrganisation()->getBuildings();
		$buildingNames = array ();
		foreach ( $buildings as $building ) {
			$temp = $building->getBuildingName ();
			$buildingNames ["$temp"] = $building;
		}
		$form = $this->createFormBuilder()
				->add ( 'building', ChoiceType::class, array('choices' => $buildingNames))
				->add ( 'capacity', IntegerType::class )
				->add ( 'start_time', DateTimeType::class, [ 
					'widget' => 'single_text',
					'attr' => [ 
						'class' => 'form-control input-inline datetimepicker',
						'data-provide' => 'datepicker',
						'data-date-format' => 'YYYY-MM-DD hh:mm' 
					]])
				->add ( 'end_time', DateTimeType::class, [ 
					'widget' => 'single_text',
					'attr' => [ 
						'class' => 'form-control input-inline datetimepicker',
						'data-provide' => 'datepicker',
						'data-date-format' => 'YYYY-MM-DD hh:mm' 
					]])
				->getForm ();
		
		$form->handleRequest ( $request );
		if ($form->isSubmitted () && $form->isValid ()) {
			$search = $form->getData ();
			if(!$search["capacity"]){
				$search["capacity"] = 0;
			}
			
			try {
				$em = $this->getDoctrine()->getManager();
				$rooms = $em->createQueryBuilder ()->select ( 'r' )
					->from ( 'AppBundle:Room', 'r' )
					->innerjoin ( 'r.building', 'b', 'WITH', 'b.id = r.building' )
					->where ( 'b.organisation = :organisation' )
					->setParameter ( 'organisation', $this->getUser()->getUserOrganisation())
					->andWhere ( 'r.building = :building' )
					->setParameter ( 'building', $search["building"] )
					->andWhere ( 'r.capacity >= :capacity' )
					->setParameter ( 'capacity', $search["capacity"] )
					->getQuery ()->getResult ();
			}
			catch (\Exception $e) {
				error_log($e->getMessage());
				$this->addFlash('notice', 'Something went wrong!');
			}
			
			$unavailable_rooms = array ();
			foreach ( $rooms as $room ) {
				foreach ( $room->getRoomBookings () as $booking ) {
					if ($booking->getExtendedTime () < $search["start_time"]
							&& $booking->getStartTime () > $search["start_time"]) {
						array_push ( $unavailable_rooms, $room );
						break;
					} elseif ($booking->getStartTime () < $search["end_time"]
							&& $booking->getExtendedTime () > $search["end_time"]) {
						array_push ( $unavailable_rooms, $room );
						break;
					} elseif ($booking->getStartTime () > $search["start_time"]
							&& $booking->getExtendedTime () < $search["end_time"]) {
						array_push ( $unavailable_rooms, $room );
						break;
					} elseif ($booking->getStartTime () < $search["start_time"]
							&& $booking->getExtendedTime () > $search["end_time"]) {
						array_push ( $unavailable_rooms, $room );
						break;
					}
				}
			}
			
			$available_rooms = array_diff ( $rooms, $unavailable_rooms );
			
			if(empty(array_filter($available_rooms))) {
				$this->addFlash('warning', 'No rooms available!');
			}
			
			return $this->render ( 'room/list.html.twig', [ 
					'rooms' => $available_rooms 
			] );
		}
		
		return $this->render ( 'room/search.html.twig', [ 
				'searchForm' => $form->createView () 
		] );
	}
	
	
	/*
	 * Get method for getting bookings for Rooms
	 */
	/**
	 * @Route("/room/{roomId}/bookings", name="room_show_bookings")
	 *
	 * @method ("GET")
	 */
	public function getBookingsAction(Room $room) {
		$bookings = [ ];
		
		foreach ( $room->getRoomBookings () as $booking ) {
			array_push ( $bookings, [ 
					'id' => $booking->getId (),
					'organiser' => $booking->getOrganiser (),
					'room' => $booking->getRoom (),
					'startTime' => $booking->getStartTime (),
					'endTime' => $booking->getEndTime (),
					'extendedTime' => $booking->getExtendedTime () 
			] );
		}
		$data = [ 
				'bookings' => $bookings 
		];
		
		return new JsonResponse( $data );
	}
}