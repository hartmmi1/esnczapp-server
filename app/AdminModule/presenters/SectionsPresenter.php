<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 01-Sep-17
 * Time: 21:13
 */

namespace App\AdminModule\Presenters;


use App\Model\UniversityEntity;
use Doctrine\DBAL\Driver\PDOException;
use Nette\Application\UI\Form;

class SectionsPresenter extends BaseAdminPresenter{

	public function startup(){
		parent::startup();
		$this->template->presenter_name = 'Sections and universities';
	}

	public function renderDefault(){
		$this->template->unis =  $this->uniFacade->getAllEntities();
	}

	public function actionAdd(){
		$this->setView('edit');
		$this->template->title = 'Add new university/section';
		$this->template->uni = null;
	}

	public function actionEdit($id){
		/**
		 * @var UniversityEntity
		 */
		$uni = $this->uniFacade->getEntity($id);
		$this->template->uni = $uni;
		$this->template->title = 'Edit university of '.$uni->getName();


	}

	public function actionDelete($id, $confirmation){
		if(!is_numeric($id)){
			$this->redirect('Sections:default');
		}
		/**
		 * @var $city UniversityEntity
		 */
		$uni = $this->uniFacade->getEntity($id);
		if($confirmation === "yes"){
			// delete uni
			$uni_name = $uni->getName();
			try{
				$this->uniFacade->removeNow($uni);
			}catch(PDOException $e){
				$this->flashMessage('University '.$uni_name.' not deleted.<br>'.$e->getMessage(),'danger');
				$this->redirect('Sections:default');
			}
			$this->flashMessage('University '.$uni_name.' deleted.','success');
			$this->redirect('Sections:default');
		}else{
			// show confirmation dialog
			$this->template->item = $uni;
		}
	}

	public function actionDetail($id){
		$this->template->section = $this->uniFacade->getEntity($id);
	}
}