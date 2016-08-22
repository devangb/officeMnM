<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * 
 * @author devang
 * @ORM\Entity
 * @ORM\Table(name="building")
 */
class Building {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string")
	 * @var building_name
	 */
	private $building_name;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Organisation", inversedBy="buildings")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @var Organisation
	 */
	private $organisation;
	
	/**
	 * @ORM\OneToMany(targetEntity="Room", mappedBy="building")
	 * 
	 * @var rooms
	 */
	private $rooms;
	
	/**
	 * @ORM\OneToMany(targetEntity="User", mappedBy="primary_building")
	 * 
	 * @var unknown
	 */
	private $building_users;
	
	public function __construct() {
		$this->rooms = new ArrayCollection();
		$this->building_users = new ArrayCollection();
	}
	
	public function __toString() {
		return $this->getBuildingName();
	}
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getBuildingName() {
		return $this->building_name;
	}
	public function setBuildingName($building_name) {
		$this->building_name = $building_name;
		return $this;
	}
	public function getOrganisation() {
		return $this->organisation;
	}
	public function setOrganisation(Organisation $organisation) {
		$this->organisation = $organisation;
		return $this;
	}
	
	/** 
	 * @return ArrayCollection|Room[]
	 */
	public function getRooms() {
		return $this->rooms;
	}
	
	/**
	 * 
	 * @return ArrayCollection|Building[]
	 */
	public function getBuildingUsers() {
		return $this->building_users;
	}
	
	
}