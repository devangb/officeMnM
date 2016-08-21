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
	 */
	private $id;
	
	/**
	 * @ORM\Column(type="string")
	 * 
	 * @var organisation_name
	 */
	private $organisation_name;
	
	/**
	 * @ORM\Column(type="string")
	 * 
	 * @var organisation_domain
	 */
	private $organisation_domain;
	
	/**
	 * @ORM\OneToMany(targetEntity="Building", mappedBy="organisation")
	 * 
	 * @var buildings
	 */
	private $buildings;
	
	public function __construct() {
		$this->buildings = new ArrayCollection();
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
	
	public function setOrganisationDomain($organisation_domain) {
		$this->organisation_domain = $organisation_domain;
		return $this;
	}
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * @return ArrayCollection|Building[]
	 */
	public function getBuildings() {
		return $this->buildings;
	}
	
	
}