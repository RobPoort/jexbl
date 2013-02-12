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
						//TODO: eerst onderzoeken of er zoiets is als een functie om de diepte van een array vast te stellen, anders als in regel 35
						//wellicht met callbackfunctie, zie hierhttps://www.google.nl/webhp?source=search_app#hl=nl&tbo=d&output=search&sclient=psy-ab&q=php+map+multidimensional+array&oq=php+map+mult&gs_l=hp.1.0.0i30j0i8i30l3.4629.9412.0.17624.12.12.0.0.0.0.81.567.12.12.0...0.0...1c.1.2.hp.vyfXB5MmrjA&pbx=1&bav=on.2,or.r_gc.r_pw.r_cp.r_qf.&bvm=bv.42261806,d.d2k&fp=f85bd9def51c9e80&biw=1920&bih=979
						?>
							<input type="hidden" name="jbl_form[<?php echo $key; ?>][<?php echo $index; ?>]" value="<?php echo $val; ?>" />
						<?php
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