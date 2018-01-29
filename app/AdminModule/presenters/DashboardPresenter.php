<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 08-Aug-17
 * Time: 23:07
 */

namespace App\AdminModule\Presenters;


use App\Presenters\BasePresenter;
use Tracy\Debugger;
use Tracy\OutputDebugger;

class DashboardPresenter extends BaseAdminPresenter{

	public function startup(){
		parent::startup();
		$this->template->presenter_name = 'Dashboard';
	}

	public function actionTest(){

		Debugger::dump($this->pointFacade->getEntity(1));
		$this->setView('default');
	}
	
	public function renderDefault(){
	
	}
}