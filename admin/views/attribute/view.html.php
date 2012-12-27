<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.application.component.view');

class JexBookingViewAttribute extends JView{
	
public function display($tpl = null){
		
		$form = $this->get('Form');
		$item = $this->get('Item');
		$script = $this->get('Script');
		
		$pagination = $this->get('Pagination');
		
		//check of er fouten zijn
		if(count($this->get("Errors"))){
			JError::raiseError(500,implode('<br />', $errors));
			return false;
		}
		
		//assign data tot the view
		$this->item = $item;
		$this->form = $form;
		$this->script = $script;
		
		//toolbar toevoegen
		$this->addToolbar();
		
		//set the template
		parent::display($tpl);
		
		//set the document
		$this->setDocument();
	}
	
	/**
	 *	setting the toolbar
	 */
	protected function addToolBar()
	{
		JRequest::setVar('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = $this->item->id == 0;
		$canDo = JexBookingHelper::getActions($this->item->id);
		JToolBarHelper::title($isNew ? JText::_('COM_JEXBOOKING_MANAGER_ATTRIBUTE_NEW')
		: JText::_('COM_JEXBOOKING_MANAGER_ATTRIBUTE_EDIT'), 'jexbooking');
		//built the actions for new and existings records
		if($isNew)
		{
			//for new records, check the create permission
			if($canDo->get('core.create'))
			{
				JToolBarHelper::apply('attribute.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('attribute.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom('attribute.save2new', 'save-new.png', 'save-new_f2.png',
				'JTOOLBAR_SAVE_AND_NEW', false);
			}
			JToolBarHelper::cancel('attribute.cancel', 'JTOOLBAR_CANCEL');
		} else
		{
			if($canDo->get('core.edit'))
			{
				//we can save the new record
				JToolBarHelper::apply('attribute.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('attribute.save', 'JTOOLBAR_SAVE');
	
				//we can save this record, but check the create permission to see
				//if we can return to make a new one
				if($canDo->get('core.create'))
				{
					JToolBarHelper::custom('attribute.save2new', 'save-new.png', 'save-new_f2.png',
					'JTOOLBAR_SAVE_AND_NEW', false);
				}
			}
			if($canDo->get('core.create'))
			{
				JToolBarHelper::custom('attribute.save2copy', 'save-copy.png', 'save-copy_f2.png',
				'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel('attribute.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	/**
	 *	method to set up the document properties
	 *	@return	void
	 */
	protected function setDocument()
	{
		$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_JEXBOOKING_ATTRIBUTE_CREATING') : JTEXT::_('COM_JEXBOOKING_ATTRIBUTE_EDITING'));
	
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_jexbooking/views/attribute/submitbutton.js");
		JText::script('COM_JEXBOOKING_JEXBOOKING_ERROR_UNACCEPTABLE');
	}
}