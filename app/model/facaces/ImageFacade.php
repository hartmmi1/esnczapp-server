<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 02-Aug-17
 * Time: 17:39
 */

namespace App\Model;


use Kdyby\Doctrine\EntityManager;
use Tracy\Debugger;

class ImageFacade extends BaseFacade{

	public function __construct(EntityManager $em){
		parent::__construct($em, ImageEntity::class);
	}

	public function convertToArr($entity){
		$img = $entity;
		/* @var $img ImageEntity */
		return [
			'id' => $img->getId(),
			'image_url' => $img->getImageUrl(),
			'order' => $img->getOrder(),
		];
	}

	public function getImagesForPointAsArr($poi, $with_keys = true){
		$images = $this->dao->findBy(['poi' => $poi]);
		$imageArr = [];
		foreach($images as $img){
			/* @var $img ImageEntity */
			$imageArr[$img->getOrder()] = $this->convertToArr($img);
		}
		if($with_keys){
			return $imageArr;
		}else{
			return array_values($imageArr);
		}
	}

}