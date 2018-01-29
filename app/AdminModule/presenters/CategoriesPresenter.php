<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 03-Sep-17
 * Time: 22:51
 */

namespace App\AdminModule\Presenters;


use App\Model\CategoryEntity;
use App\Model\SubcategoryEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Driver\PDOException;
use Nette\Application\UI\Form;

class CategoriesPresenter extends BaseAdminPresenter{

	/**
	 * @var CategoryEntity[]|ArrayCollection
	 */
	private $categories;

	public function startup(){
		parent::startup();
		$this->template->presenter_name = 'Categories';
		$this->categories = $this->categoryFacade->getAllEntities();
	}

	public function renderDefault(){
		$this->template->categories = $this->categories;
	}

	public function actionAdd(){
		$this->setView('edit');
		$this->template->title="Add new category";
		$this->template->cat = null;
	}

	public function actionEdit($id){
		/**
		 * @var CategoryEntity
		 */
		$cat = $this->categoryFacade->getEntity($id);
		$this->template->cat = $cat;
		$this->template->title = 'Edit category '.$cat->getName();
	}

	public function actionDelete($id, $confirmation){
		if(!is_numeric($id)){
			$this->redirect('Categories:default');
		}
		/**
		 * @var CategoryEntity
		 */
		$cat = $this->categoryFacade->getEntity($id);

		if($confirmation === "yes"){
			// delete uni
			$cat_name = $cat->getName();
			try{
				$this->categoryFacade->removeNow($cat);
			}catch(PDOException $e){
				$this->flashMessage('Category '.$cat_name.' not deleted.<br>'.$e->getMessage(),'danger');
				$this->redirect('Categories:default');
			}
			$this->flashMessage('Category '.$cat_name.' deleted.','success');
			$this->redirect('Categories:default');
		}else{
			// show confirmation dialog
			$this->template->item = $cat;
		}
	}

	protected function createComponentCategoryForm(){
		$form = new Form();
		$form->addHidden('id');
		$form->addText('name');
		$form->addText('color');
		$form->addText('icon_url');
		$form->addText('order')->addRule(Form::PATTERN, 'Number only', '.*[0-9].*')->setRequired(true);
		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = [$this, 'categoryFormSubmit'];
		return $form;
	}

	public function categoryFormSubmit(Form $form, $values){
		try{
			if(empty($values['id'])){
				// add category
				$cat = new CategoryEntity();
			}else{
				// edit category
				$cat = $this->categoryFacade->getEntity($values['id']);
			}
			$cat->setName($values['name']);
			$cat->setColor($values['color']);
			$cat->setIconUrl($values['icon_url']);
			$cat->setOrder((int)$values['order']);

			// save imediately to DB
			$this->uniFacade->saveNow($cat);

			$this->presenter->flashMessage('Category '.$cat->getName().' saved successfully', 'success');
			$this->presenter->redirect('Categories:default');
		}catch(PDOException $e){
			$this->presenter->flashMessage($e->getMessage(),'danger');
			$form->addError($e->getMessage());
		}
	}

}