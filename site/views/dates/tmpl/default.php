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
			<tr><td><label>*Aankomst:&nbsp;</label></td><td><?php echo JHtml::calendar($row->start, 'date[start_date]', 'start_date', '%d-%m-%Y', 'required="required"');?></td></tr>
			<tr><td><label>*Vertrek:&nbsp;</label></td><td><?php echo JHtml::calendar($row->date, 'date[end_date]', 'end_date', '%d-%m-%Y', 'required="required"');?></td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td></td><td><input type="submit" name="sendButton" class="buttonNext" value="VOLGENDE" /></td></tr>
		</table>

	</fieldset>
	
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="1" />
</form>