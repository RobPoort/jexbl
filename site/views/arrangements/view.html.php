<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class JexBookingViewArrangements extends JView
{
	function display($tpl = null){
		
		$this->app = JFactory::getApplication();
		$this->step = (int)$this->app->input->get('step');
		$this->input = $this->app->input;
		$this->items = $this->get('Items');
		if($this->step >= 1){
				$this->attribs = $this->get('PaidItems');
				$this->item = $this->get('Item');		
		}	
		
		parent::display($tpl);
	}
}