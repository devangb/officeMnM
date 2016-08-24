<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="You are with us already!")
 */
class User implements UserInterface {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 * @ORM\Column(type="string", unique=true)
	 */
	private $email;
	
	/**
	 * Encoded password
	 * @ORM\Column(type="string")
	 */
	private $password;
	
	/**
	 * Non-persisted feild for creating encoded password
	 * @Assert\NotBlank(groups={"Registration"})
	 * 
	 * @var string
	 */
	private $plainPassword;
	
	
	private $username;
	
	/**
	 * Organisation object
	 * @ORM\ManyToOne(targetEntity="Organisation", inversedBy="organisation_users")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @var Organisation
	 */
	private $user_organisation;
	
	/**
	 * Primary building for user
	 * @ORM\ManyToOne(targetEntity="Building", inversedBy="building_users")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @var Building
	 */
	private $primary_building;
	
	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank
	 * 
	 * @var string
	 */
	private $name;
	
	/**
	 * @ORM\OneToMany(targetEntity="Booking", mappedBy="organiser")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user_bookings;
	
	public function __construct(){
		$this->user_bookings = new ArrayCollection();
	}
	
	public function __toString() {
		return $this->getName();
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getRoles() {
		return ['ROLE_USER'];
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function getSalt() {
		
	}
	
	public function eraseCredentials() {
		$this->plainPassword = null;
	}
	
	public function setUsername($username) {
		$this->username = $username;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function getPlainPassword() {
		return $this->plainPassword;
	}
	
	public function setPlainPassword($plainPassword) {
		$this->plainPassword = $plainPassword;
		//Not to save password if only plainpassword changes
		$this->password = null;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	public function getUserOrganisation() {
		return $this->user_organisation;
	}
	public function setUserOrganisation(Organisation $user_organisation) {
		$this->user_organisation = $user_organisation;
		return $this;
	}
	public function getPrimaryBuilding() {
		return $this->primary_building;
	}
	public function setPrimaryBuilding(Building $primary_building) {
		$this->primary_building = $primary_building;
		return $this;
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * 
	 * @return ArrayCollection|Booking[]
	 */
	public function getUserBookings() {
		return $this->user_bookings;
	}
	
	
}