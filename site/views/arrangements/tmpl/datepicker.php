<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

$document =& JFactory::getDocument();

$jq1 = 'jquery-ui-1.8.13.custom.min.js';
$jq2 = 'jquery-1.5.1.min.js';
$jq3 = 'jquery.ui.datepicker.js';
$jq4 = 'jquery.ui.widget.js';
$jq5 = 'jquery.ui.core.js';
$jqcss = 'jquery-ui-1.8.13.custom.css';

$path = 'components/com_jexbooking/js/';
$document->addScript($path.$jq1);
$document->addScript($path.$jq2);
$document->addScript($path.$jq3);
$document->addScript($path.$jq4);
$document->addScript($path.$jq5);
$document->addStyleSheet('components/com_jexbooking/css/start/'.$jqcss);
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
<script language="javascript">
	  $(document).ready(function() {
     $( "#start, #end_date" ).datepicker({
            minDate: "+1",
			defaultDate: "+1",
			dateFormat: "dd-mm-yy",
			beforeShowDay: onlyDays,
			changeMonth: true,
            onSelect: function(dateText, inst){
                var day =
$("#start").datepicker("getDate");
            day.setDate(day.getDate()+1);
                $("#end_date").datepicker("option","minDate",day);
            }
        });
     $('#calendar-trigger-arrive').click(function() {
            $('#start').datepicker('show');
     });
    });
	

function onlyDays(date) {
return [date.getDay() == 1 || date.getDay() == 5,""];
}
</script>
<form method="post" action="">
	<fieldset class="jexDatesSelect"><legend>Kies uw aankomstdatum:</legend>
		<table class="jbl_form_table">
			<tr>
				<td><label>*Aankomst:&nbsp;</label></td>
				<td><input type="text" id="start" name="jbl_arr_start" required="required" value="<?php //echo $this->default['start_date']; ?>" /></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td></td><td><input type="submit" name="sendButton" class="buttonNext" value="VOLGENDE" /></td></tr>
		</table>

	</fieldset>
	
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="0" />
</form>