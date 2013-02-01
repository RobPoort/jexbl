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
			case 0:
				$app->input->set('layout', 'default');
				break;
			case 1:
				$app->input->set('layout', 'step_2');
				$arr_id = $app->input->get('arrangementSelect');
				$app->setUserState("option_jbl.arr_id", $arr_id);				
				break;
			case 2:
				$this->calculatePrice();
				$app->input->set('layout','step_3');
				break;
			case 3:
				$app->input->set('layout', 'step_4');
				break;
			default :
				$app->input->set('layout', 'default');
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
		
		//TODO er moet gechecked worden of er al een berekening ìs, voordat userState opnieuw gevuld wordt!!
		$app->setUserState("option_jbl", null);
		$app->setUserState("option_jbl.arr_id", $arr_id);
		
		$state_check = $app->input->get('jbl_form',null,null);
		if($state_check['state_check'] == 1){
			//nu eerst data uit form step_2 halen
			$data = array();		
			$data = $app->input->get('jbl_form',null,null);
			$data['arr_id'] = $arr_id;
			if($data['special']){
				$app->setUserState("option_jbl.special", $data['special']);
			}
			
			//model ophalen
			$model = $this->getModel('arrangements');
			
			//arr_price ophalen
			$this->arr_price = $model->getItem();
			$this->arr_price->total_arr_price = $this->arr_price->price;
			if (array_key_exists('number_pp', $data)) {
				$this->arr_price->number_pp = (int)$data['number_pp'];
				$this->arr_price->total_arr_price = $this->arr_price->number_pp * $this->arr_price->price;
			}
			
			//prijzen met key 'number' ophalen, indien aanwezig
			if (array_key_exists('number', $data)) {
					$this->attrib_prices_number = $model->getSelectedAttribs($data, 1, null);				
			}
			//prijzen met key 'checked' ophalen, indien aanwezig
			if (array_key_exists('checked', $data)) {
				$this->attrib_prices_checked = $model->getSelectedAttribs($data, 2, null);
			}
			
			//prijzen met key special required ophalen
			if(array_key_exists('special', $data)){
				if(array_key_exists('special_required', $data['special'])){
					$this->attrib_prices_special_required = $model->getSelectedAttribs($data,3, null);
				}
			}
			//prijzen met key special checked ophalen
			//TODO deze bewerking kan pas zodra er een totaalprijs is, indien gekozen voor is_percent
			if(array_key_exists('special', $data)){
				if(array_key_exists('special_checked', $data['special'])){
					$this->attrib_prices_special_checked = $model->getSelectedAttribs($data,4, null);
				}
			}
	
			
			
			//de berekeningen in de userState zetten
			$app->setUserState("option_jbl.arr_price", $this->arr_price);
			if($this->attrib_prices_number){
				$app->setUserState("option_jbl.attrib_prices_number", $this->attrib_prices_number);
			}
			if($this->attrib_prices_checked){
				$app->setUserState("option_jbl.attrib_prices_checked", $this->attrib_prices_checked);
			}
			if($this->attrib_prices_special_required){
				$app->setUserState("option_jbl.attrib_prices_special_required", $this->attrib_prices_special_required);
			}
			if($this->attrib_prices_special_checked){
				$app->setUserState("option_jbl.attrib_prices_special_checked", $this->attrib_prices_special_checked);
			}
			
			//totaalprijs berekenen
			$total = 0;
			$total += $this->arr_price->total_arr_price;
			if($this->attrib_prices_number){
				foreach($this->attrib_prices_number as $item){
					$total += $item->total_attrib_price;
				}
			}
			if($this->attrib_prices_checked){
				foreach($this->attrib_prices_checked as $item){
					$total += $item->total_attrib_price;
				}
			}
			if($this->attrib_prices_special_required){
				foreach($this->attrib_prices_special_required as $item){
					$total += $item->total_attrib_price;
				}
			}
			if($this->attrib_prices_special_checked){
				foreach($this->attrib_prices_special_checked as $item){
					$total += $item->total_attrib_price;
				}
			}
			
			//total_price in userState zetten
			$app->setUserState("option_jbl.total_price", $total);
			
			//nu percent berekeningen, indien nodig
			$total_percent = 0;
			$total_percent_price = 0;
			$percent_items = array();
			if(array_key_exists('special', $data)){
				if(array_key_exists('special_checked', $data['special'])){
					$this->checked_percent = $model->getSelectedAttribs($data, 5, $total);
					if($this->checked_percent['percent']){
						
						foreach($this->checked_percent['percent'] as $percent){
							$percent_items[] = $percent;
							$total_percent += $percent->total_attrib_price_percent;
							$total_percent_price += $percent->price;
							
						}					
					}				
				}
				$app->setUserState("option_jbl.percent_items", $percent_items);
				$app->setUserState("option_jbl.total_price_percent", $total_percent);
				$app->setUserState("option_jbl.total_price_percent_price", $total_percent_price);
				
			}
			if(count($percent_items) > 0){
				$this->total_price_def = $total + $total_percent + $total_percent_price;
				$app->setUserState("option_jbl.total_price_def", $this->total_price_def);
			} else {
				$app->setUserState("option_jbl.total_price_def", $total);
			}
		}
		
	}
	
	public function process(){
		$app = JFactory::getApplication();
		$app->input->set('layout', 'bedankt');
		$this->display();
	}
}