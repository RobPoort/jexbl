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
<h1>Overzicht:</h1>
<div class="jbl_prijsberekening" id="jbl_prijsberekening">
	
		<fieldset class="jbl_form"><legend>Uw prijsberekening:</legend>
		
			<table class="jbl_form_table">
				<tr>
					<td>
						<?php echo $final['name']; ?>
					</td>
					<td>
						<?php echo $final['name_value']; ?>
					</td>
					<td><?php echo $final['name_number_pp']; ?></td>
				</tr>
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
					<td colspan="3">&nbsp;</td>
				</tr>
				<?php
					if ($final['attribs_number']) {
						foreach ($final['attribs_number'] as $item){
							?>
							<tr>
								<td><?php echo $item['name']; ?></td>
								<td><?php echo $item['price']; ?></td>
								<td><?php echo $item['number_info']; ?></td>							
							</tr>
							<?php
						}
					} 
				?>
				<?php
					if ($final['attribs_checked']) {
						foreach ($final['attribs_checked'] as $item){
							?>
							<tr>
								<td><?php echo $item['name']; ?></td>
								<td><?php echo $item['price']; ?></td>
								<td><?php echo $item['number_info']; ?></td>
							</tr>
							<?php
						}
					} 
				?>
				<?php
					if ($final['attribs_special_required']) {
						foreach ($final['attribs_special_required'] as $item){
							if($item['price'] > 0){
							?>
							<tr>
								<td><?php echo $item['name']; ?></td>
								<td><?php echo $item['price']; ?></td>
								<td><?php echo $item['number_info']; ?></td>
							</tr>
							<?php
							}
						}
					} 
				?>
				<?php
					if ($final['attribs_special_checked']) {
						foreach ($final['attribs_special_checked'] as $item){
							?>
							<tr>
								<td><?php echo $item['name']; ?></td>
								<td><?php echo $item['price']; ?></td>
								<td><?php echo $item['number_info']; ?></td>
							</tr>
							<?php
						}
					} 
				?>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>				
					<td style="text-align:left;">+</td>
					<td>&nbsp;</td>
				</tr>			
				<?php
				if(count($final['percent_items']) > 0){ //TODO hier kan het eventueel (weer) fout gaan, denk hierbij aan diepte van de multi-dimensional array
					?>
					<tr>				
						<td style="text-align:right;font-weight:bold;"><?php echo $final['subtotaal']; ?></td>
						<td style="font-weight:bold;"><?php echo $final['subtotaal_value']; ?></td>
						<td><?php echo $final['subtotaal_extra']; ?></td>
					</tr>				
					<?php
					foreach($final['percent_items'] as $item){
					?>				
					<tr>
						<td><?php echo $item['name']; ?></td>
						<td><?php echo $item['price']; ?></td>
						<td><?php echo $item['number_info']; ?></td>
					</tr>
					<?php
					}
					?>
					<tr><td colspan="3">&nbsp;</td></tr>
					<tr>				
						<td style="text-align:right;font-weight:bold;"><?php echo $final['totaal']; //TODO ook hier opletten of het juiste wordt getoond ?></td>
						<td style="font-weight:bold;"><?php echo $final['totaal_value']; ?></td>
						<td><?php echo $final['totaal_extra']; ?></td>
					</tr>
					<?php
				} else{
					?>
						<tr>				
						<td style="text-align:right;font-weight:bold;"><?php echo $final['totaal']; ?></td>
						<td style="font-weight:bold;"><?php echo $final['totaal_value']; ?></td>
						<td><?php echo $final['totaal_extra']; ?></td>
					</tr>
					<?php
				} 
				?>
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