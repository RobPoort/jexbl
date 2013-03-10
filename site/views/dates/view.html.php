<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class JexBookingViewDates extends JView
{
	function display($tpl = null){
		
		$this->app = JFactory::getApplication();
		
		$this->step = $this->app->input->get('step');
		
		$this->choose = $this->app->input->get('choose');
		
		if($this->choose == 0){
			$this->locations = $this->get('Locations');
		}
		
		$this->item = $this->get('Item');	
		
		$this->app->setUserState("option_jbl.itemAttribs", null);
		
		$this->app->setUserState("option_jbl.itemAttribs", $this->item['attribs']);
		
		$this->overlap = $this->app->getUserState("option_jbl_overlap");
		
		$this->data = $this->app->input->get("jbl_form", null,null);
		
		$this->default = $this->setDefault();		
		
		$this->calcPrice = $this->app->getUserState("option_jbl.calcPrice");
		
		$this->prices = $this->app->getUserState("option_jbl.prices");
		
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
			if(isset($this->data['naw'])){
				$app->setUserState("option_jbl.naw", null);
				$app->setUserState("option_jbl.naw", $this->data['naw']);
			}
			if(isset($this->data)){
				$app->setUserState("option_jbl.data", $this->data);
			}			
		}
		
		//TODO check op number_pp
		//number_pp aanpassen aan min- en maxwaarde voor de papillon
		if($default['number_pp']){
			if($default['number_pp'] < 2){
				$default['number_pp'] = 2;
			} elseif($default['number_pp'] > 6){
				$default['number_pp'] = 6;
			}
		}
		return $default;
	}
}