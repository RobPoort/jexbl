<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.modellist');

class JexBookingModelLocations extends JModelList
{
	public function getListQuery(){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_location as jl');
		$query->join('LEFT', '#__jexbooking_type as jt ON jl.type_id=jt.id');		
		$query->select('jl.id as id, jt.id as type_id, jt.name as type_name, jl.desc, total_number, available_number, jl.published, jl.name');
		
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
			$query->update('#__jexbooking_location');
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