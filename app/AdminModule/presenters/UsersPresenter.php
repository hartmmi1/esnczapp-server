<?php
/**
 * @author Martin Herich
 * Date: 7. 9. 2017
 */

namespace App\AdminModule\Presenters;


use App\Model\UserEntity;
use Doctrine\DBAL\Driver\PDOException;
use Nette\Application\UI\Form;
use Symfony\Component\Console\Output\Output;
use Tracy\Debugger;
use Tracy\OutputDebugger;

class UsersPresenter extends BaseAdminPresenter{
	
	public function renderDefault(){
		$this->template->userEntities = $this->userFacade->getAllEntities();
	}
	
	public function actionEdit($id){

		$this->template->uEntity = $this->userFacade->getEntity($id);
		$this->template->title = 'Edit user '.$this->template->uEntity->getName();

		$this['userForm']->setDefaults([
			'id' => $this->template->uEntity->getId(),
			'name' => $this->template->uEntity->getName(),
			'login' => $this->template->uEntity->getLogin(),
			'role' => $this->template->uEntity->getRole(),
			'university' => ($this->template->uEntity->getUniversity() ? $this->template->uEntity->getUniversity()->getId() : null) ,
		]);
	}
	
	public function actionAdd(){
		$this->template->title = 'Add user';
		$this->setView('edit');
	}

	public function actionDelete($id, $confirmation){
		$this->template->title = 'Delete user';
		if($id){
			$this->template->item = $this->userFacade->getEntity($id);
			if($confirmation === 'yes'){
				try{
					$this->userFacade->removeNow($this->template->item);
					$this->flashMessage('User '.$this->template->item->getName().' deleted','success');
					$this->redirect('Users:default');
				}catch(PDOException $e){
					$this->flashMessage($e->getMessage(),'danger');
					$this->redirect('Users:default');
				}
			}
		}else{
			$this->renderDefault();
		}
	}
	
	public function createComponentUserForm(){
		$form = new Form();

		$form->addHidden('id');
		$form->addText('name')->setAttribute('class','form-control');
		$form->addText('login')->setAttribute('class','form-control');
		$form->addText('psswd')->setAttribute('class','form-control');
		$form->addSelect('role',null, UserEntity::ROLES)->setAttribute('class','form-control');
		$form->addSelect('university', null, $this->uniFacade->getItems4Select())->setAttribute('class','form-control');

		$form->addSubmit('save','Save')->setAttribute('class','btn btn-success');

		$form->onSuccess[] = [$this,'processForm'];

		return $form;
	}

	public function processForm($form, $values){
		$id = $values['id'];
		if($id){
			$newUser = $this->userFacade->getEntity($id);
		}else{
			$newUser = new UserEntity();
		}
		$newUser->setName($values['name']);
		$newUser->setLogin($values['login']);
		if(!empty($values['psswd'])){
			$newUser->setPassword($values['psswd']);
		}
		$newUser->setRole($values['role']);
		$newUser->setUniversity($this->uniFacade->getEntity($values['university']));

		try{
			$this->userFacade->saveNow($newUser);
			$this->flashMessage('User '.$newUser->getName().' saved successfully','success');
			$this->redirect('Users:default');
		}catch(PDOException $e){
			$this->flashMessage($e->getMessage(), 'danger');
		}
	}
}