<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 31-Jul-17
 * Time: 17:22
 */

namespace App\Model;

use Kdyby\Doctrine\EntityManager;


abstract class BaseFacade{

	/**
	 * @var EntityManager
	 * Injected from BasePresenter
	 */
	protected $em;

	/**
	 * @var object Doctrine repository (Entity class) (data access object)
	 */
	protected $dao;

	protected $colors_pallete = [
		'dark blue' => '#2e3192',
		'light blue' => '#00aeef',
		'green' => '#7ac143',
		'orange' => '#f47b20',
		'red' => '#ff0000',
		'magenta' => '#ec008c',
		'vodafone red' => '#E60000'
	];

	/**
	 * BaseFacade constructor.
	 * @param EntityManager $em
	 * @param string $entity_class Ziskane <EntityName>::class (doctrine entity, nededi od BaseEntity, je to deprecated)
	 */
	public function __construct(EntityManager $em, $entity_class){
		$this->em = $em;
		$this->dao = $this->em->getRepository($entity_class);
	}

	/**
	 * @return array
	 */
	public function getColorsPallete(){
		return $this->colors_pallete;
	}

	/**
	 * Return entity from DB by primary key
	 * @param  int $id of entity
	 * @return null|object
	 */
	public function getEntity($id){
		return $this->dao->find($id);
	}

	/**
	 * Return all entities from DB
	 * @return null|array of Entities
	 */
	public function getAllEntities(){
		return $this->dao->findAll();
	}

	/**
	 * Saves entity to DB immediately
	 * @param $entity
	 */
	public function saveNow($entity){
		$this->em->persist($entity);
		$this->em->flush();
	}

	public function saveLater($entity){
		$this->em->persist($entity);
	}

	/**
	 * Deletes entity from DB immediately
	 * @param $entity
	 */
	public function removeNow($entity){
		$this->em->remove($entity);
		$this->em->flush();
	}

	public function getItems4Select(){
		$items = $this->getAllEntities();
		$retArr = [];
		foreach($items as $item){
			$retArr[$item->getId()] = $item->getName();
		}
		return $retArr;
	}

	abstract public function convertToArr($entity);

}