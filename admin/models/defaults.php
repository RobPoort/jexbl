<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.modellist');

class JexBookingModelDefaults extends JModelList
{
	public function getListQuery(){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_default_prices as jdp');
		$query->join('LEFT', '#__jexbooking_location as jl ON jdp.location_id=jl.id');		
		$query->select('jdp.id as id, jdp.location_id as location_id, jdp.name as name, start_date, end_date, min_price, is_pn_min_price, extra, is_pn_extra, jdp.published as published, jl.name as location_name');
		//$query->select('*');
		
		return $query;
	}
	function publish()
		{
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
			$query->update('#__jexbooking_default_prices');
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