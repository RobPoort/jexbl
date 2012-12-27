<?php

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JexBookingController extends JController
{
	/**
	*	display task
	*	@return void
	*/
	function display($cachable = false){
		
		//set default view if not set by the task
		JRequest::setVar('view',JRequest::getCmd('view','Locations'));
		
		//call the parent behavior
		parent::display($cachable);
		
		//set the submenu
		JexBookingHelper::addSubmenu('Arrangements');		
	}
}