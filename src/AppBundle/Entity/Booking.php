<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
	 * @var unknown
	 */
	private $room;
	
	/**
	 * 
	 * @var unknown
	 */
	private $organiser;
	
	/**
	 * @ORM\Column(type="datetime")
	 * 
	 * @var unknown
	 */
	private $start_time;
	private $end_time;
	private $extended_time;
}