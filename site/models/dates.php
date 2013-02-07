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
		$date = $app->input->get("date",null,null);
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
	 * @param int $location_id
	 * @param string $start_date
	 * @param string $end_date
	 * @return Object
	 */
	public function getArrangements($location_id,$start_date,$end_date){
		
		//unixtijden maken
		list($day,$month,$year) = explode('-', $start_date);
		$start = mktime(0,1,0,$month,$day,$year);
		list($day,$month,$year) = explode('-', $end_date);
		$end = mktime(0,1,0,$month,$day,$year);
		
		//eerst alle arrs ophalen, daarna datumcheck
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_arrangements');
		$query->select('*');
		$query->where('location_id='.(int)$location_id.' AND published=1 AND required=1');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$result = array();
		
		if($rows){
			foreach($rows as $row){
				list($day,$month,$year) = explode('-', $row->start_date);
				$start_arr = mktime(0,1,0,$month,$day,$year);
				list($day,$month,$year) = explode('-', $row->end_date);
				$end_arr = mktime(0,1,0,$month,$day,$year);
				
				// overlap_unix is de unix-tijd van ofwel de vertrekdatum, ofwel de aankomstdatum, welke binnen arrangement valt
				// indien overlap_unix = 0, dan valt een geheel arrangement binnen de periode aankomstdatum - vertrekdatum
				if($end > $start_arr && $end < $end_arr){
					$result['overlap_message'] = 'uw vertrekdatum valt binnen het "'.ucwords($row->name).'" arrangement. Uw prijs wordt berekend door de kosten van het arrangement op te tellen bij de dagen die vooraf gaan aan het arrangement. '; 
					
					$result['dagen_voor'] = ($start_arr - $start) / 86400;
					
					$result['start_unix'] = $start;
					$result['end_unix'] = $end;
					
					$result['start_unix_arr'] = $start_arr;
					$result['end_unix_arr'] = $end_arr;
					
					$result['buiten_arr'] = ($start_arr - $start) / 86400;
					
					$result['arrangement'] = $row;
					
				} elseif($start > $start_arr && $start < $end_arr){
					$result['overlap_message'] = 'uw aankomstdatum valt binnen het '.$row->name.' arrangement. Uw prijs wordt berekend door de kosten van het arrangement op te tellen bij de dagen die na de arrangementsperiode vallen. ';
					
					$result['dagen_na'] = ($end - $end_arr) / 86400;					
					
					$result['start_unix'] = $start;
					$result['end_unix'] = $end;
						
					$result['start_unix_arr'] = $start_arr;
					$result['end_unix_arr'] = $end_arr;
					
					$result['buiten_arr'] ($end - $end_arr) / 86400;
					
					$result['arrangement'] = $row;
					
				} elseif($start < $start_arr && $end > $end_arr){
					$result['overlap_message'] = 'Binnen de door u gekozen periode valt het arrangement '.$row->name.'. Uw prijs wordt berekend door de kosten van het arrangement op te tellen bij de dagen die vooraf gaan aan het arrangement en die na de arrangementsperiode vallen. ';
					
					$result['dagen_minus'] = ((($start_arr - $start) / 86400) + (($end - $end_arr) / 86400) - (($end_arr - $start_arr) / 86400));
					
					$result['start_unix'] = $start;
					$result['end_unix'] = $end;
						
					$result['start_unix_arr'] = $start_arr;
					$result['end_unix_arr'] = $end_arr;
					
					$result['buiten_arr'] = ((($end - $start) / 86400) - ((($start_arr - $start) / 86400) + (($end - $end_arr) / 86400) - (($end_arr - $start_arr) / 86400)));
					
					$result['arrangement'] = $row;
				}
			}
		} else{
			$result = 0;
		}
		
		
		return $result;
		
	}
}