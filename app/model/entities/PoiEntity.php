<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 25-Jul-17
 * Time: 15:48
 */

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Poi
 * @package App\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="pois")
 */
class PoiEntity{

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
	protected $full_address;

	/**
	 * @var
	 *
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var
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
	protected $preview_image;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=2000, nullable=true)
	 */
	protected $website_link;

	/**
	 * @var SubcategoryEntity
	 * @ORM\ManyToOne(targetEntity="SubcategoryEntity")
	 */
	protected $subcategory;

	/**
	 * @var CityEntity
	 * @ORM\ManyToOne(targetEntity="CityEntity")
	 */
	protected $city;

	/**
	 * @var UniversityEntity
	 * @ORM\ManyToOne(targetEntity="UniversityEntity")
	 */
	protected $university;

	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer", options={"default":0})
	 */
	protected $position;

	/**
	 * @var int Discount for ESNcard or just for erasmus students. Use with commercial partners only
	 *
	 * @ORM\Column(type="integer", options={"default":0})
	 */
	protected $discount;

	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer", options={"default":0})
	 */
	protected $visible;
	
	/**
	 * @var ImageEntity[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="ImageEntity", mappedBy="poi")
	 */
	protected $images;
	
	/**
	 * @var ContentBlockEntity[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="ContentBlockEntity", mappedBy="poi")
	 */
	protected $content;

	public function __construct(){
		$this->content = new ArrayCollection();
		$this->images = new ArrayCollection();

	}

	/**
	 * @return mixed
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getFullAddress(){
		return $this->full_address;
	}

	/**
	 * @param mixed $full_address
	 */
	public function setFullAddress($full_address){
		$this->full_address = $full_address;
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
	public function getLat(){
		return $this->lat;
	}

	/**
	 * @param mixed $lat
	 */
	public function setLat($lat){
		$this->lat = $lat;
	}

	/**
	 * @return float
	 */
	public function getLon(){
		return $this->lon;
	}

	/**
	 * @param float $lon
	 */
	public function setLon($lon){
		$this->lon = $lon;
	}

	/**
	 * @return string
	 */
	public function getPreviewImage(){
		return $this->preview_image;
	}

	/**
	 * @param string $preview_image
	 */
	public function setPreviewImage($preview_image){
		$this->preview_image = $preview_image;
	}

	/**
	 * @return SubcategoryEntity
	 */
	public function getSubcategory(){
		return $this->subcategory;
	}

	/**
	 * @param SubcategoryEntity $subcategory
	 */
	public function setSubcategory($subcategory){
		$this->subcategory = $subcategory;
	}

	/**
	 * @return CityEntity
	 */
	public function getCity(){
		return $this->city;
	}

	/**
	 * @param CityEntity $city
	 */
	public function setCity($city){
		$this->city = $city;
	}

	/**
	 * @return UniversityEntity
	 */
	public function getUniversity(){
		return $this->university;
	}

	/**
	 * @param UniversityEntity $university
	 */
	public function setUniversity($university){
		$this->university = $university;
	}

	/**
	 * @deprecated
	 * @return int
	 */
	public function getOrder(){
		return $this->getPosition();
	}

	/**
	 * @deprecated
	 * @param int $position
	 */
	public function setOrder($position){
		$this->setPosition($position);
	}

	/**
	 * @return string
	 */
	public function getWebsiteLink(){
		return $this->website_link;
	}

	/**
	 * @param string $website_link
	 */
	public function setWebsiteLink($website_link){
		$this->website_link = $website_link;
	}

	/**
	 * @return int
	 */
	public function getDiscount(){
		return $this->discount;
	}

	/**
	 * @param int $discount
	 */
	public function setDiscount($discount){
		$this->discount = $discount;
	}

	/**
	 * @return int
	 */
	public function getPosition(){
		return $this->position;
	}

	/**
	 * @param int $position
	 */
	public function setPosition($position){
		$this->position = (int)$position;
	}

	/**
	 * @return int
	 */
	public function isVisible(){
		return $this->visible;
	}

	/**
	 * @param int $visible
	 */
	public function setVisible($visible){
		$this->visible = $visible;
	}

	/**
	 * @return bool
	 */
	public function getReversedVisibility(){
		return !$this->isVisible();
	}
	
	/**
	 * @return ImageEntity[]|ArrayCollection
	 */
	public function getImages(){
		return $this->images;
	}
	
	/**
	 * @return ContentBlockEntity[]|ArrayCollection
	 */
	public function getContent(){
		return $this->content;
	}

	
}