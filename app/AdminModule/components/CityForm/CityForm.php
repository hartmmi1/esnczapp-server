<?php
/**
 * @author Martin Herich
 * Date: 5. 9. 2017
 */

namespace App\AdminModule\Components;

use App\Components\BaseControl;
use App\Model\CityEntity;
use App\Model\CityFacade;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class CityForm extends BaseControl{

	/**
	 * @var CityFacade
	 */
	private $cityFacade;

	/**
	 * CityForm constructor.
	 * @param $cityFacade CityFacade
	 */
	public function __construct(CityFacade $cityFacade){
		parent::__construct();

		$this->cityFacade = $cityFacade;
	}

	public function createComponentCityForm(){
		$form = new Form;
		$form->addHidden('id');
		$form->addText('name','Name');
		$form->addText('lon', 'Longitude');
		$form->addText('lat', 'Latitude');
		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = [$this, 'processForm'];
		Debugger::barDump($form);
		return $form;
	}
	
	public function render($city = null){
		
		$template = $this->getTemplate();
		$template->setFile(__DIR__.DIRECTORY_SEPARATOR.'cityForm.latte');
		$template->city = $city;
		$template->render();
	}

	/**
	 * @param $form Form
	 */
	public function processForm($form){
		$values = $form->getValues();

		try{
			if(empty($values['id'])){
				// add city
				$city = new CityEntity();
			}else{
				// edit city
				$city = $this->cityFacade->getEntity($values['id']);
			}
			$city->setName($values['name']);
			$city->setLon($values['lon']);
			$city->setLat($values['lat']);

			$this->cityFacade->saveNow($city);

			$this->presenter->flashMessage('City of '.$city->getName().' saved successfully', 'success');
			$this->presenter->redirect('Cities:default');
		}catch(PDOException $e){
			$form->addError($e->getMessage());
		}



	}

	
}

interface ICityFormFactory{
	/**
	 * @return CityForm
	 */
	public function create();
}