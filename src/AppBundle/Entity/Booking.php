<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="booking")
 */
class Booking {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 * 
	 * @var id
	 */
	private $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Room", inversedBy="room_bookings")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Room
	 */
	private $room;
	
	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="user_bookings")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @var User
	 */
	private $organiser;
	
	/**
	 * @ORM\Column(type="datetime")
	 * 
	 * @var \DateTime
	 */
	private $start_time;
	
	/**
	 * @ORM\Column(type="datetime")
	 * 
	 * @var \DateTime
	 */
	private $end_time;
	
	/**
	 * @ORM\Column(type="datetime")
	 * 
	 * @var \DateTime
	 */
	private $extended_time;
	
	/**
	 * @ORM\Column(type="text")
	 * @var string
	 */
	private $purpose;
	
	/**
	 * @ORM\Column(type="integer")
	 * 
	 * @var interger
	 */
	private $capacity;
	
	/**
	 *
	 * @return the id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 *
	 * @param id $id        	
	 */
	public function setId( $id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 *
	 * @return the Room
	 */
	public function getRoom() {
		return $this->room;
	}
	
	/**
	 *
	 * @param Room $room        	
	 */
	public function setRoom(Room $room) {
		$this->room = $room;
		return $this;
	}
	
	/**
	 *
	 * @return the User
	 */
	public function getOrganiser() {
		return $this->organiser;
	}
	
	/**
	 *
	 * @param User $organiser        	
	 */
	public function setOrganiser(User $organiser) {
		$this->organiser = $organiser;
		return $this;
	}
	
	/**
	 *
	 * @return the DateTime
	 */
	public function getStartTime() {
		return $this->start_time;
	}
	
	/**
	 *
	 * @param \DateTime $start_time        	
	 */
	public function setStartTime(\DateTime $start_time) {
		$this->start_time = $start_time;
		return $this;
	}
	
	/**
	 *
	 * @return the DateTime
	 */
	public function getEndTime() {
		return $this->end_time;
	}
	
	/**
	 *
	 * @param \DateTime $end_time        	
	 */
	public function setEndTime(\DateTime $end_time) {
		$this->end_time = $end_time;
		return $this;
	}
	
	/**
	 * @return the DateTime
	 */
	public function getExtendedTime() {
		return $this->extended_time;
	}
	
	/**
	 * @param \DateTime $extended_time        	
	 */
	public function setExtendedTime(\DateTime $extended_time) {
		$this->extended_time = $extended_time;
		return $this;
	}
	
	/**
	 * @return the string
	 */
	public function getPurpose() {
		return $this->purpose;
	}
	
	/**
	 * @param $purpose
	 */
	public function setPurpose($purpose) {
		$this->purpose = $purpose;
		return $this;
	}
	
	/**
	 * @return the interger
	 */
	public function getCapacity() {
		return $this->capacity;
	}
	
	/**
	 * @param $capacity
	 */
	public function setCapacity($capacity) {
		$this->capacity = $capacity;
		return $this;
	}
	
	
	
}