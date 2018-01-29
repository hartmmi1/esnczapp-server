<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 02-Aug-17
 * Time: 19:41
 */

namespace App\Model;

use Kdyby\Doctrine\EntityManager;

class SubcFacade extends BaseFacade{
	
	/**
	 * @var CategoryFacade
	 */
	private $catFacade;
	
	public function __construct(EntityManager $em, CategoryFacade $categoryFacade){
		parent::__construct($em, SubcategoryEntity::class);
		$this->catFacade = $categoryFacade;
	}

	/**
	 * @param $entity SubcategoryEntity
	 * @return array
	 */
	public function convertToArr($entity){
		$subc = $entity;
		return [
			'id' => $subc->getId(),
			'name' => $subc->getName(),
			'color' => $subc->getColor(),
			'icon' => $subc->getIconUrl(),
			'order' => $subc->getOrder(),
			'parent_category' =>$subc->getParentCategory()->getId(), // todo je to nutne posielat na appku?
		];
	}

	public function getSubcategoriesByCat($category_id){
		return $this->dao->findBy(['parent_category' => $category_id],['position' => 'ASC']);
	}

	public function getSubcategories4SelectGroupedByCat(){
		$retArr = [];
		$subcats = $this->getAllEntities();
		foreach($subcats as $sc){
			$retArr[$sc->getParentCategory()->getName()][$sc->getId()] = $sc->getName();
		}
		return $retArr;
	}
}