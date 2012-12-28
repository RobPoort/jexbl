<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.modellist');

class JexBookingModelArrangements extends JModelList
{
	//methode om de query op te bouwen voor lijst van arrangementen
	public function getListQuery(){
	
		//nieuw query object aanmaken
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_arrangements as arr');
		$query->join('LEFT', '#__jexbooking_type as jty ON arr.type_id=jty.id');
		$query->join('LEFT', '#__jexbooking_location as jl ON arr.location_id=jl.id');
		$query->select('arr.id as id, arr.name as name, arr.desc, arr.start_date as start_date, arr.end_date as end_date, arr.nights as nights, arr.price as price, arr.is_pa as is_pa, arr.required as required, arr.published as published, jty.name as type_name, jl.name as location_name');
		
		//$query->select('arr.desc');
		
		return $query;
	}
	function publish(){
	
		$data = JRequest::get('post');
		$value = JRequest::getCmd('task');
		$ids = JRequest::getVar('cid', array(), 'post', 'array');
		$where = array();
		foreach($ids as $id){
			$where[] = ' id='.(int)$id;
		}
		switch($value){
			case 'publish':
				$value = 1;
				break;
			case 'unpublish';
				$value = 0;
				break;
			default:
				$value = 1;
				break;
		}
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();
		$query->update('#__jexbooking_arrangements');
		$query->set('published = '.(int)$value);
		$query->where($where, ' OR ');
		$db->setQuery((string)$query);
		if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        	return false;
        } else {
        	return true;
		}	
		 
	}
}