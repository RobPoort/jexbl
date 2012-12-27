<?php
defined('_JEXEC') or die('Rot op');

//Access check
If(!JFactory::getUser()->authorize('core.manage', 'com_jexbooking')){
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//requier helper file
JLoader::register('JexBookingHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'jexbooking.php');

jimport('joomla.application.component.controller');

//get instance of controller
$controller = JController::getInstance('JexBooking');

//perform the requested task
$controller->execute(JRequest::getCmd('task'));

//redirect if set by the controller
$controller->redirect();