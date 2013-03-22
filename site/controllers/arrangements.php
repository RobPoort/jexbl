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
		//model ophalen
		$model = $this->getModel('arrangements');
		//eerst bepalen uit welke stap we komen: (hidden) field 'step'
		$step = $app->input->get('step');
		
		//nu layout bepalen en userState vullen
		switch($step){
			case 'datepicker':
				$app->input->set('layout', 'datepicker');
				break;
			case 0:				
				$app->input->set('layout', 'default');
				break;
			case 1:
				$arr_id = $app->getUserState("option_jbl.arr_price")->id;
				if(!$arr_id){
					$arr_id = JFactory::getApplication()->input->get('arrangementSelect');
				}				
				if($arr_id){
					$app->setUserState("option_jbl.arr_id", $arr_id);
					}
				$this->calculatePrice();				
				$app->input->set('layout', 'step_2');
				
				if(!$app->getUserState("option_jbl.arr_id")){
					$arr_id = $app->input->get('arrangementSelect');
				}
				// dit is 'm
				$app->setUserState("option_jbl.arr_id", $arr_id);
								
				break;
			case 2:
				if(!$app->input->get('noCalc')){
					$this->calculatePrice();
				}
				$app->input->set('layout','step_3');
				break;
			case 3:								
				//$this->calculatePrice();
				if($app->input->get("naw", null,null)){
					$form = $this->app->input->get("jbl_form",null,null);
					$naw = $form['naw'];
					
					$app->setUserState("option_jbl.naw", $naw);
					
					
				}
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
		
		
		$arr_id = $app->getUserState("option_jbl.arr_id");		
		
		$app->setUserState("option_jbl.arr_id", $arr_id);
		
		$state_check = $app->input->get('jbl_form',null,null);		
		if($state_check['state_check'] == 1){
			//nu eerst data uit form step_2 halen
			$data = array();		
			$data = $app->input->get('jbl_form',null,null);
			
			if($data['arr_id']){
			$data['arr_id'] = $arr_id;
			} else{
				$arr_id = $app->getUserState("option_jbl.arr_id");
			}
			
			if($data['special'] ){
				$app->setUserState("option_jbl.special", $data['special']);
			}
			if($data['checked']){
				$app->setUserState("option_jbl.checked", $data['checked']);
			}
			if($data['number']){
				$app->setUserState("option_jbl.number", $data['number']);
			}
			
			//model ophalen
			$model = $this->getModel('arrangements');
			
			//arr_price ophalen
			//TODO deze total_arr_price moet opnieuw ivm is_pp, use_extra_pp, extra_pp en min_pp. Moet ook message komen met berekening
			$this->arr_price = $model->getItem();
			$arrItem = $this->arr_price;
			
			if($arrItem->is_pp == 0){ //aantal personen maakt niets uit
				$this->arr_price->total_arr_price = $arrItem->price;
				$this->arr_price->message = 'testkip';
			} elseif($arrItem->is_pp == 1){ //nu alle mogelijkheden waarbij aantal personen wel van belang is
				$number_pp = $data['number_pp'];
				
				$app->setUserState("option_jbl.adaptedNumber_pp", null); // number_pp in UserState zetten
				$app->setUserState("option_jbl.adaptedNumber_pp", $number_pp);
				
				if($arrItem->use_extra_pp == 0){
					if($number_pp < $arrItem->min_pp){ //rekening houden met min_pp
						$number_pp = $arrItem->min_pp;
						$app->setUserState("option_jbl.adaptedNumber_pp", null); // aangepaste number_pp in UserState zetten
						$app->setUserState("option_jbl.adaptedNumber_pp", $number_pp);
						$this->arr_price->total_arr_price = $arrItem->price * $number_pp;
						$this->arr_price->message = $number_pp.' x &euro; '.number_format($arrItem->price, 2, ',', '.').'<br />(Het minimum aantal personen voor dit arrangement is '.$arrItem->min_pp.')';
					} else {
						$this->arr_price->total_arr_price = $arrItem->price * $number_pp;
						$this->arr_price->message = $number_pp.' x &euro; '.number_format($arrItem->price, 2, ',', '.');
					}
				} elseif($arrItem->use_extra_pp == 1){ //nu met use_extra_pp, tricky ivm min_pp. Price is dan voor min_pp, alle personen meer zijn extra_pp!
					if($number_pp < $arrItem->min_pp){ //number_pp moet weer worden aangepast aan min_pp
						$number_pp = $arrItem->min_pp;
						$app->setUserState("option_jbl.adaptedNumber_pp", null); // aangepaste number_pp in UserState zetten
						$app->setUserState("option_jbl.adaptedNumber_pp", $number_pp);
						$this->arr_price->total_arr_price = $arrItem->price; //price is nu voor min_pp personen!
						$this->arr_price->message = '(het minimum aantal personen is '.$arrItem->min_pp.')';
					} elseif($number_pp == $arrItem->min_pp){ //total_price is nu price
						$number_pp = $arrItem->min_pp;
						$app->setUserState("option_jbl.adaptedNumber_pp", null); // aangepaste number_pp in UserState zetten
						$app->setUserState("option_jbl.adaptedNumber_pp", $number_pp);
						$this->arr_price->total_arr_price = $arrItem->price; //price is nu voor min_pp personen!
						$this->arr_price->message = '('.$number_pp.' personen)';
					} elseif ($number_pp > $arrItem->min_pp) { //min_pp is price, alles boven min_pp is extra_pp
						$this->arr_price->total_arr_price = $arrItem->price; //eerste gedeelte: geldt voor min_pp
						$this->arr_price->total_arr_price += $arrItem->extra_pp * ($number_pp - $arrItem->min_pp);
						$this->arr_price->message = 'Eerste '.$arrItem->min_pp.' personen &euro; '.number_format($arrItem->price,2 , ',' ,'.').'<br />Daaropvolgende '.($number_pp - $arrItem->min_pp).' personen &euro; '.number_format($arrItem->extra_pp,2, ',', '.').' pp.';
					}
				}
			}
			
			
			
			
			//$this->arr_price->total_arr_price = $this->arr_price->price;
			//if (array_key_exists('number_pp', $data)) {
			//	$this->arr_price->number_pp = (int)$data['number_pp']; // TODO: moet extra conditie aan toegevoegd worden ivm number_pp wanneer in arr use_pp 0 is
			//	if($this->arr_price->is_pa == 0){
			//	$this->arr_price->total_arr_price = $this->arr_price->number_pp * $this->arr_price->price;
			//	}
			//}
			
			//prijzen met key 'number' ophalen, indien aanwezig
			if (array_key_exists('number', $data)) {
					$this->attrib_prices_number = $model->getSelectedAttribs($data, 1, null);				
			}
			//prijzen met key 'checked' ophalen, indien aanwezig
			if (array_key_exists('checked', $data)) {
				$this->attrib_prices_checked = $model->getSelectedAttribs($data, 2, null);
			} elseif($app->getUserState("option_jbl.attrib_prices_checked")){				
				$this->attrib_prices_checked = $app->getUserState("option_jbl.attrib_prices_checked");
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
	
			
			//de extras, comment en naw ophalen, indien aanwezig, en ook in userState zetten			
			if(array_key_exists('extras', $data)){				
				if(array_key_exists('checked', $data['extras'])){
					$this->attrib_extras_checked = $model->getSelectedAttribs($data,6, null);					
				}
				if(array_key_exists('number', $data['extras'])){
					$this->attrib_extras_number = $model->getSelectedAttribs($data,7,null);
				}
			}
			if(array_key_exists('naw', $data)){
				//$this->naw = $data['naw'];
			}
			if(array_key_exists('comment', $data)){
				$this->comment = htmlentities($data['comment']);
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
			//de extras in de userState zetten
			if($this->attrib_extras_checked){
				//$app->setUserState("option_jbl.attrib_extras_checked", $this->attrib_extras_checked);
			}
			if($this->attrib_extras_number){
				$app->setUserState("option_jbl.attrib_extras_number", $this->attrib_extras_number);
			}
			if($this->naw){
				//$app->setUserState("option_jbl.naw", $this->naw);
			}
			if($this->comment){
				$app->setUserState("option_jbl.comment", $this->comment);
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
				if(array_key_exists('special_required', $data['special'])){
					$this->required_percent = $model->getSelectedAttribs($data, 7, $total);
					if($this->required_percent['percent']){
						foreach($this->required_percent['percent'] as $percent){
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
		$app->setUserState('option_jbl', null);
		
		//laatste gegevens uit form halen en in de userState zetten
		$form = $app->input->get("jbl_form",null,null);
		$final = $app->input->get('final', null, null);
		$naw = $form['naw'];
		$comment = $form['comment'];
		
		//userState eerst legen, dan data uit step_3 er in
		$app->setUserState("option_process_jbl", null);
		$app->setUserState("option_process_jbl.final", $final);
		$app->setUserState("option_process_jbl.comment", $comment);
		$app->setUserState("option_process_jbl.naw", $naw);
		
		$mailfrom	= $app->getCfg('sitename');
		$this->display();
	}
}