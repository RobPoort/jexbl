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
				break;
			case 2:
				$this->calculatePrice();
				$app->input->set('layout','step_3');
				break;
		}		
		
		$this->display();
	}
	
	/**
	 * method om het model op te halen voor de prijsberekening
	 * @return string
	 */
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
	/**
	 * method om prijs te berekenen en vul userState aan
	 * @return empty
	 */
	public function calculatePrice(){
		
		$app = JFactory::getApplication();
		
		//eerst userState opschonen, zodat bij voor 2e keer invullen formulier niet waarden uit de eerste keer bewaard blijven. arr_id moet er wel weer in
		$arr_id = $app->getUserState("option_jbl.arr_id");		
		$app->setUserState("option_jbl", null);
		$app->setUserState("option_jbl.arr_id", $arr_id);
		
		//nu eerst data uit form step_2 halen
		$data = array();		
		$data = $app->input->get('jbl_form',null,null);
		$data['arr_id'] = $arr_id;
		
		//model ophalen
		$model = $this->getModel('arrangements');
		
		//arr_price ophalen
		$this->arr_price = $model->getItem();
		$this->arr_price->total_arr_price = $this->arr_price->price;
		
		//prijzen met key 'number' ophalen, indien aanwezig
		if (array_key_exists('number', $data)) {
			if (array_key_exists('number_pp', $data['number'])) {
				$this->arr_price->number_pp = (int)$data['number']['number_pp'];
				$this->arr_price->total_arr_price = $this->arr_price->number_pp * $this->arr_price->price;
			}		
		}		
		
		//de berekeningen in de userState zetten
		$app->setUserState("option_jbl.arr_price", $this->arr_price);
	}
}