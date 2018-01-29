<?php
/**
 * @author Martin Herich
 * Date: 5. 9. 2017
 */

namespace App\AdminModule\Presenters;


use App\Model\SubcategoryEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Driver\PDOException;
use Nette\Application\UI\Form;

class SubcategoriesPresenter extends BaseAdminPresenter{

	/**
	 * @var SubcategoryEntity[]|ArrayCollectionction
	 */
	private $subcategories;
	
	// categories for menu and filters are pulled in BaseAdminPresenter

	public function startup(){
		parent::startup();
		$this->template->presenter_name = 'Subcategories';
	}

	public function renderDefault($category){
		if(empty($category)){
			$this->subcategories = $this->subcFacade->getAllEntities();
			$this->template->selected_cat = 0; // all
		}else{
			$this->subcategories = $this->subcFacade->getSubcategoriesByCat($category);
			$this->template->selected_cat = $category;
		}
		$this->template->subcategories = $this->subcategories;
	}

	public function actionAdd(){
		$this->setView('edit');
		$this->template->title="Add new subcategory";
		$this->template->subcat = null;
	}

	public function actionEdit($id){
		/**
		 * @var SubcategoryEntity
		 */
		$subcat = $this->subcFacade->getEntity($id);
		$this->template->subcat = $subcat;
		$this->template->title = 'Edit subcategory';
		$this['subcategoryForm']->setDefaults(['parent_category' => $subcat->getParentCategory()->getId()]);
	}

	public function actionDelete($id, $confirmation){
		if(!is_numeric($id)){
			$this->redirect('Subcategories:default');
		}
		/**
		 * @var SubcategoryEntity
		 */
		$subcat = $this->subcFacade->getEntity($id);

		if($confirmation === "yes"){
			// delete uni
			$subcat_name = $subcat->getName();
			try{
				$this->categoryFacade->removeNow($subcat);
			}catch(PDOException $e){
				$this->flashMessage('Subcategory '.$subcat_name.' not deleted.<br>'.$e->getMessage(),'danger');
				$this->redirect('Subcategories:default');
			}
			$this->flashMessage('Subcategory '.$subcat_name.' deleted.','success');
			$this->redirect('Subcategories:default');
		}else{
			// show confirmation dialog
			$this->template->item = $subcat;
		}
	}

	protected function createComponentSubcategoryForm(){
		$form = new Form();
		$form->addHidden('id');
		$form->addText('name');
		$form->addText('color');
		$form->addText('icon_url');
		$form->addText('order')->addRule(Form::PATTERN, 'Number only', '.*[0-9].*')->setRequired(true);
		$form->addSelect('parent_category',null, $this->categoryFacade->getItems4Select())->setAttribute('class','form-control');
		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = [$this, 'subcategoryFormSubmit'];
		return $form;
	}

	public function subcategoryFormSubmit($form, $values){
		try{
			if(empty($values['id'])){
				// add category
				$subcat = new SubcategoryEntity();
			}else{
				// edit category
				$subcat = $this->subcFacade->getEntity($values['id']);
			}

			$subcat->setName($values['name']);
			$subcat->setColor($values['color']);
			$subcat->setIconUrl($values['icon_url']);
			$subcat->setPosition($values['order']);
			$subcat->setParentCategory($this->categoryFacade->getEntity($values['parent_category']));

			$this->subcFacade->saveNow($subcat);

			$this->presenter->flashMessage('Subcategory '.$subcat->getName().' saved successfully', 'success');
			$this->redirect('Subcategories:default');
		}catch(PDOException $e){
			$this->presenter->flashMessage($e->getMessage(),'danger');
			$form->addError($e->getMessage());
		}
	}
}