<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface {
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;
	
	/**
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
	
}