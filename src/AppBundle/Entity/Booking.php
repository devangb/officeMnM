<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;

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
	 * @ORM\ManyToOne(targetEntity="Room")
	 * 
	 * @var Room
	 */
	private $room;
	
	/**
	 * @ORM\ManyToMany(targetEntity="User")
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
}