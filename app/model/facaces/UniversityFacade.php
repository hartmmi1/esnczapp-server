<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 02-Aug-17
 * Time: 15:06
 */

namespace App\Model;

use Kdyby\Doctrine\EntityManager;

class UniversityFacade extends BaseFacade{

	public function __construct(EntityManager $em){
		parent::__construct($em, UniversityEntity::class);
	}

	public function getUnisForCity($city_id){
		return $this->dao->findBy(['parent_city'=>$city_id]);
	}

	/**
	 * @param UniversityEntity $uniEntity
	 * @return array
	 */
	public function convertToArr($uniEntity){
		$uni = $uniEntity;
		return [
			'id' => $uni->getId(),
			'name' => $uni->getName(),
			'section_name' => 'ESN',
			'section_desc' => 'Erasmus Student Network'
		];
	}
}