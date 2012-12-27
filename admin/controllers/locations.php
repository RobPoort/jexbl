<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controlleradmin');

class JexBookingControllerLocations extends JControllerAdmin{
	
	/**
	 * * Proxy for getModel
	 */
	public function getModel($name = 'location', $prefix = 'JexBookingModel'){
		
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
			
		return $model;
	}
}