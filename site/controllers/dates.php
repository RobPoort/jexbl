<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JexBookingControllerDates extends JController
{
	function display($cachable = false){		
		
		parent::display($cachable);
	}
	
	/**
	 * method om de stappen in het formulier te definiëren en de tmpl layout toe te wijzen
	 * @ return empty
	 */
	public function setStep(){
		$this->app = JFactory::getApplication();
		$step = $this->app->input->get('step');
		
		switch ($step){
			case 1:
				$this->app->input->set('layout', 'step_2');
				break;
			case 2:
				$this->app->input->set('layout', 'step_3');
				break;
			default:
				$this->app->input->set('layout', 'default');
				break;
		}
		
		$this->display();
	}
	
	/**
	 * method om het model op te halen voor de prijsberekening
	 * @return string
	 */
	public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, array('ignore_request' => false));
	}
	
}