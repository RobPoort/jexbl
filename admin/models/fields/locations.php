<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormfieldLocations extends JFormFieldList
{
	/**
	 * het formfield type
	 */
	protected $type= 'locations';
	
	/*
	 * lijst met checkboxes met locaties aanmaken
	 * hieruit worden de arrangementen gehaald die bij die locaties horen 
	 */
	
	public function getOptions(){
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__jexbooking_location');
		$query->where('published = 1');
		$db->setQuery((string)$query);
		
		$rows = $db->loadObjectList();
		
		$options = array();
		if($rows){
			foreach($rows as $row){
				$options[] = JHtml::_('select.option', $row->id, $row->name);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
	}
}