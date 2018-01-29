<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 25-Jul-17
 * Time: 16:28
 */

namespace App\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ContentBlockEntity
 * @package App\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="content_blocks")
 */
class ContentBlockEntity{

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
	 * @ORM\ManyToOne(targetEntity="\App\Model\PoiEntity", inversedBy="content", cascade={"remove"})
	 * @ORM\JoinColumn(onDelete="CASCADE")
	 */
	protected $poi;

	/**
	 * @var String Title of content block
	 *
	 * @ORM\Column(type="string")
	 */
	protected $content_title;

	/**
	 * @var String content (basic html)
	 *
	 * @ORM\Column(type="string", length=2000)
	 */
	protected $content_block;

	/**
	 * @var int Order of render in POI detail screen
	 *
	 * @ORM\Column(type="integer")
	 */
	protected $position;

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
	public function getContentTitle(){
		return $this->content_title;
	}

	/**
	 * @param String $content_title
	 */
	public function setContentTitle($content_title){
		$this->content_title = $content_title;
	}

	/**
	 * @return String
	 */
	public function getContentBlock(){
		return $this->content_block;
	}

	/**
	 * @param String $content_block
	 */
	public function setContentBlock($content_block){
		$this->content_block = $content_block;
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




}