<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class JexBookingController extends JController
{
	function display($cachable = false){
		
		
		$app = JFactory::getApplication();
		
		//bedoeling is om userState te legen, iedere keer als 'vers' op het boekings menuItem komt.		
		//TODO: dat moet beter kunnen, maar het is toch nodig om aan begin van proces alle extra's uit userState te halen ivm het vullen van reeds ingevulde waarden in form
		if(!$step = $app->input->get('step') || $step == 0){
			
			$app->setUserState("option_jbl", null);
			
		}
		
		parent::display($cachable);
	}
}