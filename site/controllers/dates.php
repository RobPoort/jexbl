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
				$this->calcPrice = $this->calculatePrice();
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
	 * aan de hand van $locatieId, $startDate en $endDate
	 * @return	object
	 */	
	public function checkForArr(){
	
		$this->app = JFactory::getApplication();
	
		//eerst start en enddate uit form halen + $location_id
		$date = $this->app->input->get("jbl_form",null,null);
	
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
	/**
	 * method om de prijsberekening te maken
	 * @return Object
	 */
	//TODO: de percentageprijzen in aparte functie?
	private function calculatePrice(){
		
		$app = JFactory::getApplication();
		
		$this->data = $app->input->get("jbl_form", null, null);
		
		//indien $this->overlap == null, dan geen arrangement van toepassing, anders arrangement prijs ophalen in function calcArr(), want andere gegevens
		//TODO: moet opgevraagd worden vóór percentage berekend gaat worden, indien van toepassing
		$this->overlap = $app->getUserState("option_jbl_overlap");
		
		if($this->overlap){
			$this->arrPrice = $this->calcArr($this->data);
		}
		
		$app->setUserState("option_jbl.calcPrice", null);
		
		$app->setUserState("option_jbl.calcPrice", $this->overlap);
		
		$this->calcPrice = $app->getUserState("option_jbl.calcPrice");
		
		
		return $this->calcPrice;
	}
	/**
	 * method om arrangementskosten op te vragen, indien er overlap is($this->overlap != null)
	 * @param Object $data
	 */
	private function calcArr($data){
		
		
		return $this->arrPrice;
	}
}