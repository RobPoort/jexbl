<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

$this->app = JFactory::getApplication();
$this->step = (int)$this->app->input->get('step');
$arrangement = $this->arrangement;
$attribs_number = $this->attrib_prices_number;
$attribs_checked = $this->attrib_prices_checked;
$attribs_special_required = $this->attrib_prices_special_required;
$attribs_special_checked = $this->attrib_prices_special_checked;
$attribs_extras_checked = $this->attribs_extras_checked;
$attribs_extras_number = $this->attribs_extras_number;
$final = $this->app->input->get("final",null,null);

$mailfrom	= $this->app->getCfg('sitename');
$state = $this->state;
$form = $this->app->input->get("jbl_form",null,null);
$naw = $form['naw'];
//$attribs_extras_checked = $form['extras']['checked'];
$this->app->setUserState('option_jbl.naw', $naw);
$this->app->setUserState("option_jbl.attrib_extras_checked", $attribs_extras_checked);

//TODO bovenstaande filteren, zitten nog overbodige dingen van oude bedankt pagina in
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
<pre><?php var_dump($final); ?></pre>
<h1>Overzicht:</h1>
<div class="jbl_prijsberekening" id="jbl_prijsberekening">
	
		<fieldset class="jbl_form"><legend>Uw prijsberekening:</legend>
		
			<table class="jbl_form_table">
				<tr>
					<td>
						Periode:
					</td>
					<td>
						<?php echo $final['start_date']; ?>
					</td>
					<td>
						<?php echo $final['end_date']; ?>
					</td>
					
				</tr>
				<tr>
					<td>
						Personen:
					</td>
					<td>
						<?php echo $final['number_pp']; ?>
					</td>
				</tr>
				<?php if(isset($final['arrangement']) && !empty($final['arrangement'])) : ?>
					<?php foreach($final['arrangement'] as $item) : ?>
						<tr>
							<td>
								<?php echo $item['name'];?>
							</td>
							
							<td>
								&euro;&nbsp;<?php echo number_format($item['price'], 2, ',', '.'); ?>
							</td>
							<td>&nbsp;</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if(isset($final['stayPeriod']) && !empty($final['stayPeriod'])) : ?>
					<?php foreach($final['stayPeriod'] as $item) : ?>
						<tr>
							<td>
								<?php echo $item['nachten'];?>&nbsp;nachten&nbsp;<?php echo $item['name']; ?>
							</td>
							
							<td>
								&euro;&nbsp;<?php echo number_format($item['stayPeriodPrice'], 2, ',', '.'); ?>
							</td>
							<td>&nbsp;</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>			
				<tr>
					<td>&nbsp;</td>				
					<td style="text-align:left;">+</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>Subtotaal</td>				
					<td style="text-align:left;">&euro;&nbsp;<?php echo number_format($final['subTotalPrice'], 2, ',', '.')?></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<?php if(isset($final['calcAttribs']) && !empty($final['calcAttribs'])) : ?>
					<?php foreach($final['calcAttribs'] as $item) : ?>
						<tr>
							<td>
								<?php echo $item['name']; ?>
							</td>
							<td>
								&euro;&nbsp;<?php echo number_format($item['price'], 2, ',', '.'); ?>
							</td>
							<td>&nbsp;</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>				
					<td style="text-align:left;">+</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		<form method="post" action="">
			<input type="submit" name="buttonedit" value="WIJZIG" class="buttonNext" />			
			<input type="hidden" name="step" value="1" />
			<input type="hidden" name="task" value="arrangements.setStep" />
			
		</form>
		</fieldset>
	</div>
	<fieldset class="jbl_form"><legend>Uw gegevens:</legend>
		<table class="jbl_form_table">
			<tr>
				<td class="naw_left">dhr/mw:</td>
				<td><?php echo $naw['surname'].'&nbsp;'.$naw['name']; ?></td>
			</tr>
			<tr>
				<td class="naw_left">adres:</td>
				<td><?php echo $naw['street'].'&nbsp;'.$naw['street_number']; ?></td>
			</tr>
			<tr>
				<td class="naw_left">&nbsp;</td>
				<td><?php echo $naw['zipcode'].'&nbsp;'.$naw['city']; ?></td>
			</tr>
			<tr>
				<td class="naw_left">telefoon:</td>
				<td><?php echo $naw['phone']; ?></td>
			</tr>
			<tr>
				<td class="naw_left">e-mail:</td>
				<td><?php echo $naw['mail']; ?></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<?php $i = 1; ?>
			<?php foreach($naw['birthdate'] as $item) : ?>
			<tr>
				<td class="naw_left">Geb. datum:</td>
				<td><?php echo $item; ?></td>
			</tr>
			<?php $i++; ?>
			<?php endforeach; ?>
		</table>
	<form method="post" action="">
			<input type="submit" name="buttonedit" value="WIJZIG" class="buttonNext" />			
			<input type="hidden" name="step" value="2" />
			<input type="hidden" name="task" value="arrangements.setStep" />			
			<input type="hidden" name="jbl_form[state_check]" value="1" />
			<input type="hidden" name="noCalc" value="1" />
	</form>
	</fieldset>
	<?php
	 if($attribs_extras_checked || $attribs_extras_number){
	?>
	<fieldset class="jbl_form"><legend>Uw extra wensen:</legend>
		<table class="jbl_form_table">
			<?php
				if($attribs_extras_checked){
					foreach($attribs_extras_checked as $item){
						?>
						<tr>
							<td><?php echo $item->name; ?></td>
							<td>
								<?php
									if($item->persons > 1){
										echo '(&aacute;&nbsp;'.$item->persons.'&nbsp;personen)';
									} 
								?>
							</td>
						</tr>
						<?php
					}
				}
				if($attribs_extras_number){
					foreach($attribs_extras_number as $item){
						?>
						<tr>
							<td><?php echo $item->name; ?></td>
							<td>(<?php echo $item->number; ?>&nbsp;maal)</td>
						</tr>
						<?php
					}
				} 
			?>
			<tr><td colspan="2">&nbsp;</td></tr>
		</table>
		<form method="post" action="">
			<input type="submit" name="buttonedit" value="WIJZIG" class="buttonNext" />			
			<input type="hidden" name="step" value="2" />
			<input type="hidden" name="task" value="arrangements.setStep" />			
			<input type="hidden" name="jbl_form[state_check]" value="1" />
			<input type="hidden" name="noCalc" value="1" />
	</form>
	</fieldset>
	<?php
	} 
	?>
	<fieldset class="jbl_form"><legend>Uw opmerkingen:</legend>
		<p><?php echo $form['comment']; ?></p>
		<p>&nbsp;</p>
		<form method="post" action="">
			<input type="submit" name="buttonedit" value="WIJZIG" class="buttonNext" />			
			<input type="hidden" name="step" value="2" />
			<input type="hidden" name="task" value="arrangements.setStep" />			
			<input type="hidden" name="jbl_form[state_check]" value="1" />
			<input type="hidden" name="noCalc" value="1" />
		</form>
	</fieldset>
	<fieldset class="jbl_form"><legend>Heeft u alles gecontroleerd?</legend>
		<form method="post" action="">
			<input type="submit" name="sendButton" value="VERZENDEN" class="buttonNext" />
			<input type="hidden" name="task" value="arrangements.process" />
		</form>
	</fieldset>