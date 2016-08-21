<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @author devang
 * @ORM\Entity
 * @ORM\Table(name="room")
 */
class Room {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 * 
	 * @var id
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string")
	 * 
	 * @var room_name
	 */
	private $room_name;
	
	/**
	 * @ORM\Column(type="integer")
	 * 
	 * @var room_floor
	 */
	private $room_floor;
	
	/**
	 * @ORM\Column(type="integer")
	 * 
	 * @var capacity
	 */
	private $capacity;
	
	
	public function getRoomName() {
		return $this->room_name;
	}
	public function setRoomName($room_name) {
		$this->room_name = $room_name;
		return $this;
	}
	public function getRoomFloor() {
		return $this->room_floor;
	}
	public function setRoomFloor($room_floor) {
		$this->room_floor = $room_floor;
		return $this;
	}
	public function getCapacity() {
		return $this->capacity;
	}
	public function setCapacity($capacity) {
		$this->capacity = $capacity;
		return $this;
	}
	
}