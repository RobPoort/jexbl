<?php
	/**
	*	admin part
	*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jexbooking&layout=edit&id='.(int)$this->item->id); ?>" method="post" name="adminForm" id="location-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JEXBOOKING_LOCATION_DETAILS'); ?></legend>
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset('details') as $field) : ?>
					<li><?php echo $field->label;echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JEXBOOKING_LOCATION_ATTRIBUTES'); ?></legend>
			<?php foreach($this->form->getFieldset('attributen') as $field) : ?>
			<?php //echo $field->label; ?>			
			<?php echo $field->input; ?>
			<?php endforeach; ?>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<?php
			echo JHtml::_('sliders.start', 'jexbooking-slider');
			foreach($params as $name=>$fieldset) :
				echo JHtml::_('sliders.panel', JText::_($fieldset->label), $name.'-params');	
					if(isset($fieldset->description) && trim($filedset->description)) :
		?>
					<p class="tip"><?php echo $this->escape(JText::_($fieldset->description)); ?></p>
		<?php
					endif;
		?>
				<fieldset class="panelform">
					<ul class="adminformlist">
						<?php
							foreach($this->form->getFieldset($name) as $field) :
						?>
							<li><?php echo $field->label;echo $filed->input; ?></li>
						<?php
							endforeach;
						?>
					</ul>
				</fieldset>
		<?php
			endforeach;
			echo JHtml::_('sliders.end');
		?>
	</div>
	<div>
		<input type="hidden" name="task" value="location.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>