<?php
	/**
	*	admin part
	*/
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.tooltip');
JHTML::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_jexbooking&view=type&layout=edit&id='.(int)$this->item->id); ?>" method="post" name="adminForm" id="type-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JEXBOOKING_TYPE_DETAILS'); ?></legend>
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset('details') as $field) : ?>
					<li><?php echo $field->label;echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_JEXBOOKING_TYPE_ATTRIBUTES'); ?></legend>
			
			<?php foreach($this->form->getFieldset('attributen') as $field) : ?>
			<?php //echo $field->label; ?><br />			
			<?php echo $field->input; ?>
			<?php endforeach; ?>
		</fieldset>
	</div>	
	<div class="width-60 fltlft">
		<fieldset class="adminform">			
			<ul class="adminformlist">
				<?php foreach($this->form->getFieldset('publish') as $field) : ?>
					<li><?php echo $field->label;echo $field->input; ?></li>
				<?php endforeach; ?>
			</ul>
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
							<li><?php echo $field->label;echo $field->input; ?></li>
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
		<input type="hidden" name="task" value="type.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>