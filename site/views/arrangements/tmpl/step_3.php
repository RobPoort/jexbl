<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

$this->app = JFactory::getApplication();
$app = $this->app;
$this->step = (int)$this->app->input->get('step');
$arrangement = $this->arrangement;
$attribs_number = $this->attrib_prices_number;
$attribs_checked = $this->attrib_prices_checked;
$attribs_special_required = $this->attrib_prices_special_required;
$attribs_special_checked = $this->attrib_prices_special_checked;
$extras = $this->extras;
$state = $this->state;
$naw = $state->naw;
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
<pre>
<?php //var_dump($arrangement); ?>
</pre>
<div class="jbl_prijsberekening" id="jbl_prijsberekening">
<form method="post" action="" class="form-validate">
	<fieldset class="jbl_form"><legend>Uw prijsberekening:</legend>
		<table class="jbl_form_table">
			<tr>
				<td>
					<?php echo $arrangement->name; ?>
					<input type="hidden" name="final[name]" value="<?php echo $arrangement->name; ?>" />
				</td>
				<td>
					&euro;<?php echo number_format($arrangement->total_arr_price, 2, ',','.');?>
					<input type="hidden" name="final[name_value]" value="&euro;&nbsp;<?php echo number_format($arrangement->total_arr_price, 2, ',','.');?>" />
				</td>
				<td>
					<?php
						if($arrangement->is_pa == 0){
							echo '('.$arrangement->number_pp.'&nbsp;personen&nbsp;&aacute;&nbsp;&euro;&nbsp;'.number_format($arrangement->price, 2, ',','.').')';
							?>
							<input type="hidden" name="final[name_number_pp]" value="<?php echo '('.$arrangement->number_pp.'&nbsp;personen&nbsp;&aacute;&nbsp;&euro;&nbsp;'.number_format($arrangement->price, 2, ',','.').')'; ?>" />
							<?php
						} 
					?>
					
				</td>
			</tr>
			<tr>
				<td>
					Periode:
				</td>
				<td>
					Van:&nbsp;<?php echo $arrangement->start_date; ?>
					<input type="hidden" name="final[start_date]" value="Van:&nbsp;<?php echo $arrangement->start_date; ?>" />
				</td>
				<td>
					Tot:&nbsp;<?php echo $arrangement->end_date; ?>
					<input type="hidden" name="final[end_date]" value="Tot:&nbsp;<?php echo $arrangement->end_date; ?>" />
				</td>
				
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<?php
				if ($attribs_number) {
					$i = 0;
					foreach ($attribs_number as $item){
						?>
						<tr>
							<td><?php echo $item->name; ?></td>							
							<td>&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?></td>
							<td>(<?php echo $item->number;?>&nbsp;maal&nbsp;&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?>)
							<input type="hidden" name="final[attribs_number][<?php echo $i; ?>][name]" value="<?php echo $item->name; ?>" />
							<input type="hidden" name="final[attribs_number][<?php echo $i; ?>][price]" value="&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?>" />
							<input type="hidden" name="final[attribs_number][<?php echo $i; ?>][number_info]" value="(<?php echo $item->number;?>&nbsp;maal&nbsp;&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?>)" />
							</td>
							
						</tr>
						
						<?php
						$i++;
					}
				} 
			?>
			<?php
				if ($attribs_checked) {
					$i = 0;
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
							<input type="hidden" name="final[attribs_checked][<?php echo $i; ?>][name]" value="<?php echo $item->name; ?>" />
							<input type="hidden" name="final[attribs_checked][<?php echo $i; ?>][price]" value="&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?>" />
							<input type="hidden" name="final[attribs_checked][<?php echo $i; ?>][number_info]" value="
								<?php
									if($item->is_pp){
										echo '('.$item->persons.'&nbsp;personen&nbsp;&aacute;&nbsp;'.number_format($item->single_price, 2, ',','.').')';
									} 
								?>
							" />
						</tr>
						<?php
						$i++;
					}
				} 
			?>
			<?php
				if ($attribs_special_required) {
					$i = 0;
					foreach ($attribs_special_required as $item){
						if($item->price > 0){
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
							<input type="hidden" name="final[attribs_special_required][<?php echo $i; ?>][name]" value="<?php echo $item->name; ?>" />
							<input type="hidden" name="final[attribs_special_required][<?php echo $i; ?>][price]" value="&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?>" />
							<input type="hidden" name="final[attribs_special_required][<?php echo $i; ?>][number_info]" value="
							<?php
								if($item->is_pp_special){
									?>
									(<?php echo $item->persons;?>&nbsp;personen&nbsp;&aacute;&nbsp;&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?>)
									<?php
								} else{
									?>
										&nbsp;
									<?php
								}
							?>" />
							
						</tr>
						<?php
						}
						$i++;
					}
				} 
			?>
			<?php
				if ($attribs_special_checked) {
					$i = 0;
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
										<td>&nbsp;
									<?php
								}
							?>
							<input type="hidden" name="final[attribs_special_checked][<?php echo $i; ?>][name]" value="<?php echo $item->name; ?>" />
							<input type="hidden" name="final[attribs_special_checked][<?php echo $i; ?>][price]" value="&euro;&nbsp;<?php echo number_format($item->total_attrib_price, 2, ',','.'); ?>" />
							<input type="hidden" name="final[attribs_special_checked][<?php echo $i; ?>][number_info]" value="
							<?php
								if($item->is_pp_special){
									?>
									(<?php echo $item->persons;?>&nbsp;personen&nbsp;&aacute;&nbsp;&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?>)
									<?php
								} else{
									?>
										&nbsp;
									<?php
								}
							?>" />
							</td>
						</tr>
						<?php
						$i++;
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
					<td>&nbsp;
					<input type="hidden" name="final[subtotaal]" value="subtotaal:" />
					<input type="hidden" name="final[subtotaal_value]" value="&euro;<?php echo number_format($this->total_price, 2, ',','.'); ?>" />
					<input type="hidden" name="final[subtotaal_extra]" value="" />
					</td>
				</tr>				
				<?php
				foreach($this->percent_items as $item){
				$i = 0;
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
					
					<input type="hidden" name="final[percent_items][<?php echo $i; ?>][name]" value="<?php echo $item->name; ?>" />
					<input type="hidden" name="final[percent_items][<?php echo $i; ?>][price]" value="&euro;&nbsp;
					<?php 
						echo number_format($item->total_attrib_price, 2, ',','.');
						if($item->total_attrib_price_percent){
							?>
							&nbsp;+&nbsp;&euro;&nbsp;
							<?php
							echo number_format($item->total_attrib_price_percent, 2, ',', '.');
						}
					 ?>" />					
					<input type="hidden" name="final[percent_items][<?php echo $i; ?>][number_info]" value="
					<?php 
					if($item->total_attrib_price_percent){
						echo '('.$item->percent.'%&nbsp;van&nbsp;&euro;&nbsp;'.number_format($this->total_price, 2, ',','.').')';
					} else {
						echo '&nbsp;';
					}
					?>"/>
					</td>				
				</tr>
				<?php
				$i++;
				}
				?>
				<tr><td colspan="3">&nbsp;</td></tr>
				<tr>				
					<td style="text-align:right;font-weight:bold;">totaal:</td>
					<td style="font-weight:bold;">&euro;&nbsp;<?php echo number_format($this->total_price_def, 2, ',','.'); ?></td>
					<td>&nbsp;</td>
					<input type="hidden" name="final[totaal]" value="totaal:" />
					<input type="hidden" name="final[totaal_value]" value="&euro;&nbsp;<?php echo number_format($this->total_price_def, 2, ',','.'); ?>" />
					<input type="hidden" name="final[totaal_extra]" value="" />
				</tr>
				<?php
			} else{
				?>
					<tr>				
					<td style="text-align:right;font-weight:bold;">totaal:</td>
					<td style="font-weight:bold;">&euro;<?php echo number_format($this->total_price, 2, ',','.'); ?></td>
					<td>&nbsp;</td>
					<input type="hidden" name="final[totaal]" value="totaal:" />
					<input type="hidden" name="final[totaal_value]" value="&euro;<?php echo number_format($this->total_price, 2, ',','.'); ?>" />
					<input type="hidden" name="final[totaal_extra]" value="" />
				</tr>
				<?php
			} 
			?>
		</table>
	</fieldset>	
	
	
		<?php
		if($this->extras){
			?>
			<fieldset class="jbl_form"><legend class="hasTip" title="Hier kunt u nog extra wensen opgeven">Extra's:</legend>
				<table class="jbl_form_table">
					<?php
						$attrib_extras_checked = $app->getUserState("option_jbl.attrib_extras_checked");
						$attrib_extras_number = $app->getUserState("option_jbl.attrib_extras_number");
						foreach($extras as $attrib){
							if($attrib->has_number){
								$value = 0;
								if($attrib_extras_number){
									foreach($attrib_extras_number as $key=>$val){
										if($attrib->id == $val->id){
											$value = $val->number;
										}
									}
								}
								?>
									<tr>
									<td class="jbl_form_checkbox">&nbsp;</td><td class="jbl_form_left"><label <?php if($attrib->desc) : ?>class="hasTip" title="<?php echo $attrib->desc; ?>" <?php endif; ?>><?php echo $attrib->name; ?></label></td><td class="jbl_form_right">&nbsp;x&nbsp;<input type="text" name="jbl_form[extras][number][<?php echo $attrib->id; ?>]" value="<?php echo $value; ?>" class="jbl_input_number" /></td>
									</tr>
								<?php
							} else{
								$checked = '';
								
								if($attrib_extras_checked){
									foreach($attrib_extras_checked as $key=>$value){										
										if($attrib->id == $value->id){
											$checked = 'checked="checked"';
											
										}
									}
								}
								?>
									<tr>
									<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[extras][checked][<?php echo $attrib->id; ?>]" value="1" <?php echo $checked; ?> /></td><td class="jbl_form_left"><label <?php if($attrib->desc) : ?>class="hasTip" title="<?php echo $attrib->desc; ?>" <?php endif; ?>><?php echo $attrib->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
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
					<td><input type="text" name="jbl_form[naw][surname]" value="<?php echo $naw['surname']; ?>" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">*Achternaam:</td>
					<td><input type="text" name="jbl_form[naw][name]" value="<?php echo $naw['name']; ?>" class="jbl_input_text" required="required" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Straat + huisnummer:</td>
					<td><input type="text" name="jbl_form[naw][street]" value="<?php echo $naw['street']; ?>" class="jbl_input_text" /><input type="text" name="jbl_form[naw][street_number]" value="<?php echo $naw['street_number']; ?>" class="jbl_input_text" style="width:20px;margin-left:3px;"/></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Postcode:</td>
					<td><input type="text" name="jbl_form[naw][zipcode]" value="<?php echo $naw['zipcode']; ?>" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Plaats:</td>
					<td><input type="text" name="jbl_form[naw][city]" value="<?php echo $naw['city']; ?>" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">*e-mail:</td>
					<td><input type="text" name="jbl_form[naw][mail]" value="<?php echo $naw['mail']; ?>" class="jbl_input_text" required="required" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">*Telefoon:</td>
					<td><input type="text" name="jbl_form[naw][phone]" value="<?php echo $naw['phone']; ?>" class="jbl_input_text" required="required" /></td>
				</tr>								
			</table>
		</fieldset>
	<fieldset class="jbl_form"><legend>Eventuele opmerkingen:</legend>
		<?php
			$value = '';
			$comment = $app->getUserState("option_jbl.comment");			
			if($comment){
				$value = $comment;
			} 
		?>
		<textarea rows="15" cols="85" name="jbl_form[comment]">
			<?php echo $comment; ?>
		</textarea>
	</fieldset>
	<fieldset class="jbl_form" id="button">			
			<input type="submit" name="sendButton" value="VOLGENDE" class="buttonNext" />			
			 <input type="hidden" name="step" value="3" />
			 <input type="hidden" name="task" value="arrangements.setStep" />
			 <input type="hidden" name="jbl_form[state_check]" value="1" />		 
	</form>
	<div class="clear">&nbsp;</div>
		<form method="post" action="">
		<button class="buttonprev" onClick="this.form.submit()" >VORIGE</button>
		<input type="hidden" name="task" value="arrangements.setStep" />
		<input type="hidden" name="step" value="1" />
		<input type="hidden" name="arrangementSelect" value="<?php echo $arrangement->id; ?>" />
		</form>
	</fieldset>
</div>