<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 25-Jul-17
 * Time: 16:56
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ImageEntity
 * @package App\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="images")
 */
class ImageEntity{

	/**
	 * @var int unique identifier
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @var PoiEntity
	 * @ORM\ManyToOne(targetEntity="\App\Model\PoiEntity", inversedBy="images", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $poi;

	/**
	 * @var String Title of content block
	 *
	 * @ORM\Column(type="string")
	 */
	protected $image_url;

	/**
	 * @var int Order of render in carousel
	 *
	 * @ORM\Column(type="integer")
	 */
	protected $position;

	/**
	 * @var int Id of resource for Webchemistry\images objects
	 */
	protected $resource_id;

	/**
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return PoiEntity
	 */
	public function getPoi(){
		return $this->poi;
	}

	/**
	 * @param PoiEntity $poi
	 */
	public function setPoi($poi){
		$this->poi = $poi;
	}

	/**
	 * @return String
	 */
	public function getImageUrl(){
		return $this->image_url;
	}

	/**
	 * @param String $image_url
	 */
	public function setImageUrl($image_url){
		$this->image_url = $image_url;
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
	 *
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
	public function getResourceId(){
		return $this->resource_id;
	}

	/**
	 * @param int $resource_id
	 */
	public function setResourceId($resource_id){
		$this->resource_id = $resource_id;
	}



}