<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

//JHtml::_('behavior.calendar');

$document =& JFactory::getDocument();



$jq1 = 'jquery-ui-1.8.13.custom.min.js';
$jq2 = 'jquery-1.5.1.min.js';
$jq3 = 'jquery.ui.datepicker.js';
$jq4 = 'jquery.ui.widget.js';
$jq5 = 'jquery.ui.core.js';
$jqcss = 'jquery-ui-1.8.13.custom.css';

$path = 'components/com_jexbooking/js/'; // path to the file
// true means MooTools will load if it is not already loaded
//JHTML::script($filename, $path, true);


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
     $( "#start_date, #end_date" ).datepicker({
            minDate: "+1",
			defaultDate: "+1",
			dateFormat: "dd-mm-yy",
			
			changeMonth: true,
            onSelect: function(dateText, inst){
                var day =
$("#start_date").datepicker("getDate");
            day.setDate(day.getDate()+1);
                $("#end_date").datepicker("option","minDate",day);
            }
        });
     $('#calendar-trigger-arrive').click(function() {
            $('#start_date').datepicker('show');
     });
    });
	

function onlyDays(date) {
return [date.getDay() == 1 || date.getDay() == 5,""];
}
</script>
<form method="post" action="">
	<fieldset class="jexDatesSelect"><legend>Kies uw aankomst- en vertrekdatum:</legend>
		<table class="jbl_form_table">
			<tr>
				<td><label>*Aankomst:&nbsp;</label></td>
				<td><input type="text" id="start_date" name="jbl_form[start_date]" required="required" value="<?php echo $this->default['start_date']; ?>" /></td>
			</tr>
			<tr>
				<td><label>*Vertrek:&nbsp;</label></td>
				<td><input type="text" id="end_date" name="jbl_form[end_date]" required="required" value="<?php echo $this->default['end_date']; ?>" /></td>
			</tr>
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