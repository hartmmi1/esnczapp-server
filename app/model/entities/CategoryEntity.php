<?php
/**
 * @author Martin Herich
 * Date: 24. 7. 2017
 */

namespace App\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class CityEntity
 * @package App\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class CategoryEntity{

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
	 * @ORM\Column(type="string")
	 */
	protected $color;

	/**
	 * @var
	 *
	 * @ORM\Column(type="string",length=2000)
	 */
	protected $icon_url;

	/**
	 * @var
	 *
	 * @ORM\Column(type="integer")
	 */
	protected $position;
	
	/**
	 * @var SubcategoryEntity[]|ArrayCollection
	 * @ORM\OneToMany(targetEntity="SubcategoryEntity", mappedBy="parent_category")
	 */
	protected $subcategories;

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
	public function getColor(){
		return $this->color;
	}

	/**
	 * @param mixed $color
	 */
	public function setColor($color){
		$this->color = $color;
	}

	/**
	 * @return mixed
	 */
	public function getIconUrl(){
		return $this->icon_url;
	}

	/**
	 * @param mixed $icon_url
	 */
	public function setIconUrl($icon_url){
		$this->icon_url = $icon_url;
	}

	/**
	 * @return mixed
	 */
	public function getPosition(){
		return $this->position;
	}

	/**
	 * @param mixed $position
	 */
	public function setPosition($position){
		$this->position = (int)$position;
	}

	// backward compatibility

	/**
	 * @deprecated
	 * @return mixed
	 */
	public function getOrder(){
		return $this->getPosition();
	}

	/**
	 * @deprecated
	 * @param $position
	 */
	public function setOrder($position){
		$this->setPosition($position);
	}
	
	/**
	 * @return SubcategoryEntity[]|ArrayCollection
	 */
	public function getSubcategories(){
		return $this->subcategories;
	}



}