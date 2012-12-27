<?php
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$items = $this->items;

//TODO hidden fields
?>
<form action="<?php echo JRoute::_('index.php?option=com_jexbooking&view=attributes'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5">
					<?php echo JText::_('COM_JEXBOOKING_ATTRIBUTES_HEADING_ID'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($this->items); ?>)" />
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ATTRIBUTES_HEADING_NAME'); ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ATTRIBUTES_HEADING_DESC'); ?>
				</th>
				<th width="5">
					<?php echo JText::_('JSTATUS') ?>
				</th>
				<th>
					<?php echo JText::_('COM_JEXBOOKING_ATTRIBUTES_HEADING_PRICE'); ?>
				</th>
				<th width="5">
					<?php echo JText::_('COM_JEXBOOKING_ATTRIBUTES_HEADING_HAS_PRICE'); ?>
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
					<a href="<?php echo JRoute::_('index.php?option=com_jexbooking&task=attribute.edit&id='.$item->id); ?>">
						<?php echo $item->name; ?>
					</a>
				</td>
				<td>
					<?php echo $item->desc; ?>
				</td>
				<td class="center">
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'attributes.', true); ?>
				</td>
				<td>
					<?php 
					if(!$item->has_price){
						echo 'nvt.';
					} else{
						echo '&euro;&nbsp;'.$item->price;
					}
					?>
				</td>
				<td class="center">
					<?php
						//TODO dit zou nog naar het model moeten verhuizen, qua mvc
						if($item->has_price){
							echo JText::_('COM_JEXBOOKING_STATEMENT_HAS_PRICE_YES');
						} else{
							echo JText::_('COM_JEXBOOKING_STATEMENT_HAS_PRICE_NO');
						}
						//TODO hieronder kan, maar dan moet er nog een method geschreven worden. Eerst afwachten of we dit willen
						//echo JHtml::_('jgrid.published', $item->has_price, $i, 'attributes', true);
					?>
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