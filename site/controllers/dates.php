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
				$this->checkForArr();
				break;
			case 2:
				$this->app->input->set('layout', 'step_3');
				break;
			case 3:
				$this->app->input->set('layout', 'step_4');
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
	
	/**
	 * method om te bepalen of aankomst- of vertrekdatum binnen een arrangement vallen
	 * aan de hand van $locatie_id, $start_date en $end_date
	 * @return	object
	 */
	public function checkForArr_oud(){
		
		$this->app = JFactory::getApplication();
		
		//eerst start en enddate uit form halen + $location_id
		$date = $this->app->input->get("date",null,null);
		
		$start_date = $date['start_date'];
		$end_date = $date['end_date'];
		$location_id = $this->app->input->get('location_id');
		
		$model = $this->getModel('dates');
		$arrangements = $model->getArrangements($location_id,$start_date,$end_date);
		
		$this->app->setUserState("option_jbl_overlap", null);
		if($arrangements){		
			$this->app->setUserState("option_jbl_overlap", $arrangements);
		}
	}
	public function checkForArr(){
	
		$this->app = JFactory::getApplication();
	
		//eerst start en enddate uit form halen + $location_id
		$date = $this->app->input->get("date",null,null);
	
		$startDate = new DateTime($date['start_date']);
		$endDate = new DateTime($date['end_date']);
		$locationId = $this->app->input->get('location_id');
	
		$model = $this->getModel('dates');
		$arrangements = $model->getArrangements($locationId,$startDate,$endDate);
	
		$this->app->setUserState("option_jbl_overlap", null);
		if($arrangements){
			$this->app->setUserState("option_jbl_overlap", $arrangements);
		}
	}
}