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
		$query->select('*');
		
		if(!$this->choose){
		$query->where('type_id='.$this->type_id.' AND published=1');
		} else{
			$query->where('location_id='.$this->location_id.' AND published=1');
		}
		
		$db->setQuery($query);
		$this->items = $db->loadObjectList();
		
		return $this->items;
	}
	/**
	 * method om gegevens van gekozen arrangement op te halen
	 * @return object
	 */
	function getItem(){
		//eerst arr_id ophalen uit state
		$arr_id = (int)JFactory::getApplication()->getUserState("option_jbl.arr_id");
		
		if($arr_id){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->from('#__jexbooking_arrangements');
			$query->where('id='.$arr_id);
			$query->select('*');
			$db->setQuery($query);
			$row = $db->loadObject();
			
			return $row;
		}
	}
	/*
	 * methode om de items voor stap 2 op te halen, nl. alle attribs met prijs, en de arrangementen mét prijs
	 */
	function getPaidItems(){
		
		//eerst de arr_id ophalen uit de userState
		$arr_id = (int)JFactory::getApplication()->getUserState("option_jbl.arr_id");
		
		//attrib_ids ophalen uit xref tabel
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);		
		$query->from('#__jexbooking_xref_attributes as jxa');
		$query->select('attribute_id');
		$query->where('arr_id='.$arr_id);
		$db->setQuery($query);
		$attrib_ids = $db->loadObjectList();
		
		//nu de attribs zelf ophalen met als condities published=1 en has_price=1
		//TODO kijken of hier een $query->clear() volstaat en kijken of join hier niet beter is
		
		$rows = array();
		foreach ($attrib_ids as $attrib_id){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->from('#__jexbooking_attributes as ja');
			$query->where('ja.published=1 AND ja.has_price=1 AND ja.id='.$attrib_id->attribute_id);
			$query->select('*');
			$db->setQuery($query);
			$row = $db->loadObject();
			if($row){
			$rows[] = $row;
			}
		}
		
		return $rows;
	}	
	
	function getSelectedAttribs($data){
		
		//de $key's in $data zijn de attrib_ids
		foreach($data as $key=>$value){
			$arr_ids[] = 'id='.(int)$key;
		}
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_attributes');
		$query->select('*');
		$query->where((array)$arr_ids, ' OR ');
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		echo '<pre>';
		foreach($rows as $row){
			//echo $row->id.'&nbsp;'.$row->name.'&nbsp;'.$row->price.'&nbsp;'.$row->has_number.'<br />';
		}
		echo '</pre>';
		return $rows;
	}
}