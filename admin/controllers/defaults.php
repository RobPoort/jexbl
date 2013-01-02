<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controlleradmin');

class JexBookingControllerDefaults extends JControllerAdmin{
	
	/**
	 * * Proxy for getModel
	 */
	public function getModel($name = 'default', $prefix = 'JexBookingModel'){
		
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
			
		return $model;
	}
}