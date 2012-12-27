<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.view');

class JexBookingViewTypes extends JView
{
	public function display($tpl = null){
		
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
		
		
		//assign data to the view
		$this->items = $items;
		$this->pagination = $pagination;
		
		//set the toolbar
		$this->addToolBar();
		
		parent::display($tpl);
		
		//set the document
		$this->setDocument();
	}
	
	/**
	 *	setting the toolbar
	 */
	protected function addToolBar()
	{
		$canDo = JexBookingHelper::getActions();
		JToolBarHelper::title(JText::_('COM_JEXBOOKING_MANAGER_TYPES'), 'jexbooking');
		if($canDo->get('core.create'))
		{
			JToolBarHelper::addNew('type.add', 'JTOOLBAR_NEW');
		}
		if($canDo->get('core.edit'))
		{
			JToolBarHelper::editList('type.edit', 'JTOOLBAR_EDIT');
		}
		if($canDo->get('core.delete'))
		{
			JToolBarHelper::deleteList('', 'types.delete', 'JTOOLBAR_DELETE');
		}
		if($canDo->get('core.admin'))
		{
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_jexbooking');
		}
	}
	
	protected function setDocument()
	{
		//$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_JEXBOOKING_MANAGER_TYPES'));
	
		
	}
}