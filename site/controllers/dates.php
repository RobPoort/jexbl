<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JexBookingControllerDates extends JController
{
	function display($cachable = false){		
		
		parent::display($cachable);
	}
	
	/**
	 * method om de stappen in het formulier te definiëren en de tmpl layout toe te wijzen
	 * @ return empty
	 */
	public function setStep(){
		$this->app = JFactory::getApplication();
		$step = $this->app->input->get('step');
		
		switch ($step){
			case 1:
				$this->app->input->set('layout', 'step_2');
				$this->checkForArr();
				$this->getPrices();
				break;
			case 2:
				$this->calcPrice = $this->calculatePrice();
				$this->app->input->set('layout', 'step_3');
				break;
			case 3:
				$this->app->input->set('layout', 'step_4');
				break;
			default:
				$this->app->input->set('layout', 'default');
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
	 * method om te bepalen of aankomst- of vertrekdatum binnen een arrangement vallen
	 * aan de hand van $locatieId, $startDate en $endDate
	 * @return	object
	 */	
	public function checkForArr(){
	
		$this->app = JFactory::getApplication();
	
		//eerst start en enddate uit form halen + $location_id
		$date = $this->app->input->get("jbl_form",null,null);
	
		$startDate = new DateTime($date['start_date']);
		$endDate = new DateTime($date['end_date']);
		$locationId = $this->app->input->get('location_id');
	
		$model = $this->getModel('dates');
		$arrangements = $model->getArrangements($locationId,$startDate,$endDate);
	//TODO: 'arrangement' in $arrangements heeft nog geen prijsberekening. Moet ook via calcArr: $arrangements['arrangement'] = calcArr();
		$this->app->setUserState("option_jbl_overlap", null);
		if($arrangements){
			$this->app->setUserState("option_jbl_overlap", $arrangements);
		}
	}
	
	/**
	 * method om de prijzen van de pre-subtotaal attribs te berekenen
	 * @param Object attribs
	 * @return void
	 */
	public function calcAttribs(){
		
		$this->app = JFactory::getApplication();
		$this->data = $this->app->input->get("jbl_form", null, null);
		$this->attribs = $this->app->getUserState("option_jbl.itemAttribs");
		
		//number_pp  en nights bepalen voor als een attrib is_pn en/of is_pp
		$number_pp = $this->data['number_pp'];
		//TODO var_dump
		
		$start = new DateTime($this->data['start_date']);
		$end = new DateTime($this->data['end_date']);
		$diff = $start->diff($end);
		$nachten = $diff->days;
		
		$data = $this->data;
		$attribs = $this->attribs;
		
		//eerst alle attribs in array zetten met id als $key
		$checks			= array();
		$numbers		= array();
		$not_percents	= array();
		
		if(isset($attribs['checked'])){
			foreach($attribs['checked'] as $item){
				$checks[$item->id] = $item;
			}
		}
		if(isset($attribs['number'])){
			foreach($attribs['number'] as $item){
				$numbers[$item->id] = $item;
			}
		}
		if(isset($attribs['special']['required']['not_percent'])){
			
			foreach($attribs['special']['required']['not_percent'] as $item){
				
				$nights = $nachten;
				$personen = $number_pp;
				$messageNumberPp = '';
				$messageNumberNights = '';
				
				if($item->is_pp_special == 0){
					$personen = 1;
				} else {
					$mess = '';
					if($number_pp < 2){
						$personen = 2;
						$mess = '(minimaal 2 personen)';
					} elseif($number_pp > 6) {
						$personen = 6;
						$mess = '(maximaal 6 personen)';
					}
					$messageNumberPp = ' x '.$personen.' personen '.$mess;
				}
				
				if($item->is_pn_special == 0){
					$nights = 1;
				} elseif($nights == 1){
					$messageNumberNights = ' x '.$nights.' nacht ';
				} else {
					$messageNumberNights = ' x '.$nights.' nachten ';
				}
				
				$not_percents[$item->id]['attribObject'] = $item;
				$not_percents[$item->id]['calculated'] = $item->special_price * $personen * $nights;
				$not_percents[$item->id]['message'] = '&euro;&nbsp;'.number_format($item->special_price, 2, ',' , '.').$messageNumberPp.$messageNumberNights;
			}
			
		}
		
		//nu de form data over attribs leggen
		$checkedAttribs = array();
		if(isset($data['checked'])){
			
			foreach($data['checked'] as $key=>$value){
				$personen = $number_pp;
				$messageNumberPp		= '';
				$messageNumberNights	= '';
				if($checks[$key]->is_pp == 0){
					$personen = 1;
				} else {
					$mess = '';
					if($number_pp < 2){
						$personen = 2;
						$mess = '(minimaal 2 personen)';
					} elseif($number_pp > 6) {
						$personen = 6;
						$mess = '(maximaal 6 personen)';
					}
					$messageNumberPp = ' x '.$personen.' personen '.$mess;
				}
				
				$nights = $nachten;
				if($checks[$key]->is_pn == 0){
					$nights = 1;
				} elseif($nights == 1){
					$messageNumberNights = ' x '.$nights.' nacht ';
				} else {
					$messageNumberNights = ' x '.$nights.' nachten ';
				}
				
				$checkedAttribs[$key]['attribObject'] = $checks[$key];
				$checkedAttribs[$key]['calculated'] = $checks[$key]->price * $personen * $nights;
				$checkedAttribs[$key]['message'] = '&euro;&nbsp;'.number_format($checks[$key]->price, 2, ',' , '.').$messageNumberPp.$messageNumberNights;
			}
			
		}
		if(isset($data['number'])){
				
			foreach($data['number'] as $key=>$value){
				
				$persons				= $number_pp;
				$messageNumberPp		= '';
				$messageNumberNights	= '';
				if($numbers[$key]->is_pp == 0){
					$persons = 1;
					
				} elseif($numbers[$key] == 1) {
					
					$mess = '';
					if($number_pp < 2){
						$persons = 2;
						$mess = '(minimaal 2 personen)';
					} elseif($number_pp > 6) {
						$persons = 6;
						$mess = 'maximaal 6 personen';
					}
					$messageNumberPp = ' x '.$persons.' personen '.$mess;
				}
				
				$nights = $nachten;
				if($numbers[$key]->is_pn == 0){
					$nights = 1;
				} elseif($nights == 1){
					$messageNumberNights = ' x '.$nights.' nacht ';
				} else {
					$messageNumberNights = ' x '.$nights.' nachten ';
				}
				
				if($value != ''){
					$checkedAttribs[$key]['attribObject'] = $numbers[$key];
					$checkedAttribs[$key]['calculated'] = $numbers[$key]->price * $persons * $nights * $value;
					$checkedAttribs[$key]['message'] = $value.' x &euro;&nbsp;'.number_format($numbers[$key]->price, 2, ', ', '.').$messageNumberPp.$messageNumberNights;
				}
			}
				
		}
		
		if($checkedAttribs){
			$attribsSubTotaal = 0;
			foreach($checkedAttribs as $item){
				$attribsSubTotaal += $item['calculated'];
			}
		}
		if(!empty($not_percents)){			
			//$checkedAttribs['not_percents'] = $not_percents;
			$this->app->setUserState("option_jbl.calcattribsSpecial", null);
			$this->app->setUserState("option_jbl.calcattribsSpecial", $not_percents);
			$not_percentSubTotaal = 0;
			foreach($not_percents as $item){
				$not_percentSubTotaal += $item['calculated'];
			}			
		}
		$this->app->setUserState("option_jbl.attribsSubTotaal", null);
		$this->app->setUserState("option_jbl.attribsSubTotaal", $attribsSubTotaal);
		
		$this->app->setUserState("option_jbl.notPercentsSubTotaal", null);
		$this->app->setUserState("option_jbl.notPercentsSubTotaal", $not_percentSubTotaal);
		
		return $checkedAttribs;
	}
	
	/**
	 * method om de prijsberekening te maken en in userState te zetten
	 * @return Void
	 */
	//TODO: de percentageprijzen in aparte functie?
	public function calculatePrice(){
		
		$app = JFactory::getApplication();
		
		$this->data = $app->input->get("jbl_form", null, null);
		
		$this->locationId = (int)$app->input->get("location_id");
		
		//indien $this->overlap == null, dan geen arrangement van toepassing, anders arrangement prijs ophalen in function calcArr(), want andere gegevens
		//TODO: moet opgevraagd worden vóór percentage berekend gaat worden, indien van toepassing
		$this->overlap = $app->getUserState("option_jbl_overlap");
		
		//de prijs berekenen van de opgetelde pricePeriods
		$this->getPrices();
		
		$this->prices;
		$pricePeriods = $this->prices->pricePeriods;
		
		//TODO moet extra validatie komen op number_pp in form, min 2/ max 6 personen. nu met onderstaande statements en message
		$number_pp = 2;
		$message = '';
		if($this->data['number_pp'] < 2){
			$message	= "Het minimum aantal personen is 2";
			$app->setUserState("option_jbl.calcPrice_message", $message);
		}
		
		if($this->data['number_pp'] > 2){
			$number_pp = $this->data['number_pp'];
		}
		if($number_pp > 6){
			$number_pp = 6;
			$message	= "Het maximum aantal personen is 6";
			$app->setUserState("option_jbl.calcPrice_message", $message);
		}
		
		$totalStayPrice		= 0;
		$stayPeriods	= array();
		if($pricePeriods){
			$i = 0;
			foreach($pricePeriods as $key=>$value){
				if($key == 0 && $value['priceObject']->is_pn_extra){
					$totalStayPrice += $value['priceObject']->min_price * $number_pp;
					$totalStayPrice += ($value['nachten'] - 1) * $value['priceObject']->extra * $number_pp;
					$stayPeriods[$i]['nachten']			= $value['nachten'];
					$stayPeriods[$i]['priceObject']		= $value['priceObject'];
					$stayPeriods[$i]['number_pp']		= $number_pp;
					$stayPeriods[$i]['stayPeriodPrice']	= ($value['priceObject']->min_price * $number_pp) + (($value['nachten'] - 1) * $value['priceObject']->extra * $number_pp);
					$stayPeriods[$i]['message']			= $message;
				} elseif($value['priceObject']->is_pn_extra){
					$totalStayPrice += $value['priceObject']->extra * $value['nachten'] * $number_pp;
					$stayPeriods[$i]['nachten']			= $value['nachten'];
					$stayPeriods[$i]['priceObject']		= $value['priceObject'];
					$stayPeriods[$i]['number_pp']		= $number_pp;
					$stayPeriods[$i]['stayPeriodPrice']	= $value['priceObject']->extra * $value['nachten'] * $number_pp;
					$stayPeriods[$i]['message']			= $message;
				} else {
					$totalStayPrice += $value['priceObject']->min_price * $value['nachten'] * $number_pp;
					$stayPeriods[$i]['nachten']			= $value['nachten'];
					$stayPeriods[$i]['priceObject']		= $value['priceObject'];
					$stayPeriods[$i]['number_pp']		= $number_pp;
					$stayPeriods[$i]['stayPeriodPrice']	= $value['priceObject']->min_price * $value['nachten'] * $number_pp;
					$stayPeriods[$i]['message']			= $message;
				}
				$i++;
			}
		}
		
		if($this->overlap){
			$this->arrPrice = $this->calcArr($this->locationId,$this->data,$this->overlap);			
		}
		
		$this->calculatePrice->locationId = $this->locationId;
		$this->calculatePrice->form_data = $this->data;
		
		if($this->arrPrice){
			$this->calculatePrice->arrPrice = $this->arrPrice;
		}
		
		//$app->setUserState("option_jbl.calcPrice", null);
		
		$app->setUserState("option_jbl.calcPrice", $totalStayPrice);
		$app->setUserState("option_jbl.stayperiods", $stayPeriods);
		if($this->overlap){
			$app->setUserState("option_jbl.arrPrice", $this->arrPrice);
		}
		$this->calcPrice	= $app->getUserState("option_jbl.calcPrice");
		
		
		$this->calcAttribs	= $this->calcAttribs();
		$this->app->setUserState("option_jbl.calcattribs", $this->calcAttribs);
		
		$this->calcAttribsSpecial = $this->calcAttribs;
		//TODO: wel of niet userstate eerst legen? Geldt ook voor $this->calcAttribs
		//$this->app->setUserState("option_jbl.calcattribsSpecial", null);
		//$this->app->setUserState("option_jbl.calcattribsSpecial", $this->calcAttribsSpecial['not_percents']);
		
		
		
		//nu totale subtotaal
		$subtotal = 0;
		if($this->overlap){
			$subtotal += $this->arr_price;
		}
		if($totalStayPrice){
			$subtotal += $totalStayPrice;
		}
		if($this->app->getUserState("option_jbl.attribsSubTotaal")){
			$subtotal += $this->app->getUserState("option_jbl.attribsSubTotaal");
		}
		if($this->app->getUserState("option_jbl.notPercentsSubTotaal")){
			$subtotal += $this->app->getUserState("option_jbl.notPercentsSubTotaal");
		}
		
		
		$this->app->setUserState("option_jbl.subtotal", null);
		$this->app->setUserState("option_jbl.subtotal", $subtotal);
		
		
		
	}
	/**
	 * method om arrangementskosten op te vragen, indien er overlap is($this->overlap != null)
	 * @param int $locationId
	 * @param Object $data
	 * @param Object $overlap
	 * 
	 * @return Object $this->arrPrice
	 */
	private function calcArr($locationId,$data,$overlap){
		
		
		//$arrPrice['start'] = $data['start_date'];
		//$arrPrice['end'] = $data['end_date'];
				
		$number_pp = (int)$data['number_pp'];
		
		$arr = $overlap['arrangement'];
		
		$arrPrice['calc'] = array();
		$arrPrice['calc']['price_message'] = array();
		
		if($arr->is_pp){
			if($arr->use_extra_pp){
				$arrPriceTotal = $arr->price + (($number_pp - 1) * $arr->extra_pp);
				$arrPrice['calc']['arr_price'] = $arrPriceTotal;
				$arrPrice['calc']['price_message'][] = 'Arrangement:&nbsp;'.$arr->name;		
				$arrPrice['calc']['price_message'][] = '1ste persoon &euro;&nbsp;'.number_format($arr->price, 2, ',', '.').',';				
				if($number_pp < 3){
					$arrPrice['calc']['price_message'][] = 'de volgende persoon &euro;&nbsp;'.number_format($arr->extra_pp, 2, ',', '.').'.';
				} else {
					$arrPrice['calc']['price_message'][] = 'de volgende '.($number_pp - 1).' personen &euro;&nbsp;'.number_format($arr->extra_pp, 2, ',', '.').' per persoon.';
				}
			} else{
				$arrPriceTotal = $arr->price + (($number_pp - 1) * $arr->price);
				$arrPrice['calc']['arr_price'] = $arrPriceTotal;
				$arrPrice['calc']['price_message'][] = 'Arrangement:&nbsp;'.$arr->name;
				$arrPrice['calc']['price_message'][] = '1ste persoon &euro;&nbsp;'.number_format($arr->price, 2, ',', '.').',';
				if($number_pp < 3){
					$arrPrice['calc']['price_message'][] = 'de volgende persoon &euro;&nbsp;'.number_format($arr->price, 2, ',', '.').'.';
				} else {
					$arrPrice['calc']['price_message'][] = 'de volgende '.($number_pp - 1).' personen &euro;&nbsp;'.number_format($arr->price, 2, ',', '.').' per persoon.';
				}
			}
		} else {
			$arrPrice['calc']['arr_price'] = $arr->price;
			$arrPrice['calc']['price_message'][] = 'Arrangement:&nbsp;'.$arr->name;
		}
		
		
		$this->arrPrice = $arrPrice;
		
		return $this->arrPrice;
	}
	
	public function getPrices(){
		
		$this->app = JFactory::getApplication();
		
		//eerst start en enddate uit form halen + $location_id en eventuele overlap
		$date = $this->app->input->get("jbl_form",null,null);
		$this->locationId = (int)$this->app->input->get("location_id");
		$this->overlap = $this->app->getUserState("option_jbl_overlap");
		
		$startDate = new DateTime($date['start_date']);
		$endDate = new DateTime($date['end_date']);
		$locationId = $this->locationId;
		
		$model = $this->getModel('dates');
		$this->prices = $model->getPrices($locationId,$startDate,$endDate,$this->overlap);
		
		$this->app->setUserState("option_jbl.prices", $this->prices);
	}
}