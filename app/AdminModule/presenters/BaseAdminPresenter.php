<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 30-Aug-17
 * Time: 22:38
 */

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\CityForm;
use App\AdminModule\Components\ICityFormFactory;
use App\AdminModule\Components\DeleteItemControl;
use App\AdminModule\Components\IDeleteItemControlFactory;
use App\AdminModule\Components\IPointFormFactory;
use App\AdminModule\Components\IUniversityFormFactory;
use App\AdminModule\Components\UniversityForm;
use App\Model\UserEntity;
use App\Presenters\BasePresenter;
use Tracy\Debugger;
use WebChemistry\Images\IImageStorage;
use WebChemistry\Images\TPresenter;

class  BaseAdminPresenter extends BasePresenter {
	
	/**
	 * @var ICityFormFactory @inject
	 */
	public $cityFormFactory;

	/**
	 * @var IUniversityFormFactory @inject
	 */
	public $universityFormFactory;

	/**
	 * @var IPointFormFactory @inject
	 */
	public $pointFormFactory;

	/** @var  IImageStorage @inject */
	public $storage;


	/**
	 * @var IDeleteItemControlFactory @inject
	 */
	public $deleteItemControlFactory;

	protected $userUniversityId;

	public function getUserUniId(){
		return $this->userUniversityId;
	}
	
	public function startup(){
		parent::startup();

		if(!$this->user->isLoggedIn()){
			$this->redirect(':Front:Landing:default');
		}
		
		// to generate submenu and filters... unnecessary load for DB?
		$this->template->categories = $this->categoryFacade->getAllEntities();
		
		// todo rework authenticator
		$this->template->user_name = $this->user->getIdentity()->getData()['name'];
		$this->template->user_title = UserEntity::ROLES[$this->user->getIdentity()->getRoles()[0]];
		$this->template->presenter_name = 'undefined presenter';
		$this->template->sectionName = $this->user->getIdentity()->getData()['section'];
		$this->userUniversityId = $this->userFacade->getEntity($this->user->getId())->getUniversity()->getId();
		$this->template->userUniversityId = $this->userUniversityId;

		//Debugger::enable(Debugger::DEVELOPMENT);
		//Debugger::$showBar = true;
	}
	
	/**
	 * @return CityForm
	 */
	protected function createComponentCityForm(){
		$form = $this->cityFormFactory->create();
		Debugger::barDump($form);
		return $form;
	}

	/**
	 * @return UniversityForm
	 */
	protected function createComponentUniversityForm(){
		$form = $this->universityFormFactory->create();
		return $form;
	}

	/**
	 * @return DeleteItemControl
	 */
	protected function createComponentDeleteItemControl(){
		$component = $this->deleteItemControlFactory->create();
		Debugger::barDump($component);

		return $component;
	}

	/**
	 * @return \App\AdminModule\Components\PointForm
	 */
	protected function createComponentPointForm(){
		return $this->pointFormFactory->create();
	}

}