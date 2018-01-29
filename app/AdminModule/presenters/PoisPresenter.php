<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 01-Sep-17
 * Time: 18:24
 */

namespace App\AdminModule\Presenters;


use App\Model\PoiEntity;
use App\Model\PointFacade;
use App\Model\UserEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Nette\Application\UI\Form;
use Tracy\Debugger;

class PoisPresenter extends BaseAdminPresenter{

	public function startup(){
		parent::startup();
		$this->template->presenter_name = 'Points of interest';
	}

		public function actionDefault($filter_by_subcategory,$section_filter){

		$criteria = [];

		if($this->user->isInRole(UserEntity::ROLE_SECTION_ADMIN)){
			$section_filter = $this->userUniversityId;
		}
		if(!empty($section_filter)){
			$criteria['university'] = $section_filter;
			$this['formFilterPoints']->setDefaults(['uni' => $section_filter]);
		}


		if(empty($filter_by_subcategory)){
			$this->template->active_subcat = 0;
		}else{
			$this->template->subcats = $this->subcFacade->getSubcategoriesByCat($filter_by_subcategory);
			$this->template->active_subcat = $filter_by_subcategory;
			$criteria['subcategory'] = $filter_by_subcategory;
		}
		// get point after applying filters
		$this->template->points = $this->pointFacade->getPointsByCriteria($criteria);

		// get categories and subcategories for filters
		$this->template->categories = $this->categoryFacade->getAllEntities();
	}

	public function actionDetail($id){
		$this->template->point = $this->pointFacade->getEntity($id);
	}

	public function actionAdd(){

	}

	public function actionEdit($id){
		$this->template->point = $this->pointFacade->getEntity($id);
	}

	public function actionHide($id, $state){

		$point = $this->pointFacade->getEntity($id);
		$point->setVisible($state);
		$this->pointFacade->saveNow($point);

		$this->flashMessage('Point '.$point->getName().' ('.$point->getId().') is '.($state ? "shown" : "hidden"), 'success');
		$this->redirect('Pois:default');
		// todo add parameters of actual view
	}

	public function actionDelete($id, $confirmation){
		$this->template->title = 'Delete point';
		if($id){
			$this->template->item = $this->pointFacade->getEntity($id);

			// Only super admin and admin of same university can delete POI
			if( ($this->template->item->getUniversity()->getId() != $this->userUniversityId) && !($this->user->isInRole(2)) ){
				$this->flashMessage('Insufficient rights to delete point','warning');
				$this->redirect('Pois:default');
			}

			if($confirmation === 'yes'){
				try{
					$this->pointFacade->removeNow($this->template->item);
					$this->flashMessage('Point '.$this->template->item->getName().' deleted','success');
					$this->redirect('Pois:default');
				}catch(PDOException $e){
					$this->flashMessage($e->getMessage(),'danger');
					$this->redirect('Pois:default');
				}
			}
		}else{
			$this->setView('default');
		}
	}

	public function createComponentFormFilterPoints(){
		$filter = new Form();
		$filter->addSelect('uni',null, array_merge([0 => 'All universities'], $this->uniFacade->getItems4Select()));
		//$filter->addSelect('city', null, $this->cityFacade->getItems4Select());
		$filter->addSubmit('apply','Filter');

		$filter->onSuccess[] = [$this, 'applyFilter'];

		return $filter;
	}

	public function applyFilter($form, $values){
		$this->redirect('Pois:default', ['section_filter' => $values['uni']]);
	}
}