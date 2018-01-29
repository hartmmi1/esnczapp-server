<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 02-Aug-17
 * Time: 16:37
 */

namespace App\Model;

use Kdyby\Doctrine\EntityManager;

class CategoryFacade extends BaseFacade{

	public function __construct(EntityManager $em){
		parent::__construct($em, CategoryEntity::class);
	}

	/**
	 * @param CategoryEntity $categoryEntity
	 * @return array
	 */
	public function convertToArr($categoryEntity){
		$category = $categoryEntity;
		return [
			'id' => $category->getId(),
			'name' => $category->getName(),
			'icon' => $category->getIconUrl(),
			'color' => $category->getColor(),
			'order' => $category->getOrder()
		];
	}

}