<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 02-Aug-17
 * Time: 17:41
 */

namespace App\Model;


use Kdyby\Doctrine\EntityManager;

class ContentFacade extends BaseFacade{

	public function __construct(EntityManager $em){
		parent::__construct($em, ContentBlockEntity::class);
	}

	/**
	 * @param $entity ContentBlockEntity
	 * @return array
	 */
	public function convertToArr($entity){
		$cb = $entity;
		return [
			'content_title' => $cb->getContentTitle(),
			'content_body' => $cb->getContentBlock(),
		];
	}

	public function getContentForPointAsArr($poi, $with_keys = true){
		$contentBlocks = $this->dao->findBy(['poi' => $poi]);
		$contentArr = [];
		foreach($contentBlocks as $cb){
			/* @var $cb ContentBlockEntity */
			$contentArr[$cb->getOrder()] = $this->convertToArr($cb);
		}
		if($with_keys){
			return $contentArr;
		}else{
			return array_values($contentArr);
		}
	}

}