<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\BuildingAddForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BuildingController extends Controller {
	/**
	 * @Route("/building/add", name="building_add")
	 */
	public function addAction(Request $request) {
		if (! $this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		$form = $this->createForm ( BuildingAddForm::class );
		
		$form->handleRequest ( $request );
		if ($form->isSubmitted () && $form->isValid ()) {
			$building = $form->getData ();
			
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $building );
			$em->flush ();
			
			return $this->redirectToRoute ( 'building_list' );
		}
		
		return $this->render ( 'building/add.html.twig', [ 
				'buildingForm' => $form->createView () 
		] );
	}
	
	/**
	 * @Route("/building/{buildingId}/delete", name="building_delete")
	 */
	public function deleteAction($buildingId){
		$em = $this->getDoctrine()->getManager();
		
		$building = $em->getRepository('AppBundle:Building')
					->findOneBy(['id' => $buildingId]);
		foreach ($building->getRooms() as $room){
			$em->remove($room);
			$em->flush();
		}
		$em->remove($building);
		$em->flush();
	}
	
	/**
	 * @Route("/building", name="building_list")
	 */
	public function listAction() {
		if (! $this->getUser ()) {
			$this->addFlash ( 'notice', 'Login please!' );
			return $this->redirectToRoute ( 'security_login' );
		}
		
		try {
			$em = $this->getDoctrine ()->getManager ();
			$buildings = $em->getRepository ( 'AppBundle:Building' )->findAll ();
		} catch ( \Exception $e ) {
			error_log ( $e->getMessage () );
			$this->addFlash ( 'notice', 'Something went wrong!' );
		}
		return $this->render ( 'building/list.html.twig', [ 
				'buildings' => $buildings 
		] );
	}
	
	/**
	 * @Route("/building/{buildingId}/edit", name="building_edit")
	 */
	public function editAction($buildingId, Request $request) {
		$em = $this->getDoctrine ()->getManager ();
		
		$building = $em->getRepository ( 'AppBundle:Building' )->findOneBy ( [ 
				'id' => $buildingId 
		] );
		
		$form = $this->createForm ( BuildingAddForm::class, $building );
		
		$form->handleRequest ( $request );
		if ($form->isSubmitted () && $form->isValid ()) {
			$building = $form->getData ();
			
			$em = $this->getDoctrine ()->getManager ();
			$em->persist ( $building );
			$em->flush ();
			
			return $this->redirectToRoute ( 'building_list' );
		}
		
		return $this->render ( 'building/edit.html.twig', [ 
				'buildingForm' => $form->createView () 
		] );
	}
	
	
	/**
	 * @Route("/building/{building_name}/rooms", name="building_show_rooms")
	 * 
	 * @param Building $building        	
	 */
	public function getRoomsAction($building_name) {
		$em = $this->getDoctrine ()->getManager ();
		
		$building = $em->getRepository ( 'AppBundle:Building' )->findOneBy ( [ 
				'building_name' => $building_name 
		] );
		
		$rooms = [ ];
		
		foreach ( $building->getRooms () as $room ) {
			$rooms = [ 
					'id' => $room->getId (),
					'roomName' => $room->getRoomName (),
					'roomFloor' => $room->getRoomFloor (),
					'capacity' => $room->getCapacity () 
			];
		}
		
		$data = [ 
				'rooms' => $rooms 
		];
		
		return new JsonResponse ( $data );
	}
}