<?php
defined('_JEXEC') or die('Restricted Access');

class JexBookingModelArrangements extends JModel
{
	/**
	 * method to get the arrangemenst
	 * @return array $items
	 */
	
	function getItems(){
		
		//eerst de locatie_id ophalen uit de params
		$this->location_id = JFactory::getApplication()->input->get('location_id');
		$this->type_id = JFactory::getApplication()->input->get('type_id');
		$this->choose = JFactory::getApplication()->input->get('choose');
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->from('#__jexbooking_arrangements as ja');
		$query->select('id, name, start_date, end_date');
		
		if(!$this->choose){
		$query->where('type_id='.$this->type_id.' AND published=1');
		} else{
			$query->where('location_id='.$this->location_id.' AND published=1');
		}
		
		$db->setQuery($query);
		$this->items = $db->loadObjectList();
		
		return $this->items;
	}
}