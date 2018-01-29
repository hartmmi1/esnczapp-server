<?php
/**
 * @author Martin Herich
 * Date: 12. 7. 2017
 */

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class CityEntity
 * @package App\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="cities")
 */
class CityEntity{

	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", nullable=true, length=5000)
	 */
	protected $description;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 */
	protected $lat;

	/**
	 * @var float
	 *
	 * @ORM\Column(type="float")
	 */
	protected $lon;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=2000, nullable=true)
	 */
	protected $city_image_url;

	/**
	 * @ORM\OneToMany(targetEntity="UniversityEntity", mappedBy="parent_city")
	 * @var UniversityEntity[] | ArrayCollection
	 */
	protected $universities;

	public function __construct(){
		$this->universities = new ArrayCollection();
	}

	/**
	 * @return integer $id
	 */
	public function getId(){
		return (int)$this->id;
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
	 * @return float
	 */
	public function getLat(){
		return (float)$this->lat;
	}

	/**
	 * @param float $lat
	 */
	public function setLat($lat){
		$this->lat = (float)$lat;
	}

	/**
	 * @return float
	 */
	public function getLon(){
		return (float)$this->lon;
	}

	/**
	 * @param float $lon
	 */
	public function setLon($lon){
		$this->lon = (float)$lon;
	}

	/**
	 * @return UniversityEntity[]|ArrayCollection
	 */
	public function getUniversities(){
		return $this->universities;
	}

	/**
	 * @return bool|int
	 */
	public function getUniversitiesCount(){
		if(is_array($this->universities)){
			return count($this->universities);
		}else if(is_object($this->universities)){
			return $this->universities->count();
		}else{
			// todo throw exception
			return false;
		}
	}


}