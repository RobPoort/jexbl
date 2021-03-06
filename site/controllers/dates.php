<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JexBookingControllerDates extends JController
{
	function display($cachable = false){		
		
		$this->setLocationId();
		
		parent::display($cachable);
	}
	
	/**
	 * method om de location_id in de userState te zetten, afhankelijk van de menu params. Ook attributes behorende bij locatie in userState zetten
	 * @return void
	 */
	public function setLocationId(){
		$app = JFactory::getApplication();
		
		$this->data = $app->input->get('jbl_form', null, null);
		$this->choose = $app->input->get('choose');
		$this->location_id = $app->input->get('location_id');
		$this->setAttributes();
		if($this->choose == 1){
			//location_id is gedurende hele traject, de location_id zoals aangegeven in menu params
			$app->setUserState("jbl_option.location_id", null);
			$app->setUserState("jbl_option.location_id", $this->location_id);
		} elseif($this->choose == 0){
			//location_id wordt bepaald door list op default pagina. userState alleen zetten indien in form location opgegeven wordt
			if(isset($this->data['location'])){
				$app->setUserState("jbl_option.location_id", null);
				$app->setUserState("jbl_option.location_id", $this->data['location']);
			}
		}
	}
	/**
	 * method om de attributes behorende bij een locatie op te halen en in userState te zetten
	 * @return void
	 */
	public function setAttributes(){
		
		$this->app = JFactory::getApplication();
		$locationId = $this->app->getUserState("jbl_option.location_id");
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('attribute_id');
		$query->from('#__jexbooking_xref_attributes');
		$query->where('location_id='.$locationId);
		$db->setQuery($query);
		$attributes = $db->loadObjectList();
		
		$this->app->setUserState("jbl_option.locationAttributes", null);
		$this->app->setUserState("jbl_option.locationAttributes", $attributes);
		
	}
	
	/**
	 * method om de stappen in het formulier te defini�ren en de tmpl layout toe te wijzen
	 * @ return empty
	 */
	public function setStep(){
		$this->app = JFactory::getApplication();
		$step = $this->app->input->get('step');
		
		switch ($step){
			case 1:
				$this->setLocationId();
				$this->app->input->set('layout', 'step_2');
				$this->checkForArr();
				$this->getPrices();
				break;
			case 2:
				$this->calcPrice = $this->calculatePrice();
				$this->app->input->set('layout', 'step_3');
				break;
			case 3:
				//laatste gegevens uit form halen en in de userState zetten
				$app = $this->app;
				$form = $app->input->get("jbl_form",null,null);
				$final = $app->input->get('final', null, null);
				$naw = $form['naw'];
				$comment = $form['comment'];
				
				//userState eerst legen, dan data uit step_3 er in
				$app->setUserState("option_process_jbl", null);
				$app->setUserState("option_process_jbl.final", $final);
				$app->setUserState("option_process_jbl.comment", $comment);
				$app->setUserState("option_process_jbl.naw", $naw);
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
		$data = $this->app->input->get("jbl_form",null,null);
	
		$startDate = new DateTime($data['start_date']);
		$endDate = new DateTime($data['end_date']);
		//$locationId = $this->app->input->get('location_id');
		$locationId = $this->app->getUserState("jbl_option.location_id");
	
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
		$not_required_not_percents	= array();
		$not_required_percents		= array();
		
		$not_percents_test = array();
		
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
		if(isset($attribs['special']['not_required']['not_percent'])){
			 foreach($attribs['special']['not_required']['not_percent'] as $item){
			 	$not_percents_test[$item->id] = $item;
			 }			 
		}
		
		//begin special->not_required->not_percent
		if(isset($attribs['special']['not_required']['not_percent'])){
				
			foreach($attribs['special']['not_required']['not_percent'] as $item){
		
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
		
				$not_required_not_percents[$item->id]['attribObject'] = $item;
				$not_required_not_percents[$item->id]['calculated'] = $item->special_price * $personen * $nights;
				$not_required_not_percents[$item->id]['message'] = '&euro;&nbsp;'.number_format($item->special_price, 2, ',' , '.').$messageNumberPp.$messageNumberNights;
			}
				
		}
		//eind special->not_required->not_percent
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
		//nu de attribs->special->not_required->percent ophalen
		if(isset($attribs['special']['not_required']['percent'])){
			$not_required_percents[] = $attribs['special']['not_required']['percent'];
			$this->app->setUserState("option_jbl.calcAttribsSpecialPercent", null);	
			$this->app->setUserState("option_jbl.calcAttribsSpecialPercent", $not_required_percents);
		}
		//einde
		
		//nu de form data over attribs leggen
		$checkedNotPercents = array();
		
		if(isset($data['special'])){
			
			if(isset($data['special']['not_required'])){
				
				if(isset($data['special']['not_required']['not_percent'])){
					
					foreach($data['special']['not_required']['not_percent'] as $key=>$value){
						
						$personen = $number_pp;
						$messageNumberPp		= '';
						$messageNumberNights	= '';
						if($not_percents_test[$key]->is_pp == 0){
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
						if($not_percents_test[$key]->is_pn == 0){
							$nights = 1;
						} elseif($nights == 1){
							$messageNumberNights = ' x '.$nights.' nacht ';
						} else {
							$messageNumberNights = ' x '.$nights.' nachten ';
						}
						
						$checkedNotPercents[$key]['attribObject'] = $checks[$key];
						$checkedNotPercents[$key]['calculated'] = $checks[$key]->special_price * $personen * $nights;
						$checkedNotPercents[$key]['message'] = '&euro;&nbsp;'.number_format($checks[$key]->special_price, 2, ',' , '.').$messageNumberPp.$messageNumberNights;
						
					}
					
				}
				
			}
		
		}
		
		
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
		
		//subtotalen berekenen
		$attribsSubTotaal = 0;
		$not_percentSubTotaal = 0;
		$not_required_not_percentsSubtotaal = 0;
		
		if($checkedAttribs){			
			foreach($checkedAttribs as $item){
				$attribsSubTotaal += $item['calculated'];
			}
		}
		if(!empty($checkedNotPercents)){
			foreach($checkedNotPercents as $item){
				$attribsSubTotaal += $item['calculated'];
			}
		}
		if(!empty($not_percents)){						
			foreach($not_percents as $item){
				$not_percentSubTotaal += $item['calculated'];
			}
					
		}		
		if(!empty($not_required_not_percents)){
			foreach ($not_required_not_percents as $item){
				$not_required_not_percentsSubtotaal += $item['calculated'];
			}
		}
		
		//userStates vullen
		
		//$calcattribsSpecial = array_merge($not_percents,$not_required_not_percents);
		$calcattribsSpecialRequired = $this->calcAttribsSpecialRequired();
		if(!empty($calcattribsSpecial)){
			$this->app->setUserState("option_jbl.attribsSpecialRequired", null);
			$this->app->setUserState("option_jbl.attribsSpecialRequired", $calcattribsSpecialRequired);
		}
		$calcAttribsSpecialSubTotaal = $not_percentSubTotaal + $not_required_not_percentsSubtotaal;
		
		$this->app->setUserState("option_jbl.attribsSubTotaal", null);
		$this->app->setUserState("option_jbl.attribsSubTotaal", $attribsSubTotaal);
		
		$this->app->setUserState("option_jbl.calcAttribsSpecialSubTotaal", null);
		$this->app->setUserState("option_jbl.calcAttribsSpecialSubTotaal", $calcAttribsSpecialSubTotaal);
		
		return $checkedAttribs;
	}
	
	/**
	 * method om de specialAttribs uit form te halen
	 * @return Object
	 */
	public function calcAttribsSpecialRequired(){
		
		//old school: voor elk special attrib een query
		$app = JFactory::getApplication();
		$all_data = $app->input->get("jbl_form", null, null);
		$data = $all_data['special'];
		$location_id = $app->getUserState("jbl_option.location_id");
		
		//nu eerst de required op halen
				
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('attribute_id');
		$query->from('#__jexbooking_xref_attributes');		
		$query->where("location_id=".$location_id);		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$results = array();
		foreach($rows as $row){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('name,use_percent,use_special_price, is_required, published, is_discount');
			$query->from("#__jexbooking_attributes");
			$query->where('id='.$row->attribute_id.' AND published=1 AND is_special=1 AND is_required=1');
			$db->setQuery($query);
			$result = $db->loadObject();
			if($result){
				$results[$row->attribute_id] = $result;
			}
		}
		return $results;
		
	}
	
	/**
	 * method om de prijsberekening te maken en in userState te zetten
	 * @return Void
	 */
	//TODO: de percentageprijzen in aparte functie?
	public function calculatePrice(){
		
		$app = JFactory::getApplication();
		
		$this->data = $app->input->get("jbl_form", null, null);
		
		//$this->locationId = (int)$app->input->get("location_id");
		$this->locationId = $app->getUserState("jbl_option.location_id");
		
		//indien $this->overlap == null, dan geen arrangement van toepassing, anders arrangement prijs ophalen in function calcArr(), want andere gegevens
		//TODO: moet opgevraagd worden v��r percentage berekend gaat worden, indien van toepassing
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
				if($key == 0 && $value['priceObject']->is_pn_extra == 1){
					$totalStayPrice += $value['priceObject']->min_price * $number_pp; //klopt niet, moet pn zijn, niet pp?
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
				} elseif($value['priceObject']->use_pp_extra == 1 && $value['priceObject']->is_pn_extra == 1){
					
					if($number_pp <= 2){
						//eerst eerste nacht berekenen, daarna daaropvolgende dagen
						$dayStayPriceFirst	=  $value['priceObject']->min_price * $number_pp;
						$dayStayPrice		=  $value['priceObject']->extra * $number_pp;
						$totalStayPrice		+= $dayStayPrice * ($value['nachten'] - 1); //vermenigvuldigen met aantal nachten minus de eerste dag
						$totalStayPrice		+= $dayStayPriceFirst;
						$stayPeriods[$i]['nachten']			= $value['nachten'];
						$stayPeriods[$i]['priceObject']		= $value['priceObject'];
						$stayPeriods[$i]['number_pp']		= $number_pp;
						$stayPeriods[$i]['stayPeriodPrice']	= $totalStayPrice;
						$stayPeriods[$i]['message']			= $message;
					} elseif($number_pp > 2){
						//eerst eerste nacht berekenen, daarna daaropvolgende dagen
						$dayStayPriceFirst	=  $value['priceObject']->min_price * 2; //eerste 2 personen
						$dayStayPriceFirst	+= $value['priceObject']->pp_extra * ($number_pp - 2); //daaropvolgende personen
						$dayStayPrice		=  $value['priceObject']->min_price * 2; //eerste 2 personen
						$dayStayPrice		+= $value['priceObject']->pp_extra * ($number_pp - 2); //daaropvolgende personen
						$totalStayPrice		+= $dayStayPrice * ($value['nachten'] - 1); //vermenigvuldigen met aantal nachten
						$stayPeriods[$i]['nachten']			= $value['nachten'];
						$stayPeriods[$i]['priceObject']		= $value['priceObject'];
						$stayPeriods[$i]['number_pp']		= $number_pp;
						$stayPeriods[$i]['stayPeriodPrice']	= $totalStayPrice;
						$stayPeriods[$i]['message']			= $message;
					}
					
				} elseif ($value['priceObject']->use_pp_extra == 1 && $value['priceObject']->is_pn_extra == 0){
					//als us_pp_extra, dan eerst min_price * 2, alle daaropvolgende personen = + pp_extra
					if($number_pp <= 2){
					$dayStayPrice		=  $value['priceObject']->min_price * $number_pp;
					$totalStayPrice		+= $dayStayPrice * $value['nachten']; //vermenigvuldigen met aantal nachten					
					$stayPeriods[$i]['nachten']			= $value['nachten'];
					$stayPeriods[$i]['priceObject']		= $value['priceObject'];
					$stayPeriods[$i]['number_pp']		= $number_pp;
					$stayPeriods[$i]['stayPeriodPrice']	= $totalStayPrice;
					$stayPeriods[$i]['message']			= $message;
					} elseif($number_pp > 2){
						$dayStayPrice		=  $value['priceObject']->min_price * 2; //eerste 2 personen
						$dayStayPrice		+= $value['priceObject']->pp_extra * ($number_pp - 2); //daaropvolgende personen
						$totalStayPrice		+= $dayStayPrice * $value['nachten']; //vermenigvuldigen met aantal nachten
						$stayPeriods[$i]['nachten']			= $value['nachten'];
						$stayPeriods[$i]['priceObject']		= $value['priceObject'];
						$stayPeriods[$i]['number_pp']		= $number_pp;
						$stayPeriods[$i]['stayPeriodPrice']	= $totalStayPrice;
						$stayPeriods[$i]['message']			= $message;
					}
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
		
		//checken of er over de verblijfskosten ($totalStayPrice) een korting is
		
		$stayDiscount = $this->stayDiscountSubTotal($totalStayPrice,$this->overlap);
		
		if($stayDiscount && $stayDiscount->discount > 0){			
		
			$totalStayPrice -= $stayDiscount->discount;
			$app->setUserState("option_jbl.subTotalDiscountMessage", null);
			if($stayDiscount->message){
				$app->setUserState("option_jbl.subTotalDiscountMessage", $stayDiscount->message);
			}
			$app->setUserState("option_jbl.subTotalDiscount", null);
			$app->setUserState("option_jbl.subTotalDiscount", $stayDiscount->discount);
		
		}
		
		
		$app->setUserState("option_jbl.calcPrice", $totalStayPrice);
		$app->setUserState("option_jbl.stayperiods", $stayPeriods);
		if($this->overlap){
			$app->setUserState("option_jbl.arrPrice", $this->arrPrice);
		}
		$this->calcPrice	= $app->getUserState("option_jbl.calcPrice");
		
		
		$this->calcAttribs	= $this->calcAttribs();
		$this->app->setUserState("option_jbl.calcattribs", $this->calcAttribs);
		
		$this->calcAttribsSpecial = $this->calcAttribs;	
		
		//TODO: kortingen verwerken in prijsberekeningen
		//nu totale subtotaal
		$subtotal = 0;
		if($this->overlap){
			$subtotal += $this->arrPrice['calc']['arr_price'];			
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
		
		//checken of er over de subtotaalprijs ($subtotal) een korting is		
		$totalDiscount = $this->totalDiscount($subtotal,$this->overlap);
		
		if($totalDiscount && $totalDiscount->discount > 0){
		
			$totalPrice = $subtotal;
			$totalPrice -= $totalDiscount->discount;
			$app->setUserState("option_jbl.TotalDiscountMessage", null);
			if($totalDiscount->message){
				$app->setUserState("option_jbl.TotalDiscountMessage", $totalDiscount->message);
			}
			$app->setUserState("option_jbl.TotalDiscount", null);
			$app->setUserState("option_jbl.TotalDiscount", $totalDiscount->discount);
		
		}
		
		//als laatste de special extra kosten over de subTotaalprijs
		$totalAdd = $this->totalAdd($subtotal,$this->overlap);
		
		if($totalAdd && $totalAdd->add > 0){
			
			$totalPrice = $subtotal;
			$totalPrice += $totalAdd->add;
			$app->setUserState("option_jbl.TotalAddMessage", null);
			if($totalAdd->message){
				$app->setUserState("option_jbl.TotalAddMessage", $totalAdd->message);
			}
			$app->setUserState("option_jbl.TotalAdd", null);
			$app->setUserState("option_jbl.TotalAdd", $totalAdd->add);
		}
		
		//en dan, finally, de totaalprijs!
		
		//optellen: $subtotal, $totalAdd, $totalStayPrice, $totalDiscount
		$defTotal		 = 0;
		$defTotal		+= $subtotal;
		//$defTotal		+= $totalStayPrice;
		if($totalAdd && $totalAdd->add > 0){
			$defTotal	+= $totalAdd->add;
		}
		if($totalDiscount && $totalDiscount->discount){
			$defTotal	-= $totalDiscount->discount;
		}
		$app->setUserState("option_jbl.defTotal", null);
		$app->setUserState("option_jbl.defTotal", $defTotal);
	}
	
	
	public function totalAdd($subtotal,$overlap){
		
		$data = $this->app->input->get('jbl_form', null, null);
		$attributes = $this->app->getUserState("jbl_option.locationAttributes");
		$number_pp = $data['number_pp'];
		if($overlap){
			$nights = $overlap['buiten_arr'];
		} else {
			$nights = 1;
		}
		
		//eerst de required post-attribs ophalen
		
		$postAttribs = array();
		if($attributes){ //$attributes moet sowieso resultaat geven, anders is er geen attribuut aan locatie verbonden
			$db = JFactory::getDbo();
			foreach($attributes as $row){
				$query = $db->getQuery(true);
				$query->select('*');
				$query->from('#__jexbooking_attributes');
				$query->where('id='.$row->attribute_id.' AND published=1 AND is_special=1 AND is_discount=0 AND is_required=1');
				$db->setQuery($query);
				$result = $db->loadObject();
				if($result){
					$postAttribs[$result->id] = $result;
				}
			}
		
			//nu de postAttribs uit de not_required halen, if any
			if(isset($data['special']['not_required'])){
				$special = $data['special']['not_required'];
				//eerst de key 'percent'
				$db = JFactory::getDbo();
				if(isset($special['percent'])){
					$percent = $special['percent'];
					foreach($percent as $key=>$value){ //$key is de attribute_id uit de form
						$query = $db->getQuery(true);
						$query->select('*');
						$query->from('#__jexbooking_attributes');
						$query->where('id='.$key.' AND published=1 AND is_special=1 AND is_discount=0 AND is_required=0');
						$db->setQuery($query);
						$result = $db->loadObject();
						if($result){
							$postAttribs[$key] = $result;
						}
					}
		
				}
				if(isset($special['not_percent'])){
					$percent = $special['not_percent'];
					foreach($percent as $key=>$value){ //$key is de attribute_id uit de form
						$query = $db->getQuery(true);
						$query->select('*');
						$query->from('#__jexbooking_attributes');
						$query->where('id='.$key.' AND published=1 AND is_special=1 AND is_discount=0 AND is_required=0');
						$db->setQuery($query);
						$result = $db->loadObject();
						if($result){
							$postAttribs[$key] = $result;
						}
					}
				}
			}
		} //einde postAttribs
		$postPrice = 0;
		if(!empty($postAttribs)){
			foreach($postAttribs as $postAttrib){
				$var = 0;
				if($postAttrib->use_percent == 1){
					$var += ($totalStayPrice / 100) * $postAttrib->percent;
				}
				if($postAttrib->use_special_price == 1){
					$var += $postAttrib->special_price;
				}
				$multiplierPP = 1;
				$multiplierPN = 1;
				if($postAttrib->is_pp_special == 1){
					$multiplierPP = $number_pp;
				}
				if($postAttrib->is_pn_special == 1){
					$multiplierPN = $nights;
				}
				$postPrice += $var * $multiplierPN * $multiplierPP;
			}
		}
		
		$totalAdd->add = $postPrice;
		$totalAdd->message = 'Prijs boven subtotaal';
		
		return $totalAdd;
		
	}
	
	/**
	 * method om de korting over het totaalbedrag (subtotal) te berekenen
	 * @param unknown $subTotal
	 * @param unknown $overlap
	 */
	public function totalDiscount($subTotal,$overlap){
		
		$totalStayPrice = $subTotal;
		
		$data = $this->app->input->get('jbl_form', null, null);
		$attributes = $this->app->getUserState("jbl_option.locationAttributes");
		$number_pp = $data['number_pp'];
		if($overlap){
			$nights = $overlap['buiten_arr'];
		} else {
			$nights = 1;
		}
		
		//eerst de required discounts ophalen
		
		$discounts = array();
		if($attributes){ //$attributes moet sowieso resultaat geven, anders is er geen attribuut aan locatie verbonden
			$db = JFactory::getDbo();
			foreach($attributes as $row){
				$query = $db->getQuery(true);
				$query->select('*');
				$query->from('#__jexbooking_attributes');
				$query->where('id='.$row->attribute_id.' AND published=1 AND is_special=1 AND is_discount=1 AND is_discount_subtotal=0 AND is_required=1');
				$db->setQuery($query);
				$result = $db->loadObject();
				if($result){
					$discounts[$result->id] = $result;
				}
			}
		
			//nu de discounts uit de not_required halen, if any
			if(isset($data['special']['not_required'])){
				$special = $data['special']['not_required'];
				//eerst de key 'percent'
				$db = JFactory::getDbo();
				if(isset($special['percent'])){
					$percent = $special['percent'];
					foreach($percent as $key=>$value){ //$key is de attribute_id uit de form
						$query = $db->getQuery(true);
						$query->select('*');
						$query->from('#__jexbooking_attributes');
						$query->where('id='.$key.' AND published=1 AND is_special=1 AND is_discount=1 AND is_discount_subtotal=0 AND is_required=0');
						$db->setQuery($query);
						$result = $db->loadObject();
						if($result){
							$discounts[$key] = $result;
						}
					}
						
				}
				if(isset($special['not_percent'])){
					$percent = $special['not_percent'];
					foreach($percent as $key=>$value){ //$key is de attribute_id uit de form
						$query = $db->getQuery(true);
						$query->select('*');
						$query->from('#__jexbooking_attributes');
						$query->where('id='.$key.' AND published=1 AND is_special=1 AND is_discount=1 AND is_discount_subtotal=0 AND is_required=0');
						$db->setQuery($query);
						$result = $db->loadObject();
						if($result){
							$discounts[$key] = $result;
						}
					}
				}
			}
		} // einde if($attributes) statement
		
		$subDiscount = 0;
		if(!empty($discounts)){
			foreach($discounts as $discount){
				$var = 0;
				if($discount->use_percent == 1){
					$var += ($totalStayPrice / 100) * $discount->percent;
				}
				if($discount->use_special_price == 1){
					$var += $discount->special_price;
				}
				$multiplierPP = 1;
				$multiplierPN = 1;
				if($discount->is_pp_special == 1){
					$multiplierPP = $number_pp;
				}
				if($discount->is_pn_special == 1){
					$multiplierPN = $nights;
				}
				$subDiscount += $var * $multiplierPN * $multiplierPP;
			}
		}
		
		$subTotalDiscount->discount = $subDiscount;
		if($overlap){
			$subTotalDiscount->message = 'Korting op totaalprijs (uitgezonderd arrangement)';
		} else {
			$subTotalDiscount->message = 'Korting op totaalprijs';
		}
		return $subTotalDiscount;
	}
	
	/**
	 * method om korting over verblijfskosten ($totalStayPrice) te berekenen
	 * @param string $totalStayPrice
	 * @return string 
	 */
	public function stayDiscountSubTotal($totalStayPrice,$overlap){		
		
		$data = $this->app->input->get('jbl_form', null, null);
		$attributes = $this->app->getUserState("jbl_option.locationAttributes");
		$number_pp = $data['number_pp'];
		if($overlap){
			$nights = $overlap['buiten_arr'];
		} else {
			$nights = 1;
		}
		
		//eerst de required discounts ophalen
				
		$discounts = array();
		if($attributes){ //$attributes moet sowieso resultaat geven, anders is er geen attribuut aan locatie verbonden
			$db = JFactory::getDbo();
			foreach($attributes as $row){
				$query = $db->getQuery(true);
				$query->select('*');
				$query->from('#__jexbooking_attributes');
				$query->where('id='.$row->attribute_id.' AND published=1 AND is_special=1 AND is_discount=1 AND is_discount_subtotal=1 AND is_required=1');
				$db->setQuery($query);
				$result = $db->loadObject();
				if($result){
					$discounts[$result->id] = $result;
				}
			}
		
			//nu de discounts uit de not_required halen, if any
			if(isset($data['special']['not_required'])){				
				$special = $data['special']['not_required'];
				//eerst de key 'percent'
				$db = JFactory::getDbo();
				if(isset($special['percent'])){
					$percent = $special['percent'];
					foreach($percent as $key=>$value){ //$key is de attribute_id uit de form
						$query = $db->getQuery(true);
						$query->select('*');
						$query->from('#__jexbooking_attributes');
						$query->where('id='.$key.' AND published=1 AND is_special=1 AND is_discount=1 AND is_discount_subtotal=1 AND is_required=0');
						$db->setQuery($query);
						$result = $db->loadObject();
						if($result){
							$discounts[$key] = $result;
						}
					}
					
				}
				if(isset($special['not_percent'])){
					$percent = $special['not_percent'];
					foreach($percent as $key=>$value){ //$key is de attribute_id uit de form
						$query = $db->getQuery(true);
						$query->select('*');
						$query->from('#__jexbooking_attributes');
						$query->where('id='.$key.' AND published=1 AND is_special=1 AND is_discount=1 AND is_discount_subtotal=1 AND is_required=0');
						$db->setQuery($query);
						$result = $db->loadObject();
						if($result){
							$discounts[$key] = $result;
						}
					}
				}
			}
		} // einde if($attributes) statement
		
		$subDiscount = 0;
		if(!empty($discounts)){
			foreach($discounts as $discount){
				$var = 0;
				if($discount->use_percent == 1){
					$var += ($totalStayPrice / 100) * $discount->percent;
				}
				if($discount->use_special_price == 1){
					$var += $discount->special_price;
				}				
				$multiplierPP = 1;
				$multiplierPN = 1;
				if($discount->is_pp_special == 1){
					$multiplierPP = $number_pp;
				}
				if($discount->is_pn_special == 1){
					$multiplierPN = $nights;
				}
				$subDiscount += $var * $multiplierPN * $multiplierPP;
			}
		}
		
		$subTotalDiscount->discount = $subDiscount;
		if($overlap){
			$subTotalDiscount->message = 'Korting op overnachtingen (buiten arrangement)';
		} else {
			$subTotalDiscount->message = 'Korting op overnachtingen';
		}		
		
		return $subTotalDiscount;
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
		$data = $this->app->input->get("jbl_form",null,null);
		
		$this->locationId = $this->app->getUserState("jbl_option.location_id");
		
		if(!$this->locationd_id){
			if($data['location']){
				$this->location_id = $data['location'];
			}
		}
		
		$this->overlap = $this->app->getUserState("option_jbl_overlap");
		
		$startDate = new DateTime($data['start_date']);
		$endDate = new DateTime($data['end_date']);
		$locationId = $this->locationId;
		
		$model = $this->getModel('dates');
		$this->prices = $model->getPrices($locationId,$startDate,$endDate,$this->overlap);
		
		$this->app->setUserState("option_jbl.prices", $this->prices);
	}
	
	public function process(){
		$app = JFactory::getApplication();
		$app->input->set('layout', 'bedankt');
		$naw = $app->getUserState("option_process_jbl.naw");
		$final = $app->getUserState("option_process_jbl.final");
		$comment = $app->getUserState("option_process_jbl.comment");
		
		$mailText = $this->emailText($naw,$final,$comment);
		$mailfrom	= $app->getCfg('mailfrom');
		$mailfromSite	= $app->getCfg('sitename');
		
		$this->sendMail($naw['mail'],$mailText);
		
		$this->storeReservation($naw,$mailText);
		
		$this->display();
	}
	/**
	 * method om de reservering in de database op te slaan
	 * @param array $naw
	 * @param string $mailText
	 * @return void
	 */
	public function storeReservation($naw,$mailText){
		
		$name	= $naw['name'];
		$email	= $naw['mail'];
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = 
			" INSERT INTO `#__jexbooking_reserveringen`(`email`,`name`,`mail_text`)
			VALUES('".$db->escape($email).
			"', '". $db->escape($name).
			"', '". $db->escape($mailText).
			"')";
		$db->setQuery($query);
		$db->query();
	}
	/**
	 * method om de e-mail te versturen
	 * @param unknown $email
	 * @param unknown $body
	 */
	function sendMail($email,$body){
		//config data halen
		$app = JFactory::getApplication();
		$mailfrom	= $app->getCfg('mailfrom');
		$sitename	= $app->getCfg('sitename');
	
		//mail functions
		$mailer = JFactory::getMailer();
		$mailer->setSender(array($mailfrom,$sitename)); //uit config sitenaam en email halen
		$mailer->addRecipient($email);
		$mailer->setSubject("Uw reservering bij De Papillon");
		$mailer->isHTML(true);
		$mailer->setBody($body);
		$mailer->Send();
	}
	
	/**
	 * method om de body voor de email aan te maken
	 * @param array $naw
	 * @param array $final
	 * @param array $comment
	 * 
	 * @return string
	 */
	public function emailText($naw,$final,$comment){
		
		$text	 = 'Uw reservering bij De Papillon';
		$text	.= "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"border:none;\"><tr><td>Periode:</td><td>";
		$text	.= $final['start_date'];
		$text	.= "</td><td>";
		$text	.= $final['end_date'];
		$text	.= "</td></tr><tr><td>Personen:</td><td>";
		$text	.= $final['number_pp'];
		$text	.= "</td><td></td></tr>";
		if(isset($final['arrangement']) && !empty($final['arrangement'])){
			foreach($final['arrangement'] as $item){
				$text	.= "<tr><td>";
				$text	.= $item['name'];
				$text	.= "</td>";
				$text	.= "<td>&euro;&nbsp;";
				$text	.= number_format($item['price'], 2, ',', '.');
				$text	.= "</td><td>&nbsp;</td></tr>";
			}
		}
		if(isset($final['stayPeriod']) && !empty($final['stayPeriod'])){
			foreach($final['stayPeriod'] as $item){
				$text	.= "<tr><td>";
				$text	.= $item['nachten']."&nbsp;nachten&nbsp".$item['name'];
				$text	.= "</td><td>&euro;&nbsp;";
				$text	.= number_format($item['stayPeriodPrice'], 2, ',', '.');
				$text	.="</td><td>&nbsp;</td></tr>";
			}
		}
		$text	.= "<tr>
					<td>&nbsp;</td>				
					<td style=\"text-align:left;\">+</td>
					<td>&nbsp;</td>
				</tr>";
		$text	.= "<tr>
					<td>Totaalprijs overnachtingen</td>				
					<td style=\"text-align:left;\">&euro;&nbsp;";
		$text	.= number_format($final['calcTotalStayPeriodsPrice'], 2, ',', '.');
		$text	.= "</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>";
		if(isset($final['calcAttribs']) && !empty($final['calcAttribs'])){
			foreach($final['calcAttribs'] as $item){
				$text	.= "
						<tr>
							<td>";
				$text	.= $item['name'];
				$text	.="</td>
							<td>
								&euro;&nbsp;";
				$text	.= number_format($item['price'], 2, ',', '.');
				$text	.="</td>
							<td>&nbsp;</td>
						</tr>";
			}
			$text	.= "<tr>
						<td>&nbsp;</td>				
						<td style=\"text-align:left;\">+</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Subtotaal toevoegingen</td>				
						<td style=\"text-align:left;\">&euro;&nbsp;";
			$text	.= number_format($final['subTotalAttribs'], 2, ',', '.');
			$text	.="</td>
						<td>&nbsp;</td>
					</tr>";
		}
		$text	.= "<tr>
					<td>&nbsp;</td>				
					<td style=\"text-align:left;\">+</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Subtotaalprijs</td>				
					<td style=\"text-align:left;\">&euro;&nbsp;";
		$text	.= number_format($final['subTotalPrice'], 2, ',', '.');
		$text	.="</td>
					<td>&nbsp;</td>
				</tr>";
		if(isset($final['totalAdd']) && !empty($final['totalAdd'])){
			$text	.= "<tr>
						<td>";
			$text	.= $final['totalAdd']['message'];
			$text	.= "</td>
						<td>
							&euro;&nbsp;";
			$text	.= number_format((double)$final['totalAdd']['price'], 2, ',', '.');
			$text	.= "</td>
						<td>&nbsp;</td>
					</tr>";
		}
		$text	.= "<tr>
					<td colspan=\"3\">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>				
					<td style=\"text-align:left;\">+</td>
					<td>&nbsp;</td>
				</tr>
				<tr style=\"font-weight:bold;\">
					<td>
						Totaalprijs
					</td>
					<td>
						&euro;&nbsp;";
		$text	.= number_format($final['defTotal'], 2, ',', '.');
		$text	.= "</td>
					<td>
						&nbsp;
					</td>
				</tr>";
		$text	.= "<tr><td colspan=\"3\">&nbsp;</td></tr>";	
		$text	.= "<tr style=\"font-weight:bold;\">
					<td colspan=\"3\">Uw gegevens:</td>
					</tr>";
		$text	.= "<tr><td>Naam:</td><td>";
		$text	.= $naw['name'];
		if($naw['surname'] != ''){
			$text	.= ', '.$naw['surname'];
		}
		$text	.= "</td><td>&nbsp;</td></tr>";
		$text	.= "<tr><td>Adres:</td><td>";
		$text	.= $naw['street'].'&nbsp;'.$naw['street_number'].'<br />';
		$text	.= $naw['zipcode'].'&nbsp;'.$naw['city'];
		$text	.= "</td><td>&nbsp;</td></tr>";
		$text	.= "<tr><td>Telefoon:</td><td>";
		$text	.= $naw['phone'];
		$text	.= "</td<td>&nbsp;</td></tr>";
		$text	.= "<tr><td>E-mail:</td><td>";
		$text	.= $naw['mail'];
		$text	.= "</td><td>&nbsp;</td></tr>";
		$text	.= "<tr><td colspan=\"3\">&nbsp;</td></tr>";
		foreach($naw['birthdate'] as $item){
			$text	.= "<tr><td>Geb. datum:</td><td>";
			$text	.= $item;
			$text	.= "</td><td>&nbsp;</td></tr>";
		}
		if(isset($comment) && !empty($comment) && $comment != ""){
			$text	.= "<tr><td colspan=\"3\">&nbsp;</td></tr>";
			$text	.= "<tr style=\"font-weight:bold;\">
					<td colspan=\"3\">Uw opmerkingen:</td>
					</tr>
					<tr>
					<td colspan=\"3\">";
			$text	.= $comment;
			$text	.= "</td>
					</tr>";
		}
		$text	.= "</table>";		
		
		return $text;
	}
}