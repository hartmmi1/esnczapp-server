<?php
/**
 * @author Martin Herich
 * Date: 24. 7. 2017
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class CityEntity
 * @package App\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="universities")
 */
class UniversityEntity{

	/**
	 * @var
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @var
	 *
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var
	 *
	 * @ORM\Column(type="string", length=5000, nullable=true)
	 */
	protected $description;

	/**
	 * @var CityEntity
	 * @ORM\ManyToOne(targetEntity="\App\Model\CityEntity", inversedBy="universities")
	 */
	protected $parent_city;

	/**
	 * @return mixed
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name){
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription($description){
		$this->description = $description;
	}

	/**
	 * @return CityEntity
	 */
	public function getParentCity(){
		return $this->parent_city;
	}

	/**
	 * @param CityEntity $cityEntity
	 */
	public function setParentCity($cityEntity){
		$this->parent_city = $cityEntity;
	}



}