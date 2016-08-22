<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Organisation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Building;
use AppBundle\Entity\Room;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\OrganisationAddForm;
use Symfony\Component\HttpFoundation\Request;

class OrganisationController extends Controller {
	
	/**
	 * @Route("/organisation/add", name="organisation_add")
	 * 
	 */
	public function addAction(Request $request) {
// 		$organisation = new Organisation();
// 		$organisation->setOrganisationName('Facebook');
// 		$organisation->setOrganisationDomain('google.com');
		
// 		$building1 = new Building();
// 		$building1->setBuildingName('Jupiter');
// 		$building1->setOrganisation($organisation);
		
// 		$room2 = new Room();
// 		$room2->setBuilding($building1);
// 		$room2->setRoomName('Rohan');
// 		$room2->setRoomFloor(2);
// 		$room2->setCapacity(23);
		
// 		$room1 = new Room();
// 		$room1->setBuilding($building1);
// 		$room1->setRoomName('Moria');
// 		$room1->setRoomFloor(3);
// 		$room1->setCapacity(25);
		
// 		$em = $this->getDoctrine()->getManager();
// 		$em->persist($organisation);
// 		$em->persist($building1);
// 		$em->persist($room1);
// 		$em->persist($room2);
// 		$em->flush();

		$form = $this->createForm(OrganisationAddForm::class);
		
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()) {
			$organisation = $form->getData();
				
			$em = $this->getDoctrine()->getManager();
			$em->persist($organisation);
			$em->flush();
			
			return $this->redirectToRoute('organisation_list');
		}
		
		return $this->render('organisation/add.html.twig', [
			'organisationForm' => $form->createView()	
		]);
		
	}
	
	/**
	 * @Route("/organisation/{organisation_name}/buildings", name="organisation_show_buildings")
	 * @Method("GET")
	 * 
	 * @param Organisation $organisation
	 */
	public function getBuildingsAction($organisation_name) {
		$em = $this->getDoctrine()->getManager();
		
		$organisation = $em->getRepository('AppBundle:Organisation')
		->findOneBy(['organisation_name' => $organisation_name]);
		
		$buildings = [];
		
		foreach ($organisation->getBuildings() as $building) {
			array_push($buildings, [
					'id' => $building->getId(),
					'buildingName' => $building->getBuildingName(),
			]);
		}
		
		$data = [
			'buildings' => $buildings
		];
		
		return new JsonResponse($data);
	}
	
	/**
	 * @Route("/organisation/{organisation_name}/rooms", name="organisation_show_rooms")
	 * 
	 * @param string $organisation_name
	 */
	public function getRoomsActions($organisation_name) {
		$em = $this->getDoctrine()->getManager();
		
		$organisation = $em->getRepository('AppBundle:Organisation')
				->findOneBy(['organisation_name' => $organisation_name]);
		
		$rooms = [];
		
		foreach ($organisation->getBuildings() as $building) {
			foreach ($building->getRooms() as $room) {
				array_push($rooms, [
						'id' => $room->getId(),
						'roomName' => $room->getRoomName(),
						'roomFloor' => $room->getRoomFloor(),
						'capacity' => $room->getCapacity()
				]);
			}
		}
		
		$data = [
			'rooms' => $rooms
		];
		
		return new JsonResponse($data);
	}
}