<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.model');

class JexBookingModelDates extends JModel
{
	
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
			
			list($day,$month,$year) = explode('-', $start_date);
			$start = mktime(0,1,0,$month,$day,$year);
			list($day,$month,$year) = explode('-', $end_date);
			$end = mktime(0,1,0,$month,$day,$year);
			
			$nights = ($end - $start) / 86400;
		}
		//eerst de locatie_id ophalen uit de params
		$this->location_id = $app->input->get('location_id');
		
		$this->type_id = $app->input->get('type_id');
		$this->choose = $app->input->get('choose');
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->from('#__jexbooking_location as jl');
		$query->select('*');		
		if(!$this->choose){
			$query->where('type_id='.$this->type_id.' AND published=1');
		} else{
			$query->where('id='.$this->location_id.' AND published=1');
		}
		
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
									$result['attribs']['special']['required']['percent'] = $attrib;
								} else{
									$result['attribs']['special']['required']['not_percent'] = $attrib;
								}
							} else{
								if($attrib->use_percent){
									$result['attribs']['special']['not_required']['percent'] = $attrib;
								} else{
									$result['attribs']['special']['not_required']['not_percent'] = $attrib;
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
						$result['overlap_message'] = 'De door u gekozen periode valt geheel binnen het '.ucwords($row->name).' arrangement. De kosten van de door u gekozen periode is gelijk aan die van het arrangement.';
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
	
}