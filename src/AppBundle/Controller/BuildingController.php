<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Building;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BuildingController {
	
	/**
	 * @Route("/building/{name}/rooms", name="building_show_rooms")
	 * @param Building $building
	 */
	public function getRoomsAction(Building $building) {
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