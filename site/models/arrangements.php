<?php
defined('_JEXEC') or die('Restricted Access');

class JexBookingModelArrangements extends JModel
{
	function getItems(){
		
		$this->items = 'testkip';
		
		return $this->items;
	}
}