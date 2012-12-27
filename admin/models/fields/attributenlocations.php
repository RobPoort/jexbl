<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('Checkboxes');

class JFormFieldAttributenLocations extends JFormField
{
	/**
	 * het formfieldtype
	 */
	protected $type = 'attributenlocations';
	
	public function getInput(){
		
		$this->id = JRequest::getInt('id');
		
		
		//eerst de parent_id ophalen
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_location');
		$query->select('type_id');
		$query->where('id = '.$this->id);
		$db->setQuery($query);
		$parent_id = (int)$db->loadResult();
		
		//nu de lijst vullen met bestaande attribs
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_xref_attributes as jxa');
		$query->select('attribute_id');
		$query->where('location_id = '.$this->id.' OR type_id = '.$parent_id);
		$db->setQuery($query);
		//TODO ook de xrefs van de parent moeten opgehaald worden => dat is veld type_id in #__jexbooking_location
		//TODO wellicht alleen attributes van parent ophalen indien locatie is new? (dwz, id is leeg?)=>werkt niet, want nieuw item heeft ook nog geen parent
		$xrefs = $db->loadResultArray();
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->from('#__jexbooking_attributes as ja');		
		$query->select('*');		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		foreach($rows as $row){
			if(in_array($row->id, $xrefs)){
				$row->checked = 'checked="checked"';
			} else {
				$row->checked = '';
			}
		}
		//$form = array();
		$form = '<fieldset class="checkboxes" id="jform_attribute_id"><ul class="adminform">';
		foreach($rows as $row){			
			$form .= '<li><input type="checkbox" name="jform[attribute_id][]" '.$row->checked.' value="'.$row->id.'" />'.$row->name.'</li>';
		}
		$form .= '</ul></fieldset>';
		
		
		return $form;
		
	}
}