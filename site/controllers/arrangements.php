<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.components.controller');

class JexBookingControllerArrangements extends JController
{
	function display($cachable = false){
		
		
		parent::display($cachable);
	}
	/*
	 * bepaal de volgende stap en zet de userState variabelen
	 * @return empty
	 */
	
	function setStep(){
		
		$app = JFactory::getApplication();
		
		//eerst bepalen uit welke stap we komen: (hidden) field 'step'
		$step = (int)$app->input->get('step');
		
		//nu layout bepalen en userState vullen
		switch($step){
			case 1:
				$app->input->set('layout', 'step_2');
				$arr_id = $app->input->get('arrangementSelect');
				$app->setUserState("option_jbl.arr_id", $arr_id);
				$app->setUserState("option_jbl.testStaatje", 'tesssssssssssst');
				//var_dump($arr_id);
				break;
			case 2:
				$app->input->set('layout','step_3');
				break;
		}
		//$app->input->set('layout','step_2');
		
		$this->display();
	}
}