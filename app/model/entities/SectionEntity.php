<?php
///**
// * Created by PhpStorm.
// * User: herald
// * Date: 01-Aug-17
// * Time: 13:38
// */
//
//namespace App\Model;
//
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * Class CityEntity
// * @package App\Model
// *
// * @ORM\Entity
// * @ORM\Table(name="sections")
// */
//
//class SectionEntity{
//
//	/**
//	 * @var int
//	 *
//	 * @ORM\Id()
//	 * @ORM\Column(type="integer")
//	 * @ORM\GeneratedValue()
//	 */
//	protected $id;
//
//	/**
//	 * @var string
//	 *
//	 * @ORM\Column(type="string")
//	 */
//	protected $name;
//
//	/**
//	 * @var string
//	 *
//	 * @ORM\Column(type="string", length=5000, nullable=true)
//	 */
//	protected $description;
//
//	/**
//	 * @var int
//	 *
//	 * nullable pre specialne sekcie ako UNI5 a Brno United
//	 * @ORM\Column(type="integer", nullable=true)
//	 * @ORM\ManyToOne(targetEntity="UniversityEntity")
//	 */
//	protected $university_id;
//
//	/**
//	 * @var int
//	 *
//	 * nullable pre specialne sekcie ako Ostromouc
//	 * @ORM\Column(type="integer", nullable=true)
//	 * @ORM\ManyToOne(targetEntity="CityEntity", inversedBy="id")
//	 */
//	protected $city_id;
//
//	/**
//	 * @return int
//	 */
//	public function getId(){
//		return $this->id;
//	}
//
//	/**
//	 * @return string
//	 */
//	public function getName(){
//		return $this->name;
//	}
//
//	/**
//	 * @param string $name
//	 */
//	public function setName($name){
//		$this->name = $name;
//	}
//
//	/**
//	 * @return string
//	 */
//	public function getDescription(){
//		return $this->description;
//	}
//
//	/**
//	 * @param string $description
//	 */
//	public function setDescription($description){
//		$this->description = $description;
//	}
//
//	/**
//	 * @return int
//	 */
//	public function getUniversityId(){
//		return $this->university_id;
//	}
//
//	/**
//	 * @param int $university_id
//	 */
//	public function setUniversityId($university_id){
//		$this->university_id = $university_id;
//	}
//
//	/**
//	 * @return int
//	 */
//	public function getCityId(){
//		return $this->city_id;
//	}
//
//	/**
//	 * @param int $city_id
//	 */
//	public function setCityId($city_id){
//		$this->city_id = $city_id;
//	}
//
//
//}