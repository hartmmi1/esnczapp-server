<?php
/**
 * @author Martin Herich
 * Date: 5. 9. 2017
 */

namespace App\AdminModule\Components;

use App\Components\BaseControl;
use App\Model\CityEntity;
use App\Model\CityFacade;
use App\Model\UniversityEntity;
use App\Model\UniversityFacade;
use App\Model\UserEntity;
use Couchbase\Exception;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManager;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class UniversityForm extends BaseControl{

	/**
	 * @var UniversityFacade
	 */
	private $uniFacade;

	/**
	 * @var CityFacade
	 */
	private $cityFacade;

	/**
	 * UniversityForm constructor.
	 * @param $uniFacade UniversityFacade
	 * @param $cityFacade CityFacade
	 */
	public function __construct(UniversityFacade $uniFacade, CityFacade $cityFacade){
		parent::__construct();

		$this->uniFacade = $uniFacade;
		$this->cityFacade = $cityFacade;
	}

	public function createComponentUniversityForm(){
		$form = new Form;
		$form->addHidden('id');
		$form->addText('name','Name');
		if($this->presenter->user->isInRole(UserEntity::ROLE_SUPERADMIN)){
			$form->addSelect('city', null, $this->cityFacade->getItems4Select())->setAttribute('class','form-control');
		}else{
			$form->addSelect('city', null, $this->cityFacade->getItems4Select())->setAttribute('class','form-control')->setDisabled(true);
		}

		$form->addSubmit('save', 'Save');
		$form->onSuccess[] = [$this, 'processForm'];
		return $form;
	}
	
	public function render($uni = null){
		
		$template = $this->getTemplate();
		$template->setFile(__DIR__.DIRECTORY_SEPARATOR.'uniForm.latte');
		$template->uni = $uni;
		if($uni){
			// selecting parent city (out of form latte)
			$this['universityForm']->setDefaults(['city' => $uni->getParentCity()->getId()]);

		}
		$template->render();
	}

	/**
	 * @param $form Form
	 */
	public function processForm($form){
		$values = $form->getValues();
		try{
			if(empty($values['id'])){
				// add uni
				$uni = new UniversityEntity();
			}else{
				// edit uni
				$uni = $this->uniFacade->getEntity($values['id']);
			}
			$uni->setName($values['name']);
			// disabled select is not send (disabled for section admin, they can change only uni name)
			if(isset($values['city'])){
				$uni->setParentCity($this->cityFacade->getEntity($values['city']));
			}
			$this->uniFacade->saveNow($uni);

			$this->presenter->flashMessage('University '.$uni->getName().' saved successfully', 'success');
			// todo redirect pre sekneho admina
			$this->presenter->redirect('Sections:default');
		}catch(PDOException $e){
			$this->presenter->flashMessage($e->getMessage(),'danger');
			$form->addError($e->getMessage());
		}



	}

	
}

interface IUniversityFormFactory{
	/**
	 * @return UniversityForm
	 */
	public function create();
}