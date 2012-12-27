<?php
defined('_JEXEC') or die('Restricted Access');

abstract class JexBookingHelper
{
	//submenu definiëren
	public static function addSubmenu($submenu){
		JSubMenuHelper::addEntry(JText::_('COM_JEXBOOKING_SUBMENU_TYPES'), 'index.php?option=com_jexbooking&view=types', $submenu = 'types');	
		JSubMenuHelper::addEntry(JText::_('COM_JEXBOOKING_SUBMENU_LOCATIONS'),'index.php?option=com_jexbooking&view=locations',$submenu = 'locations');
		JSubMenuHelper::addEntry(JText::_('COM_JEXBOOKING_SUBMENU_ATTRIBUTES'), 'index.php?option=com_jexbooking&view=attributes', $submenu = 'attributes');
		JSubMenuHelper::addEntry(JText::_('COM_JEXBOOKING_SUBMENU_ARRANGEMENTS'),'index.php?option=com_jexbooking&view=arrangements',$submenu = 'arrangements');
		
		
		//set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-jexbooking'.'{background-image:url(../media/com_jexbooking/images/jexBooking-48x48.png);}');
		if($submenu == 'categories')
		{
			$document->setTitle(JText::_('COM_JEXBOOKING_ADMINISTRATION_CATEGORIES'));
		}
	}
	
	//get the actions
	public static function getActions($messageId = 0)
	{
		jimport('joomla.access.access');
		$user = JFactory::getUser();
		$result = new JObject;
		
		if(empty($messageId))
		{
			$assetName = 'com_jexbooking';
		} else
		{
			$assetName = 'com_jexbooking.message.'.(int)$messageId;
		}
		
		$actions = JAccess::getActions('com_jexbooking', 'component');
		foreach($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}
		return $result;
	}
}