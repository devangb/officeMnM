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

class OrganisationController extends Controller {
	
	/**
	 * @Route("/organisation/add", name="organisation_add")
	 * 
	 */
	public function addAction() {
		$organisation = new Organisation();
		$organisation->setOrganisationName('Practo');
		$organisation->setOrganisationDomain('practo.com');
		
		$building1 = new Building();
		$building1->setBuildingName('Mars');
		$building1->setOrganisation($organisation);
		
		$room1 = new Room();
		$room1->setBuilding($building1);
		$room1->setRoomName('Moria');
		$room1->setRoomFloor(3);
		$room1->setCapacity(25);
		
		$em = $this->getDoctrine()->getManager();
		$em->persist($organisation);
		$em->persist($building1);
		$em->persist($room1);
		$em->flush();
		
		return new Response('<html><body>Organisation, building and room created!</body></html>');
	}
	
	/**
	 * @Route("/organisation/{name}/buildings", name="organisation_show_buildings")
	 * @Method("GET")
	 * 
	 * @param Organisation $organisation
	 */
	public function getBuildingsAction(Organisation $organisation) {
		$buildings = [];
		
		foreach ($organisation->getBuildings() as $building) {
			$buildings = [
					'id' => $building->getId(),
					'buildingName' => $building->getBuildingName(),
			];
		}
		
		$data = [
			'buildings' => $buildings
		];
		
		return new JsonResponse($data);
	}
	
	/**
	 * @Route("/organisation/{name}/rooms", name="organisation_show_rooms")
	 * 
	 * @param Organisation $organisation
	 */
	public function getRoomsActions(Organisation $organisation) {
		$rooms = [];
		
		foreach ($organisation->getBuildings() as $building) {
			foreach ($building->getRooms() as $room) {
				$rooms = [
						'id' => $room->getId(),
						'roomName' => $room->getRoomName(),
						'roomFloor' => $room->getRoomFloor(),
						'capacity' => $room->getCapacity()
				];
			}
		}
		
		$data = [
			'rooms' => $rooms
		];
		
		return new JsonResponse($data);
	}
}