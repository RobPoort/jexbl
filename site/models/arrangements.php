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
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->from('#__jexbooking_arrangements as ja');
		$query->select('*');
		$query->where('location_id='.$this->location_id.' AND published=1');
		$db->setQuery($query);
		$this->items = $db->loadObjectList();
		
		return $this->items;
	}
}