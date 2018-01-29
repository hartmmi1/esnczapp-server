<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 02-Aug-17
 * Time: 17:03
 */

namespace App\Model;

use Doctrine\Common\Collections\Criteria;
use Kdyby\Doctrine\EntityManager;
use Tracy\Debugger;

class PointFacade extends BaseFacade{

	public function __construct(EntityManager $em){
		parent::__construct($em, PoiEntity::class);
	}

	/**
	 * @param $entity PoiEntity
	 * @return array
	 */
	public function convertToArr($entity){
		$poi = $entity;

		return [
			'id' => $poi->getId(),
			'name' => $poi->getName(),
			'full_address' => $poi->getFullAddress(),
			'latitude' => $poi->getLat(),
			'longitude' => $poi->getLon(),
			'website_link' => $poi->getWebsiteLink(),
			'preview_image' => $poi->getPreviewImage(),
			'subcategory_id' => $poi->getSubcategory()->getId(),
			'discount' => $poi->getDiscount(),
		];
	}

	/**
	 * @param int $uni_id
	 * @param int $city_id
	 * @param int $subc_id
	 * @return array
	 */
	public function getPointsByUniAndCityInSubc($uni, $city, $subc){
		// vytvorime kriteria aby sa pouzila klauzula OR
		$criteria = new Criteria();
		$criteria
			//->orWhere(Criteria::expr()->contains('city_id',$city_id))
			->where(Criteria::expr()->eq('university', $uni))
			->andWhere(Criteria::expr()->eq('subcategory', $subc))
			->orderBy(['position' => 'ASC'])
		;
		$collection = $this->dao->matching($criteria);
		$ret = [];
		foreach($collection->getValues() as $poi){
			/* @var $poi PoiEntity */
			$ret[] = $poi->getId();
		}
		return $ret;
	}

	/**
	 * @param SubcategoryEntity $subcategory
	 * @return array
	 */
	public function getPointsBySubcAndUni($subcategory, $uniArr, $id_only = true){
		if(!$subcategory){
			return false;
		}
		$criteria = new Criteria();
		$criteria
			->where(Criteria::expr()->eq('subcategory', $subcategory))
			->andWhere(Criteria::expr()->eq('visible', 1))
			->orderBy(['position' => 'ASC'])
		;
		if(!empty($uniArr)){
			$criteria->andWhere(Criteria::expr()->in('university', $uniArr));
		}
		$collection = $this->dao->matching($criteria);
		$ret = [];
		foreach($collection->getValues() as $poi){
			/* @var $poi PoiEntity */
			// todo prasarna
			if($id_only){
				$ret[] = $poi->getId();
			}else{
				$ret[] = $poi;
			}
			
		}
		return $ret;
	}

	public function getPointsByCriteria($criteria){
		return $this->dao->findBy($criteria);
	}
}