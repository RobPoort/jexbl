<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.controllerform');

class JexBookingControllerType extends JControllerForm
{
	public function save(){
		$data = JRequest::getVar("jform");
		
		$item_id = $data['id'];
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->delete('#__jexbooking_xref_attributes')->where('type_id = '.$item_id);		
		$db->setQuery($query);
		$db->query();
		$db->clear();
		
		
		foreach($data['attribute_id'] as $attribute_id){
			$db = JFactory::getDbo();
			$db->clear();
			$query = $db->getQuery(true);
			$query->insert('#__jexbooking_xref_attributes');
			$query->columns('type_id, attribute_id');
			$query->values("$item_id, $attribute_id");
			$db->setQuery($query);
			$db->query();			
		}		
		
		parent::save();		
	}
}