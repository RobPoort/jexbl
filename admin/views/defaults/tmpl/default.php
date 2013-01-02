<?php
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$items = $this->items;

?>
<form action="<?php echo JRoute::_('index.php?option=com_jexbooking'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_JEXBOOKING_DEFAULT_PRICES_HEADING_ID'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($this->items); ?>)" />
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_DEFAULT_PRICES_HEADING_NAME'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_DEFAULT_PRICES_HEADING_START_DATE'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_DEFAULT_PRICES_HEADING_END_DATE'); ?>
				</th>
				<th width="5">
					<?php echo JText::_('JSTATUS') ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_DEFAULT_PRICES_HEADING_LOCATION_NAME'); ?>
				</th>
			</tr>
		</thead>		
		<tfoot>
			<tr>
				<td colspan="7">
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
					<a href="<?php echo JRoute::_('index.php?option=com_jexbooking&task=default.edit&id='.$item->id); ?>">
						<?php echo $item->name; ?>
					</a>
				</td>
				<td>
					<?php echo $item->start_date; ?>
				</td>
				<td>
					<?php echo $item->end_date ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'defaults.', true);?>
				</td>
				<td>
					<?php echo $item->location_name; ?>
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