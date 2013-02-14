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
		
		$this->default = $this->setDefault();
		
		$this->calcPrice = $this->app->getUserState("option_jbl.calcPrice");
		
		parent::display($tpl);
	}
	
	/**
	 * methode om default waardes in array $this->default te zetten
	 * @return Array
	 */
	private function setDefault(){
		
		$app = JFactory::getApplication();
		$this->data = null;
		$this->data = $app->input->get("jbl_form", null, null);
		
		if($this->data){
			$default = null;
			$i = 0;
			foreach($this->data as $key=>$value){
				
				if($key == 'checked'){
					
						$default[$key] = $value;
					
				} elseif ($key == 'number'){
					
						$default[$key] = $value;
					
				} else {				
				$default[$key] = $value;
				}
				$i++;
			}
		}
		
		return $default;
	}
}