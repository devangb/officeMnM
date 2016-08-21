<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


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
}