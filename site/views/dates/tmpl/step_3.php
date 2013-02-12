<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

//waardes ophalen
$item = $this->item['locatie'];
$attribs = $this->item['attribs'];
$overlap = $this->overlap;
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
<h2>step_3</h2>
<form method="get" action="">
	<input type="hidden" name="jbl_form[start_date]" value="<?php echo $this->data['start_date']; ?>" />
	<input type="hidden" name="jbl_form[end_date]" value="<?php echo $this->data['end_date']; ?>" />
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="1" />
	<input type="submit" name="buttonprev" class="buttonNext" value="VORIGE" />
</form>
<pre>
	<?php
		var_dump($this->data); 
	?>
</pre>