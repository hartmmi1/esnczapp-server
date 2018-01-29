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
 * @ORM\Table(name="subcategories")
 */
class SubcategoryEntity{

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
	 * @ORM\Column(type="string")
	 */
	protected $color;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string", length=2000)
	 */
	protected $icon_url;

	/**
	 * @var int
	 *
	 * @ORM\Column(type="integer")
	 */
	protected $position;

	/**
	 * @var CategoryEntity
	 *
	 * @ORM\ManyToOne(targetEntity="CategoryEntity", inversedBy="subcategories")
	 */
	protected $parent_category;

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
	 * @return CategoryEntity
	 */
	public function getParentCategory(){
		return $this->parent_category;
	}

	/**
	 * @param CategoryEntity $categoryEntity
	 */
	public function setParentCategory($categoryEntity){
		$this->parent_category = $categoryEntity;
	}

	// backward compatibility
	/**
	 * @deprecated
	 * @return int
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
}