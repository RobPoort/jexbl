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
$extras = $this->extras;
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
<div class="jbl_prijsberekening" id="jbl_prijsberekening">
	<fieldset class="jbl_form"><legend>Uw prijsberekening:</legend>
		<table class="jbl_form_table">
			<tr>
				<td>
					<?php echo $arrangement->name; ?>
				</td>
				<td>
					&euro;<?php echo number_format($arrangement->total_arr_price, 2, ',','.');?>
				</td>
				<td>
					<?php
						if($arrangement->number_pp){
							echo '('.$arrangement->number_pp.'&nbsp;personen&nbsp;&aacute;&nbsp;&euro;&nbsp;'.number_format($arrangement->price, 2, ',','.').')';
						} 
					?>
				</td>
			</tr>
			<tr>
				<td>
					&nbsp;
				</td>
				<td>
					Van:&nbsp<?php echo $arrangement->start_date; ?>
				</td>
				<td>
					Tot:&nbsp;<?php echo $arrangement->end_date; ?>
				</td>
				
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<?php
				if ($attribs_number) {
					foreach ($attribs_number as $item){
						?>
						<tr>
							<td><?php echo $item->name; ?></td>
							<td>&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?></td>
							<td>(<?php echo $item->number;?>&nbsp;maal&nbsp;&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?>)</td>
						</tr>
						<?php
					}
				} 
			?>
			<?php
				if ($attribs_checked) {
					foreach ($attribs_checked as $item){
						?>
						<tr>
							<td><?php echo $item->name; ?></td>
							<td>&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?></td>
							<td>
							<?php
								if($item->is_pp){
									echo '('.$item->persons.'&nbsp;personen&nbsp;&aacute;&nbsp;'.number_format($item->single_price, 2, ',','.').')';
								} 
							?>
							</td>
						</tr>
						<?php
					}
				} 
			?>
			<?php
				if ($attribs_special_required) {
					foreach ($attribs_special_required as $item){
						?>
						<tr>
							<td><?php echo $item->name; ?></td>
							<td>&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?></td>
							<?php
								if($item->is_pp_special){
									?>
									<td>(<?php echo $item->persons;?>&nbsp;personen&nbsp;&aacute;&nbsp;&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?>)</td>
									<?php
								} else{
									?>
										<td>&nbsp;</td>
									<?php
								}
							?>
						</tr>
						<?php
					}
				} 
			?>
			<?php
				if ($attribs_special_checked) {
					foreach ($attribs_special_checked as $item){
						?>
						<tr>
							<td><?php echo $item->name; ?></td>
							<td>&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?></td>
							<?php
								if($item->is_pp_special){
									?>
									<td>(<?php echo $item->persons;?>&nbsp;personen&nbsp;&aacute;&nbsp;&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?>)</td>
									<?php
								} else{
									?>
										<td>&nbsp;</td>
									<?php
								}
							?>
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
			if(count($this->percent_items) > 0){
				?>
				<tr>				
					<td style="text-align:right;font-weight:bold;">subtotaal:</td>
					<td style="font-weight:bold;">&euro;<?php echo number_format($this->total_price, 2, ',','.'); ?></td>
					<td>&nbsp;</td>
				</tr>				
				<?php
				foreach($this->percent_items as $item){
				?>				
				<tr>
					<td><?php echo $item->name; ?></td>
					<td>
					&euro;&nbsp;
					<?php 
						echo number_format($item->total_attrib_price, 2, ',','.');
						if($item->total_attrib_price_percent){
							?>
							&nbsp;+&nbsp;&euro;&nbsp;
							<?php
							echo number_format($item->total_attrib_price_percent, 2, ',', '.');
						}
					 ?>
					</td>
					<td>
					<?php 
					if($item->total_attrib_price_percent){
						echo '('.$item->percent.'%&nbsp;van&nbsp;&euro;&nbsp;'.number_format($this->total_price, 2, ',','.').')';
					} else {
						echo '&nbsp;';
					}
					?>
					</td>
				</tr>
				<?php
				}
				?>
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>				
					<td style="text-align:right;font-weight:bold;">totaal:</td>
					<td style="font-weight:bold;">&euro;<?php echo number_format($this->total_price_def, 2, ',','.'); ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php
			} else{
				?>
					<tr>				
					<td style="text-align:right;font-weight:bold;">totaal:</td>
					<td style="font-weight:bold;">&euro;<?php echo number_format($this->total_price, 2, ',','.'); ?></td>
					<td>&nbsp;</td>
				</tr>
				<?php
			} 
			?>
		</table>
	</fieldset>
	<form method="post" action="" class="form-validate">
		<?php
		if($this->extras){
			?>
			<fieldset class="jbl_form"><legend class="hasTip" title="Hier kunt u nog extra wensen opgeven">Extra's:</legend>
				<table class="jbl_form_table">
					<?php
						foreach($extras as $attrib){
							if($attrib->has_number){
								?>
									<tr>
									<td class="jbl_form_checkbox">&nbsp;</td><td class="jbl_form_left"><label <?php if($attrib->desc) : ?>class="hasTip" title="<?php echo $attrib->desc; ?>" <?php endif; ?>><?php echo $attrib->name; ?></label></td><td class="jbl_form_right">&nbsp;x&nbsp;<input type="text" name="jbl_form[number][<?php echo $attrib->id; ?>]" value="0" class="jbl_input_number" /></td>
									</tr>
								<?php
							} else{
								?>
									<tr>
									<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[checked][<?php echo $attrib->id; ?>]" value="1" /></td><td class="jbl_form_left"><label <?php if($attrib->desc) : ?>class="hasTip" title="<?php echo $attrib->desc; ?>" <?php endif; ?>><?php echo $attrib->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
									</tr>
								<?php
							}
						}
					?>
				</table>
			</fieldset>
			<?php
		} 
		?>
		<fieldset class="jbl_form"><legend>Uw NAW-gegevens:</legend>
			<table class="jbl_form_table" id="">
				<tr>
					<td class="jbl_label_naw">Voornaam:</td>
					<td><input type="text" name="jbl_form[surname]" value="" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">*Achternaam:</td>
					<td><input type="text" name="jbl_form[name]" value="" class="jbl_input_text" required="required" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Straat + huisnummer:</td>
					<td><input type="text" name="jbl_form[street]" value="" class="jbl_input_text" /><input type="text" name="jbl_form[street_number]" value="" class="jbl_input_text" style="width:20px;margin-left:3px;"/></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Postcode:</td>
					<td><input type="text" name="jbl_form[zipcode]" value="" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Plaats:</td>
					<td><input type="text" name="jbl_form[city]" value="" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">*e-mail:</td>
					<td><input type="text" name="jbl_form[mail]" value="" class="jbl_input_text" required="required" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">*Telefoon:</td>
					<td><input type="text" name="jbl_form[phone]" value="" class="jbl_input_text" required="required" /></td>
				</tr>								
			</table>
		</fieldset>
		<fieldset class="jbl_form" id="button">			
			<input type="submit" name="sendButton" value="VOLGENDE" class="buttonNext" />			
			 <input type="hidden" name="step" value="3" />
			 <input type="hidden" name="task" value="arrangements.setStep" />		 
	</form>
	<div class="clear">&nbsp;</div>
		<form method="post" action="">
		<button class="buttonNext" onClick="this.form.submit()" >VORIGE</button>
		<input type="hidden" name="task" value="arrangements.setStep" />
		<input type="hidden" name="step" value="1" />
		<input type="hidden" name="arrangementSelect" value="<?php echo $arrangement->id; ?>" />
		</form>
	</fieldset>
</div>
	<pre>
		<?php
			$app = JFactory::getApplication();
			$state = $app->getUserState("option_jbl");
			var_dump($state); 
		?>
	</pre>