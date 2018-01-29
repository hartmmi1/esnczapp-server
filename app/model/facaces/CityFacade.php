<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 31-Jul-17
 * Time: 16:36
 */

namespace App\Model;

use Kdyby\Doctrine\EntityManager;

class CityFacade extends BaseFacade{

	public function __construct(EntityManager $em){
		parent::__construct($em, CityEntity::class);
	}

	/**
	 * @param CityEntity $cityEntity
	 * @return array
	 */
	public function convertToArr($cityEntity){
		$city = $cityEntity;
		return [
			'id' => $city->getId(),
			'name' => $city->getName(),
			'state' => 'Czech Republic', // todo upravit stat aby bol dynamicky alebo odstranit
			'longitude' => $city->getLon(),
			'latitude' => $city->getLat(),
			'city_desc' => $city->getDescription(),
		];
	}

}