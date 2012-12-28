<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class JexBookingViewArrangements extends JView
{
	function display($tpl = null){
		
		$items = $this->get('Items');
		
		$pagination = $this->get('Pagination');
		
		//check for errors
		if(count($this->get('Errors'))){
			JError::raiseError(500, implode('br />', $errors));
			return false;
		}
		
		//assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
		
		//set the toolbar
		$this->addToolBar();		
		
		//set the document
		$this->setDocument();
		
		parent::display($tpl);
	}
	/**
	 *	setting the toolbar
	 */
	protected function addToolBar()
	{
		$canDo = JexBookingHelper::getActions();
		JToolBarHelper::title(JText::_('COM_JEXBOOKING_MANAGER_ARRANGEMENTS'), 'jexbooking');
		if($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('arrangement.add', 'JTOOLBAR_NEW');
		}
		if($canDo->get('core.edit'))
		{
			JToolBarHelper::editList('arrangement.edit', 'JTOOLBAR_EDIT');
		}
		if($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'arrangements.delete', 'JTOOLBAR_DELETE');
		}
		if($canDo->get('core.admin'))
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_jexbooking');
		}	
	
	}
	/**
	*	method to set up the document properties
	*	@return	void
	*/
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JEXBOOKING_ADMINISTRATION'));
	}
}