<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

//get an instance of the controller
$controller = JController::getInstance('JexBooking');

//perform the request task
$controller->execute(JRequest::getCmd('task'));

//redirect if set by the controller
$controller->redirect();