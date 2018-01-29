<?php
/**
 * @author Martin Herich
 * Date: 7. 9. 2017
 */

namespace App\Model;

use App\Logic\UserLogic;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Nette\Security as NS;

/**
 * Class UserEntity
 * @package App\Model
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class UserEntity{
	
	const ROLE_GUEST = 0;
	const ROLE_SECTION_ADMIN = 1;
	const ROLE_SUPERADMIN = 2;
	
	const ROLES = [
		self::ROLE_GUEST => 'guest',
		self::ROLE_SECTION_ADMIN => 'section admin',
		self::ROLE_SUPERADMIN => 'super admin'
	];
	
	/**
	 * @var UserLogic
	 */
	private $userLogic;
	
	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	protected $id;
	
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $login;
	
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $password;
	
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	/**
	 * @var UniversityEntity
	 * @ORM\ManyToOne(targetEntity="UniversityEntity")
	 */
	protected $university;
	
	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	protected $role;
	
	/**
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}
	
	/**
	 * @param int $id
	 */
	public function setId($id){
		$this->id = $id;
	}
	
	/**
	 * @return string
	 */
	public function getLogin(){
		return $this->login;
	}
	
	/**
	 * @param string $login
	 */
	public function setLogin($login){
		$this->login = $login;
	}
	
	/**
	 * @return string
	 */
	public function getPassword(){
		return $this->password;
	}
	
	/**
	 * @param string $password
	 */
	public function setPassword($password){
		$this->password = NS\Passwords::hash($password);
	}
	
	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}
	
	/**
	 * @param string $name
	 */
	public function setName($name){
		$this->name = $name;
	}
	
	/**
	 * @return UniversityEntity
	 */
	public function getUniversity(){
		return $this->university;
	}
	
	/**
	 * @param UniversityEntity
	 */
	public function setUniversity($university){
		$this->university = $university;
	}
	
	/**
	 * @return int
	 */
	public function getRole(){
		return $this->role;
	}
	
	/**
	 * @param int $role
	 */
	public function setRole($role){
		$this->role = $role;
	}
	
	
}