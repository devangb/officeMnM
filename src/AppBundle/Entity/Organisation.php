<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 * @author devang
 * @ORM\Entity
 * @ORM\Table(name="organisation")
 *
 */
class Organisation {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 * 
	 * @var 
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string")
	 * 
	 * @var string
	 */
	private $organisation_name;
	
	/**
	 * @ORM\Column(type="string")
	 * 
	 * @var string
	 */
	private $organisation_domain;
	
	/**
	 * @ORM\OneToMany(targetEntity="Building", mappedBy="organisation")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Building
	 */
	private $buildings;
	
	/**
	 * @ORM\OneToMany(targetEntity="User", mappedBy="user_organisation")
	 * @ORM\JoinColumn(nullable=false)
	 * @var Users
	 */
	private $organisation_users;
	
	
	public function __construct() {
		$this->buildings = new ArrayCollection();
		$this->organisation_users = new ArrayCollection();
	}
	
	public function __toString() {
		return $this->organisation_name;
	}
	
	public function getOrganisationName() {
		return $this->organisation_name;
	}
	
	public function setOrganisationName($organisation_name) {
		$this->organisation_name = $organisation_name;
		return $this;
	}
	
	public function getOrganisationDomain() {
		return $this->organisation_domain;
	}
	
	/**
	 * 
	 * @param string $organisation_domain
	 * @return \AppBundle\Entity\Organisation
	 */
	public function setOrganisationDomain($organisation_domain) {
		$this->organisation_domain = $organisation_domain;
		return $this;
	}
	
	/**
	 * Get Id
	 * 
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Set Id
	 * 
	 * @param int $id
	 * @return \AppBundle\Entity\Organisation
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Get Buildings
	 * 
	 * @return ArrayCollection|Building[]
	 */
	public function getBuildings() {
		return $this->buildings;
	}
	public function getOrganisationUsers() {
		return $this->organisation_users;
	}
	
	
	
}