<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.modeladmin');

class JexBookingModelAttribute extends JModelAdmin
{
	/**
	 *	method to override to check if you can edit an existing field
	 *	@param	array	$data	an array of input data
	 *	@param	string	$key	the name of the key for the primary key
	 *
	 *	@return	boolean
	 *	@since 2.5
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		//check specific edit permissions, then general permissions
		return JFactory::getUser()->authorise('core.edit', 'com_jexbooking.message.'.
				((int) isset($data[$key]) ? $data[$key] : 0))
				or parent::allowEdit($data, $key);
	}
	/**
	 *	Returns a reference to a Table object, always creating it
	 *
	 *	@param	type	the table type to instantiate
	 *	@param	string	a prefix for the table class name. Optional
	 *	@param	array	configuration array for the model. Optional
	 *	@param	JTable	a database object
	 */
	public function getTable($type = 'Attributes', $prefix = 'JexBookingTable', $config = array())
	{
		return JTable::getInstance($type,$prefix,$config);
	}
	/**
	 *	Method to get the record from
	 *	@param	array	$data	Data for the form
	 *	@param	boolean	$loadData true if the form is to load its own data(default case), false if not
	 *	@param	mixed	a JForm object on success, false on failure
	 */
	public function getForm($data = array(), $loadData = true)
	{
		//get the form
		$form = $this->loadForm('com_jexbooking.attribute', 'attribute', array('control'=>'jform', 'load_data'=>$loadData));
		if(empty($form))
		{
			return false;
		}
		return $form;
	}
	/**
	 *	Method to get the script that has to be included in the form
	 *
	 *	@return	string	script files
	 */
	//TODO hier nog naar kijken, ivm validatie (velden)
	public function getScript()
	{
		return 'administrator/components/com_jexbooking/models/forms/attribute.js';
	}
	/**
	 *	method to get the data to be inserted in the form
	 *
	 *	@return	mixed	the data for the form
	 */
	protected function loadFormData()
	{
		//check the session for previously entered form data
		$data = JFactory::getApplication()->getUserState('com_jexbooking.edit.attribute.data', array());
		if(empty($data))
		{
			$data = $this->getItem();
		}
		return $data;
	}
}