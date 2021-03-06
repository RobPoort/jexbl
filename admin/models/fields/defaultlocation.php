<?php
defined('_JEXEC') or die('Restricted Access');

//load the form helper and the fieldtype class
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldDefaultLocation extends JFormFieldList
{
	/**
	 * the formfield type
	 * @return string
	 */
	protected $type = 'defaultlocation';
	
	/**
	 * building the list of options
	 */
	protected function getOptions(){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_location');
		$query->select('id,name');
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		$options = array();
		if($rows){
			foreach($rows as $row){
				$options[] = JHtml::_('select.option', $row->id, $row->name);
			}			
		}
		
		return $options;
	}
}