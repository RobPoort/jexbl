<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.model');

class JexBookingModelDates extends JModel
{
	
	/**
	 * method om lijst met location_id's op te halen voor keuzelijst op defaultpagina
	 * @return	Object
	 */
	public function getLocations(){
		
		//eerst type_id uit params halen
		$app = JFactory::getApplication();
		$type_id = $app->input->get('type_id');
		
		//nu de locations ophalen aan de hand van de type_id
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_location as jl');
		$query->select('name, id');
		$query->where('type_id='.$type_id.' AND published=1');
		$db->setQuery($query);
		
		$rows = $db->loadObjectList();
		
		return $rows;		
	}
	
	
	/**
	 * method om de locatie of locatiesoort op te halen, en de daarbij behorende attributen
	 * @return object
	 */
	function getItem(){
		$app = JFactory::getApplication();
		
		//aankomst- en vertrekdatum
		$date = $app->input->get("jbl_form",null,null);
		if($date){
			$start_date = $date['start_date'];
			$end_date = $date['end_date'];
			
			
			$start = new DateTime($start_date);
			$end = new DateTime($end_date);
			$diff = $start->diff($end);
			$nights = $diff->days;
		}
		//eerst bepalen of het gaat om ��n locatie, of om een locatiesoort
		//$this->choose = $this->app->get('choose');
		
		
		
		//nu de locatie_id ophalen uit de params
		$this->location_id = $app->getUserState("jbl_option.location_id");		
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->from('#__jexbooking_location as jl');
		$query->select('*');
		$query->where('id='.$this->location_id.' AND published=1');
		
		
		$db->setQuery($query);
		$row = $db->loadObject();
		
		$result = array();
		$result['locatie'] = $row;
		$result['aankomst'] = $start_date;
		$result['vertrek'] = $end_date;
		$result['nights'] = $nights;
		
		//attributen ophalen via xref
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_xref_attributes');
		$query->select('attribute_id');
		$query->where('location_id='.$row->id);
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		//de attribs zelf ophalen en in gegroepeerde arrays zetten, zoals special required, extras etc
		$result['attribs']['checked'] = array();
		$result['attribs']['number'] = array();
		
		$result['attribs']['extras']['checked'] = array();
		$result['attribs']['extras']['number'] = array();
		
		$result['attribs']['special']['required']['checked'] = array();
		$result['attribs']['special']['required']['number'] = array();
		
		$result['attribs']['special']['not_required']['percent'] = array();
		$result['attribs']['special']['not_required']['not_percent'] = array();
		if($rows){
			foreach($rows as $row){
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->from('#__jexbooking_attributes');
				$query->where("id=".$row->attribute_id);
				$query->select('*');
				$db->setQuery($query);
				$attribs = $db->loadObjectList();
				
					foreach($attribs as $attrib){
						if($attrib->is_special == 0){
							if($attrib->has_number){
								if($attrib->has_price){
									$result['attribs']['number'][] = $attrib;
								} else{
									$result['attribs']['extras']['number'][] = $attrib;
								}
								
							} else{
								if($attrib->has_price){
									$result['attribs']['checked'][] = $attrib;
								} else{
									$result['attribs']['extras']['checked'][] = $attrib;
								}
							}
						} else{
							if($attrib->is_required){
								if($attrib->use_percent){
									$result['attribs']['special']['required']['percent'][] = $attrib;
								} else{
									$result['attribs']['special']['required']['not_percent'][] = $attrib;
								}
							} else{
								//TODO: hier nog meer statements ivm beide: zowel percent als special_price!!!!
								if($attrib->use_percent){
									$result['attribs']['special']['not_required']['percent'][] = $attrib;
								} else{
									$result['attribs']['special']['not_required']['not_percent'][] = $attrib;
								}
							}
						}
					}
			}
		}
		
		return $result;
	}
	
	/**
	 * method om de arrangements op te halen met dezelfde location_id en overeenkomstige periode
	 * @param int $locationId
	 * @param object $startDate
	 * @param object $endDate
	 * @return Object
	 */
	public function getArrangements($locationId,$startDate,$endDate){	
		
		//$startDate en $endDate DateTime objecten
		$start = $startDate;
		$end = $endDate;
		
		//eerst alle arrs ophalen, daarna datumcheck
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_arrangements');
		$query->select('*');
		$query->where('location_id='.(int)$locationId.' AND published=1 AND required=1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$result = array();
		
		if($rows){
			foreach($rows as $row){				
				//start_date en end_date van arrangementen in DateTime-objecten omzetten
				$arrStart = new DateTime($row->start_date);
				$arrEnd = new DateTime($row->end_date);
				
				//mogelijkheden:
				
				
				// start en end voor arrangement: $end < $arrEnd => default
				// start en end na arrangement: $start > $arrEnd => default
				
				// start voor arrStart en end na arrEnd: $start < $arrStart && $end > $arrEnd
				// start en end beiden tussen arrStart en arrEnd in: $start > $arrStart && $start < $arrEnd && $end > $arrStart && $end < $arrEnd 
				// start voor arrStart, maar end tussen arrStart en arrEnd in: $start < $arrStart && $end > $arrStart && $end < $arrEnd
				
				// start na arrStart en end na arrEnd: $start > $arrStart && $start < $arrEnd && $end > $arrEnd
				$date = new stdClass();
				$date->start = $start;
				$date->end = $end;
				$date->arrStart = $arrStart;
				$date->arrEnd = $arrEnd;
				
				switch ($date){
					case ($date->start <= $date->arrStart && $date->end >= $date->arrEnd):
						//arrangement valt geheel binnen periode
						$result['overlap_message'] = 'Binnen de door u gekozen periode valt het '.ucwords($row->name).' arrangement. Uw prijs wordt berekend door de kosten van het arrangement op te tellen bij de dagen die vooraf gaan aan het arrangement en de dagen erna.';						
						$result['buiten_arr'] = ($date->start->diff($date->arrStart)->days) + ($date->arrEnd->diff($date->end)->days);
						$result['arrangement'] = $row;
						if($result['buiten_arr'] == 0){
							$result['overlap_message'] = 'De door u gekozen periode valt samen met het '.ucwords($row->name).' arrangement. De kosten van uw verblijf worden berekend aan de hand van het arrangement.';
						}
						break 2;
						
					case ($date->start >= $date->arrStart && $date->start <= $date->arrEnd && $date->end <= $date->arrEnd && $date->end >= $date->arrStart):
						//periode valt geheel binnen arrangement
						$result['overlap_message'] = 'De door u gekozen periode valt geheel binnen het '.ucwords($row->name).' arrangement. De prijs van de door u gekozen periode is gelijk aan die van het arrangement.';
						$result['buiten_arr'] = 0;
						$result['arrangement'] = $row;
						
						break 2;
					case ($date->start <= $date->arrStart && $date->end > $date->arrStart && $date->end <= $date->arrEnd):
						//aankomst is voor arrangement en vertrek is binnen arrangement
						$result['overlap_message'] = 'De door u gekozen vertrekdatum valt binnen het '.ucwords($row->name).' arrangement. Uw prijs wordt berekend door de kosten van het arrangement op te tellen bij de dagen voorafgaand aan het arrangement.';
						$result['buiten_arr'] = $date->start->diff($date->arrStart)->days;
						$result['arrangement'] = $row;
						
						break 2;
					case ($date->start >= $date->arrStart && $date->start < $date->arrEnd && $date->end >= $date->arrEnd):
						//aankomst is binnen arrangement en vertrek is na arrangement
						$result['overlap_message'] = 'De door u gekozen aankomstdatum valt binnen het '.ucwords($row->name).' arrangement. Uw prijs zal berekend worden door de kosten van het arrangement op te tellen bij de dagen erna.';
						$result['buiten_arr'] = $date->arrEnd->diff($date->end)->days;
						$result['arrangement'] = $row;
						
						break 2;
					default:
						$result = null;
						
						break 2;
				}
				
			}
		} else{
			$result = null;
		}
		
		
		return $result;
		
	}
	
	/**
	 * method om de default prices op te halen aan de hand van locatie en periode
	 * @param $locationId
	 * @param $startDate
	 * @param $endDate
	 * $param $overlap
	 * @return Object
	 */
	public function getPrices($locationId,$startDate,$endDate,$overlap){
		
		//het gaat om de periode, of de periode minus de dagen die eventueel in een arrangement vallen
		
		$start = $startDate;
		$end = $endDate;
		
		$this->app = JFactory::getApplication();
		
		$overlap = $this->app->getUserState('option_jbl_overlap');
		
		$this->prices->startDate = $start;
		$this->prices->endDate = $end;
		
		//eerst de start_date en end_dates ophalen van de prijsperiodes voor deze locatie
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_default_prices');
		$query->where('location_id='.$locationId);
		$query->select('*');
		$db->setQuery($query);
		
		$prices = $db->loadObjectList();
		
		//van de start_dates en end_dates van de prijsperiodes DateTime objects maken en in array $dateTimes zetten
		if($prices){			
			$dateTimes = array();
			foreach($prices as $price){
				$dateTimes[$price->id]['start'] = new DateTime($price->start_date);
				$dateTimes[$price->id]['end'] = new DateTime($price->end_date);
				$dateTimes[$price->id]['priceObject'] = $price;
			}
			
		}
		
		//verblijfsperiode - overlap opdelen in verblijfsperiode(s)
		$this->prices->pricePeriods = array();
		
		$buitenArrPeriods = array();
		
		if($overlap){
			$startArr = new DateTime($overlap['arrangement']->start_date);
			$endArr = new DateTime($overlap['arrangement']->end_date);			
			
			if($start < $startArr && $end <= $endArr){
				
				$buitenArrPeriods[0]['start'] = $start;
				$buitenArrPeriods[0]['end'] = $startArr;
				
			} elseif($start >= $startArr && $end > $endArr){
							
				$buitenArrPeriods[0]['start'] = $endArr;
				$buitenArrPeriods[0]['end'] = $end;
				
			} elseif($start < $startArr && $end > $endArr){
				
				$buitenArrPeriods[0]['start'] = $start;
				$buitenArrPeriods[0]['end'] = $startArr;
				
				$buitenArrPeriods[1]['start'] = $endArr;
				$buitenArrPeriods[1]['end'] = $end;
			}
			$this->prices->buitenArrPeriods = $buitenArrPeriods;
			
		}
		if(!empty($buitenArrPeriods)){
			//$i = 0;
			foreach($buitenArrPeriods as $stayPeriod){
				
				
				$start	= $stayPeriod['start'];
				$end	= $stayPeriod['end'];
				
				//beginnen met de startDate					
				foreach($dateTimes as $key=>$value){
					if($start >= $value['start'] && $start < $value['end'] && $value['priceObject']->is_default == 0){
						$this->prices->startPeriod = $key;
					}
					if($end >= $value['start'] && $end <= $value['end'] && $value['priceObject']->is_default == 0){
						$this->prices->endPeriod = $key;
					}
				}
				if($this->prices->startPeriod == null){
					foreach($dateTimes as $key=>$value){
						if($start >= $value['start'] && $start < $value['end'] && $value['priceObject']->is_default == 1){
							$this->prices->startPeriod = $key;
						}
					}
				}
				if($this->prices->endPeriod == null){
					foreach($dateTimes as $key=>$value){
						if($end >= $value['start'] && $end < $value['end'] && $value['priceObject']->is_default == 1){
							$this->prices->endPeriod = $key;
						}
					}
				}
				//checken of het ��n of meer prijsperiodes betreft
				if($this->prices->startPeriod == $this->prices->endPeriod){
					//1 prijsperiode, en wel:
					$this->prices->pricePeriods[0]['priceId'] 		= $this->prices->startPeriod;
					$this->prices->pricePeriods[0]['priceObject']	= $dateTimes[$this->prices->startPeriod]['priceObject'];
					//nu aantal dagen binnen die prijsperiode
					$diff = $start->diff($end);
					$this->prices->pricePeriods[0]['nachten'] = $diff->days;
				
				
				
				} else {
					//meer prijsperiodes, eerst de start-pricePeriod
					$startPricePeriod = $dateTimes[$this->prices->startPeriod];
					$diff	= $start->diff($startPricePeriod['end']);
				
					$this->prices->pricePeriods[0]['priceId']	= $startPricePeriod['priceObject']->id;
					$this->prices->pricePeriods[0]['priceObject'] = $startPricePeriod['priceObject'];
					$this->prices->pricePeriods[0]['nachten']	= $diff->days;
				
					$newStart = $startPricePeriod['end'];
				
					$i = 1;
					foreach($dateTimes as $key=>$value){
						if($value['priceObject']->is_default == 0 && $newStart >= $value['start'] && $newStart < $value['end'] && $newStart < $end){
				
							$this->prices->pricePeriods[$i]['priceId'] = $key;
				
							//nieuwe $newStart en $diff bepalen
							if($end > $value['end']){
								$diff = $newStart->diff($value['end']);
								$newStart = $value['end'];
							} elseif($end <= $value['end']){
								$diff = $newStart->diff($end);
							}
							$this->prices->pricePeriods[$i]['priceObject'] = $value['priceObject'];
							$this->prices->pricePeriods[$i]['nachten'] = $diff->days;
				
							$i++;
				
						}
					}
				
					//nu controleren of er dagen in de default prijzen valt. Totaal aantal dagen - dagen van pricePeriods => als groter dan nul, dan default
					$diff = $start->diff($end);
					$totalDays = $diff->days;
					//nu dagen uit pricePeriods optellen
					$pricedDays = 0;
					foreach($this->prices->pricePeriods as $period){
						$pricedDays += $period['nachten'];
					}
					$defaultDays = $totalDays - $pricedDays;
					if($defaultDays > 0){
						foreach($dateTimes as $key=>$value){
							if($value['priceObject']->is_default == 1){
								$this->prices->pricePeriods[$i]['priceId'] = $key;
								$this->prices->pricePeriods[$i]['priceObject'] = $value['priceObject'];
								$this->prices->pricePeriods[$i]['nachten'] = $defaultDays;
							}
						}
					}
				}
			}
		}
		//nu de verblijfsperiode checken tegen overlap en prijsperiodes. Indien geen overlap en maar 1 prijsperiode, verder. Anders verblijfsperiode opknippen in losse verblijsperiodes
		
		
		if($overlap == null){
			//beginnen met de startDate
			foreach($dateTimes as $key=>$value){
				if($start >= $value['start'] && $start < $value['end'] && $value['priceObject']->is_default == 0){
					$this->prices->startPeriod = $key;
				}
				if($end >= $value['start'] && $end <= $value['end'] && $value['priceObject']->is_default == 0){
					$this->prices->endPeriod = $key;
				}
			}
			if($this->prices->startPeriod == null){
				foreach($dateTimes as $key=>$value){
					if($start >= $value['start'] && $start < $value['end'] && $value['priceObject']->is_default == 1){
						$this->prices->startPeriod = $key;
					}
				}
			}
			if($this->prices->endPeriod == null){
			foreach($dateTimes as $key=>$value){
					if($end >= $value['start'] && $end < $value['end'] && $value['priceObject']->is_default == 1){
						$this->prices->endPeriod = $key;
					}
				}
			}
			//checken of het ��n of meer prijsperiodes betreft
			if($this->prices->startPeriod == $this->prices->endPeriod){
				//1 prijsperiode, en wel:
				$this->prices->pricePeriods[0]['priceId'] = $this->prices->startPeriod;
				$this->prices->pricePeriods[0]['priceObject'] = $dateTimes[$this->prices->startPeriod]['priceObject'];
				//nu aantal dagen binnen die prijsperiode
				$diff = $start->diff($end);
				$this->prices->pricePeriods[0]['nachten'] = $diff->days;
				
				
				
			} else {
				
				//meer prijsperiodes, eerst de start-pricePeriod
				$startPricePeriod = $dateTimes[$this->prices->startPeriod];
				$diff	= $start->diff($startPricePeriod['end']);
				
				$this->prices->pricePeriods[0]['priceId']	= $startPricePeriod['priceObject']->id;				
				$this->prices->pricePeriods[0]['priceObject'] = $startPricePeriod['priceObject'];
				$this->prices->pricePeriods[0]['nachten']	= $diff->days;
				
				$newStart = $startPricePeriod['end'];
				
				$i = 1;
				foreach($dateTimes as $key=>$value){
					if($value['priceObject']->is_default == 0 && $newStart >= $value['start'] && $newStart < $value['end'] && $newStart < $end){
						
						$this->prices->pricePeriods[$i]['priceId'] = $key;
						
						//nieuwe $newStart en $diff bepalen
						if($end > $value['end']){
							$diff = $newStart->diff($value['end']);
							$newStart = $value['end'];
						} elseif($end <= $value['end']){
							$diff = $newStart->diff($end);
						}
						$this->prices->pricePeriods[$i]['priceObject'] = $value['priceObject'];
						$this->prices->pricePeriods[$i]['nachten'] = $diff->days;
						
						$i++;
						
					}
				}
				
				//nu controleren of er dagen in de default prijzen valt. Totaal aantal dagen - dagen van pricePeriods => als groter dan nul, dan default
				$diff = $start->diff($end);
				$totalDays = $diff->days;
				//nu dagen uit pricePeriods optellen
				$pricedDays = 0;
				foreach($this->prices->pricePeriods as $period){
					$pricedDays += $period['nachten'];
				}
				$defaultDays = $totalDays - $pricedDays;
				if($defaultDays > 0){
					foreach($dateTimes as $key=>$value){
						if($value['priceObject']->is_default == 1){
							$this->prices->pricePeriods[$i]['priceId'] = $key;
							$this->prices->pricePeriods[$i]['priceObject'] = $value['priceObject'];
							$this->prices->pricePeriods[$i]['nachten'] = $defaultDays;
						}
					}
				}
			}
		}
		
		return $this->prices;
	}
	
}