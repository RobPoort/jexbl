<?php
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$items = $this->items;
//TODO hidden fields
?>
<form action="<?php echo JRoute::_('index.php?option=com_jexbooking'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_JEXBOOKING_ARRANGEMENTS_HEADING_ID'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($this->items); ?>)" />
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ARRANGEMENTS_HEADING_NAME'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ARRANGEMENTS_HEADING_DESC'); ?>
				</th>
				<th width="5">
					<?php echo JText::_('JSTATUS') ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ARRANGEMENTS_HEADING_TYPE_NAME'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ARRANGEMENTS_HEADING_LOCATION_NAME'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ARRANGEMENTS_HEADING_FROM'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ARRANGEMENTS_HEADING_TO'); ?>
				</th>
			</tr>
		</thead>		
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				foreach($items as $i=>$item){ 
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<?php echo $item->id; ?>
				</td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id) ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_jexbooking&task=arrangement.edit&id='.$item->id); ?>">
						<?php echo $item->name; ?>
					</a>
				</td>
				<td>
					<?php echo $item->desc; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'arrangements.', true);?>
				</td>
				<td>
					<?php echo $item->type_name; ?>
				</td>
				<td>
					<?php echo $item->location_name; ?>
				</td>
				<td>
					<?php echo date('j - n - Y', $item->start_date); ?>
				</td>
				<td>
					<?php echo date('j - n - Y', $item->end_date); ?>
				</td>
			</tr>
			<?php }
			?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>