<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
	
}