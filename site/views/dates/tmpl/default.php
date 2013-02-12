<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

JHtml::_('behavior.calendar');


?>
<script>
window.addEvent('domready' function(){
	$('form.form-validate').addEvent('submit', function(evt){
		// PREVENT FORM FROM BEING SUBMITTED
		evt.preventDefault();
		var form = evt.target;
		if(! document.formvalidator.isValid(form) ){
			// DISPLAY ERROR MESSAGE HERE
			return false;
		}
		form.submit();
	});
});
</script>

<form method="post" action="">
	<fieldset class="jexDatesSelect"><legend>Kies uw aankomst- en vertrekdatum:</legend>
		<table class="jbl_form_table">
			<tr><td><label>*Aankomst:&nbsp;</label></td><td><?php echo JHtml::calendar($this->default['start_date'], 'jbl_form[start_date]', 'start_date', '%d-%m-%Y', 'required="required"');?></td></tr>
			<tr><td><label>*Vertrek:&nbsp;</label></td><td><?php echo JHtml::calendar($this->default['end_date'], 'jbl_form[end_date]', 'end_date', '%d-%m-%Y', 'required="required"');?></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td></td><td><input type="submit" name="sendButton" class="buttonNext" value="VOLGENDE" /></td></tr>
		</table>

	</fieldset>
	
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="1" />
</form>
<div class="clear"></div>
<pre>
	<?php
		//TODO: var_dump verwijderen
	
		echo '<h2>$this->data:</h2>';
		var_dump($this->data);
		echo '<h2>$this->default:</h2>';
		var_dump($this->default);
	?>
</pre>