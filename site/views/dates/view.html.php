<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class JexBookingViewDates extends JView
{
	function display($tpl = null){
		
		$this->app = JFactory::getApplication();
		
		$this->step = $this->app->input->get('step');
		
		$this->item = $this->get('Item');
		
		$this->overlap = $this->app->getUserState("option_jbl_overlap");
		
		$this->data = $this->app->input->get("jbl_form", null,null);
		
		parent::display($tpl);
	}
}