<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 06-Sep-17
 * Time: 13:06
 */

namespace App\AdminModule\Components;

use App\Components\BaseControl;
use Tracy\Debugger;

class DeleteItemControl extends BaseControl{

	public function __construct(){
	    parent::__construct();
	}

	public function createComponentDeleteItemControl(){
		Debugger::barDump('eteteet');
		$delControl = new DeleteItemControl();
		return $delControl;
	}

	public function render($item){
		$template = $this->getTemplate();
		$template->setFile(__DIR__.DIRECTORY_SEPARATOR.'deleteItemControl.latte');
		$template->confirmation = 'yes';
		$template->item = $item;
	}

}

interface IDeleteItemControlFactory{

	/**
	 * @return DeleteItemControl
	 */
	public function create();
}