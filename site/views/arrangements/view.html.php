<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class JexBookingViewArrangements extends JView
{
	function display($tpl = null){
		
		$this->app = JFactory::getApplication();
		$this->fixed_days = $this->app->input->get("fixed_days");
		$this->step = (int)$this->app->input->get('step');
		$this->input = $this->app->input;
		$this->items = $this->get('Items');
		$this->itemsByDate = $this->get("itemsByDate");
		//if($this->step = 2){
				$this->attribs = $this->get('PaidItems');				
				$this->extras = $this->get('Extras');
				$this->special_attribs = $this->get('SpecialAttribs');
				$this->item = $this->get('Item');
				$this->nights = $this->get('Nights');
				$this->arrangement = $this->app->getUserState("option_jbl.arr_price");
				$this->attrib_prices_number = $this->app->getUserState("option_jbl.attrib_prices_number");
				$this->attrib_prices_checked = $this->app->getUserState("option_jbl.attrib_prices_checked");
				$this->attrib_prices_special_required = $this->app->getUserState("option_jbl.attrib_prices_special_required");
				$this->attrib_prices_special_checked = $this->app->getUserState("option_jbl.attrib_prices_special_checked");
				
				//extras
				
				$this->attribs_extras_checked = $this->get('SelectedExtras');
				$this->app->setUserState("option_jbl.attribs_extras_checked", $this->attribs_extras_checked);
				$this->attribs_extras_number = $this->app->getUserState("option_jbl.attrib_extras_number");
				
				$this->total_price = $this->app->getUserState("option_jbl.total_price");
				
				//hieronder de percent items en prijs
				$this->total_price_def = $this->app->getUserState("option_jbl.total_price_def");
				$this->percent_items = $this->app->getUserState("option_jbl.percent_items");
				$this->total_percent = $this->app->getUserState("option_jbl.total_price_percent");
				$this->total_percent_price = $this->app->getUserState("option_jbl.total_price_percent_price");
				$this->state = $this->app->getUserState("option_jbl");
				
		//}
		$this->data = $this->app->input->get('jbl_form',null,null);
		
		parent::display($tpl);
	}
}