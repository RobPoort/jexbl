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

<form method="post" action="">
	<fieldset class="jbl_form" id="jbl_has_number"><legend>Uw prijsberekening:</legend>
		<pre>
			<?php
				var_dump($this->calcPrice); 
			?>
		</pre>
	</fieldset>
	<?php
		if($this->data){
			
			foreach ($this->data as $key=>$value){
				if(!is_array($value)){
					?>
					<input type="hidden" name="jbl_form[<?php echo $key; ?>]" value="<?php echo $value; ?>" />
					<?php
				} else {
					$i = 0;
					foreach($value as $index=>$val){
						if(!is_array($val)){
							?>
							<input type="hidden" name="jbl_form[<?php echo $key; ?>][<?php echo $index; ?>]" value="<?php echo $val; ?>" />
							<?php
						} else{
							foreach ($val as $key3=>$val3)
								if(!is_array($val3)){
								?>
								<input type="hidden" name="jbl_form[<?php echo $key; ?>][<?php echo $index; ?>][<?php echo $key3; ?>]" value="<?php echo $val3; ?>" />
								<?php
							} else{
								foreach($val3 as $key4=>$val4){
								?>
									<input type="hidden" name="jbl_form[<?php echo $key; ?>][<?php echo $index; ?>][<?php echo $key3; ?>][<?php echo $key4; ?>]" value="<?php echo $val4; ?>" />
								<?php
								}
							}
						}
						$i++;
					}
				}
				
			}
		}
	?>	
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="1" />
	<input type="submit" name="buttonprev" class="buttonNext" value="VORIGE" />
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