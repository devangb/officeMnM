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
		$form = $this->createForm(BuildingAddForm::class);
		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$building = $form->getData();
			
			$em = $this->getDoctrine()->getManager();
			$em->persist($building);
			$em->flush();
			
			return $this->redirectToRoute('building_list');
		}
		
		return $this->render('building/add.html.twig', [
				'buildingForm' => $form->createView()
		]);
	}
	
	/**
	 * @Route("/building/{building_name}/rooms", name="building_show_rooms")
	 * @param Building $building
	 */
	public function getRoomsAction($building_name) {
		$em = $this->getDoctrine()->getManager();
		
		$building = $em->getRepository('AppBundle:Building')
				->findOneBy(['building_name' => $building_name]);
		
		$rooms = [];
		
		foreach ($building->getRooms() as $room) {
			$rooms = [
					'id' => $room->getId(),
					'roomName' => $room->getRoomName(),
					'roomFloor' => $room->getRoomFloor(),
					'capacity' => $room->getCapacity()
			];
		}
		
		$data = [
			'rooms' => $rooms
		];
		
		return new JsonResponse($data);
	}
}