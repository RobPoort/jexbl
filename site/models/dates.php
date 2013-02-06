<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.model');

class JexBookingModelDates extends JModel
{
	
	/**
	 * method om de locatie of locatiesoort op te halen, en de daarbij behorende attributen
	 * @return object
	 */
	function getItems(){
		$app = JFactory::getApplication();
		
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
}