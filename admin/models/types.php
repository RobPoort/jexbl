<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.modellist');

class JexBookingModelTypes extends JModelList
{
	public function getListQuery(){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_type');
		$query->select('*');
		
		return $query;
	}
}