<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
	
	/**
	 * @ORM\ManyToOne(targetEntity="Building", inversedBy="rooms")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @var building
	 */
	private $building;
	
	/**
	 * @ORM\OneToMany(targetEntity="Booking", mappedBy="room")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @var unknown
	 */
	private $room_bookings;
	
	/**
	 * @ORM\Column(type="boolean")
	 *
	 * @var boolean
	 */
	private $has_projector = FALSE;
	
	/**
	 * @ORM\Column(type="boolean")
	 * 
	 * @var boolean
	 */
	private $has_whiteboard = FALSE;
	
	
	
	
	
	public function __construct() {
		$this->room_bookings =  new ArrayCollection();
	}
	
	public function __toString() {
		return $this->getRoomName();
	}
	
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
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getBuilding() {
		return $this->building;
	}
	public function setBuilding(Building $building) {
		$this->building = $building;
		return $this;
	}
	
	/**
	 * 
	 * @return ArrayCollection|Booking[]
	 */
	public function getRoomBookings() {
		return $this->room_bookings;
	}
	
	/**
	 *
	 * @return the boolean
	 */
	public function getHasProjector() {
		return $this->has_projector;
	}
	
	/**
	 *
	 * @param
	 *        	$has_projector
	 */
	public function setHasProjector($has_projector) {
		$this->has_projector = $has_projector;
		return $this;
	}
	
	/**
	 *
	 * @return the boolean
	 */
	public function getHasWhiteboard() {
		return $this->has_whiteboard;
	}
	
	/**
	 *
	 * @param
	 *        	$has_whiteboard
	 */
	public function setHasWhiteboard($has_whiteboard) {
		$this->has_whiteboard = $has_whiteboard;
		return $this;
	}
	
	
	
	
	
}