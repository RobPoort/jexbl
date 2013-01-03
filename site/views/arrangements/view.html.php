<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class JexBookingViewArrangements extends JView
{
	function display($tpl = null){
		
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		$this->items = $this->get('Items');
		$this->rob = $this->input->get('location_id');
		
		parent::display($tpl);
	}
}