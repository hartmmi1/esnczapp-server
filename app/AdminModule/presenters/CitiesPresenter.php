<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 01-Sep-17
 * Time: 18:28
 */

namespace App\AdminModule\Presenters;


use App\Model\CityEntity;
use Doctrine\DBAL\Driver\PDOException;
use Tracy\Debugger;

class CitiesPresenter extends BaseAdminPresenter{

	public function startup(){
		parent::startup();
		$this->template->presenter_name = 'Cities';
	}

	public function renderDefault(){
		$this->template->cities =  $this->cityFacade->getAllEntities();

	}

	public function actionAdd(){
		$this->setView('edit');
		$this->template->title = 'Add new city';
		$this->template->city = null;
	}
	
	public function actionEdit($id){
		/**
		 * @var CityEntity
		 */
		$city =  $this->cityFacade->getEntity($id);
		$this->template->city = $city;
		$this->template->title = 'Edit city of '.$city->getName();
	}

	public function actionDelete($id, $confirmation){
		if(!is_numeric($id)){
			$this->redirect('Cities:default');
		}
		/**
		 * @var $city CityEntity
		 */
		$city = $this->cityFacade->getEntity($id);
		if($confirmation === "yes"){
			// delete city
			$city_name = $city->getName();
			try{
				$this->cityFacade->removeNow($city);
			}catch(PDOException $e){
				$this->flashMessage('City '.$city_name.' not deleted.<br>'.$e->getMessage(),'danger');
				$this->redirect('Cities:default');
			}
			$this->flashMessage('City '.$city_name.' deleted.','success');
			$this->redirect('Cities:default');
		}else{
			$this->template->city = $city;
		}
	}

}