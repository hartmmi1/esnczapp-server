<?php
/**
 * @author Martin Herich
 * Date: 7. 9. 2017
 */

namespace App\Model;


use Doctrine\Common\Collections\Criteria;
use Kdyby\Doctrine\EntityManager;

class UserFacade extends BaseFacade{
	
	public function __construct(EntityManager $em){
		parent::__construct($em, UserEntity::class);
	}
	
	public function convertToArr($entity){
		// TODO: Implement convertToArr() method.
	}
	
	public function getUserByLogin($login){
		return $this->dao->findOneBy(['login' => $login]);
	}
}