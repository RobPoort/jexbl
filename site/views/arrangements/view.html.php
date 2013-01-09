<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class JexBookingViewArrangements extends JView
{
	function display($tpl = null){
		
		$this->app = JFactory::getApplication();
		$this->step = (int)$this->app->input->get('step');
		$this->input = $this->app->input;
		$this->items = $this->get('Items');
		if($this->step = 2){
				$this->attribs = $this->get('PaidItems');
				$this->extras = $this->get('Extras');
				$this->special_attribs = $this->get('SpecialAttribs');
				$this->item = $this->get('Item');
				$this->arrangement = $this->app->getUserState("option_jbl.arr_price");
				$this->attrib_prices_number = $this->app->getUserState("option_jbl.attrib_prices_number");
				$this->attrib_prices_checked = $this->app->getUserState("option_jbl.attrib_prices_checked");
				$this->total_price = $this->app->getUserState("option_jbl.total_price");
				$this->nights = $this->get('Nights');
		}
		$this->data = $this->app->input->get('jbl_form',null,null);
		
		parent::display($tpl);
	}
}