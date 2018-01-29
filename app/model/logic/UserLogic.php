<?php
/**
 * @author Martin Herich
 * Date: 7. 9. 2017
 */

namespace App\Logic;

use App\Model\UserFacade;
use Nette\Security as NS;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Tracy\Debugger;

class UserLogic implements NS\IAuthenticator{
	
	/**
	 * @var UserFacade
	 */
	private $userFacade;
	
	public function __construct(UserFacade $userFacade){
		$this->userFacade = $userFacade;
	}
	
	public function authenticate(array $credentials){
		list($login,$password) = $credentials;
		$res = $this->userFacade->getUserByLogin($login);
		if(!$res){
			throw new NS\AuthenticationException('User not found.');
		}
		
		if (!NS\Passwords::verify($password, $res->getPassword())) {
			throw new NS\AuthenticationException('Invalid password.');
		}
		
		return new NS\Identity($res->getId(), [$res->getRole()], ['name' => $res->getName(), 'section' => $res->getUniversity()->getName()]);
	}

}