<?php
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

//waardes ophalen
$item = $this->item['locatie'];
$attribs = $this->item['attribs'];
$overlap = $this->overlap;
$naw = $this->app->getUserState("option_jbl.naw");
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
		<table class="jbl_form_table">
		<?php if($overlap) :?>
			<?php if($this->app->getUserState("option_jbl.arrPrice")) : ?>
			<?php $arrs = $this->app->getUserState("option_jbl.arrPrice"); ?>
					<?php $i = 0; ?>
					<?php foreach($arrs as $arr) : ?>
						<tr>
							<td>
								<?php
									foreach($arr['price_message'] as $key=>$value){
										echo $value.'<br />';
									} 
								?>
							</td>
							<td>
								&euro;&nbsp;
								<?php echo number_format($arr['arr_price'], 2, ',', '.'); ?>
								<input type="hidden" name="final[arrangement][<?php echo $i; ?>][name]" value="<?php echo $arr['price_message'][$i]; ?>" />
								<input type="hidden" name="final[arrangement][<?php echo $i; ?>][price]" value="<?php echo $arr['arr_price']; ?>" />
							</td>
						</tr>
					<?php $i++; ?>
					<?php endforeach; ?>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>
		<?php if($this->app->getUserState("option_jbl.calcPrice")) : ?>
			 <?php $i = 0; ?>
			 <?php foreach($this->app->getUserState("option_jbl.stayperiods") as $stayPeriod) : ?>
			 	<tr>
			 		<td>
			 			<?php echo $stayPeriod['nachten']; ?>
			 			&nbsp;nacht(en)&nbsp;
			 			<?php echo $stayPeriod['priceObject']->name; ?>
			 			&nbsp;met&nbsp;
			 			<?php echo $stayPeriod['number_pp']; ?>
			 			&nbsp;personen:
			 			<?php if($stayPeriod['message'] != '') : ?>
			 			<br />(<?php echo $stayPeriod['message'] ?>)
			 			<?php endif; ?>
			 		</td>
			 		
			 		<td>
			 			&euro;&nbsp;
			 			<?php echo number_format($stayPeriod['stayPeriodPrice'], 2, ',', '.'); ?>
			 			<input type="hidden" name="final[stayPeriod][<?php echo $i; ?>][nachten]" value="<?php echo $stayPeriod['nachten']; ?>" />
			 			<input type="hidden" name="final[stayPeriod][<?php echo $i; ?>][name]" value="<?php echo $stayPeriod['priceObject']->name; ?>" />
			 			<input type="hidden" name="final[stayPeriod][<?php echo $i; ?>][number_pp]" value="<?php echo $stayPeriod['number_pp']; ?>" />
			 			<input type="hidden" name="final[stayPeriod][<?php echo $i; ?>][stayPeriodPrice]" value="<?php echo $stayPeriod['stayPeriodPrice']; ?>" />
			 			<input type="hidden" name="final[stayPeriod][<?php echo $i; ?>][message]" value="<?php echo $stayPeriod['message']; ?>" />
			 		</td>
			 	</tr>
			 <?php $i++; ?>
			 <?php endforeach; ?>
			 <?php if($this->app->getUserState("option_jbl.subTotalDiscount")) : ?>
			 <?php
			 	$subTotalDiscount			= $this->app->getUserState("option_jbl.subTotalDiscount");
			 	$subTotalDiscountMessage	= $this->app->getUserState("option_jbl.subTotalDiscountMessage");
			 ?>
			 
			 	<tr>
			 		<td><?php echo $subTotalDiscountMessage; ?></td>
			 		<td>-&nbsp;&euro;&nbsp;<?php echo number_format($subTotalDiscount, 2, ',', '.'); ?>
			 		<input type="hidden" name="final[subTotalDiscount][message]" value="<?php echo $subTotalDiscountMessage; ?>" />
			 		<input type="hidden" name="final[subTotalDiscount][subtotal]" value="<?php echo $subTotalDiscount; ?>" />
			 		</td>
			 	</tr>
			 <?php endif; ?>
			 	<tr>
			 		<td>&nbsp;</td>
			 		<td>+</td>
			 	</tr>
			 	<tr>
			 		<td style="text-align:right;">
			 		<?php if($overlap) : ?>
			 		Prijs overnachtingen:
			 		<?php else :?>
			 		Totaalprijs overnachtingen:
			 		<?php endif; ?>
			 		</td>
			 		<td>
			 		&euro;&nbsp;
			 		<?php echo number_format($this->app->getUserState("option_jbl.calcPrice"), 2, ',', '.'); ?>
			 		<input type="hidden" name="final[calcStayPeriodsPrice]" value="<?php echo $this->app->getUserState("option_jbl.calcPrice"); ?>" />
			 		</td>
			 	</tr>			 	
			 <?php endif; ?>
			 <?php if($overlap) :?>
			 	<tr>
			 		<td style="text-align:right;">Totaalprijs overnachtingen:</td>
			 		<td>
			 			&euro;&nbsp;<?php echo number_format(($this->app->getUserState("option_jbl.calcPrice") + $arr['arr_price']), 2, ',', '.'); ?>
			 			<input type="hidden" name="final[calcTotalStayPeriodsPrice]" value="<?php echo $this->app->getUserState("option_jbl.calcPrice") + $arr['arr_price']; ?>" />
			 		</td>
			 	</tr>
			 <?php endif; ?>
			 <?php if($this->app->getUserState("option_jbl.calcattribs")) :?>
			 	<tr>
			 		<td colspan="2">&nbsp;</td>
			 	</tr>
			 	<tr>
					<td colspan="2" style="text-align:left;font-weight:bold;">Toevoegingen:</td>
				</tr>
			 <?php $calcAttribs = $this->app->getUserState("option_jbl.calcattribs"); ?>
			 	<?php $i = 0; ?>
			 	<?php foreach($calcAttribs as $item) : ?>			 	
			 	<tr>
			 		<td>
			 			<?php echo $item['attribObject']->name; ?>&nbsp;<?php echo $item['message']; ?>
			 		</td>
			 		<td>
			 			&euro;&nbsp;<?php echo number_format($item['calculated'], 2, ',', '.'); ?>
			 			<input type="hidden" name="final[calcAttribs][<?php echo $i; ?>][name]" value="<?php echo $item['attribObject']->name; ?>&nbsp;<?php echo $item['message']; ?>" />
			 			<input type="hidden" name="final[calcAttribs][<?php echo $i; ?>][price]" value="<?php echo $item['calculated']; ?>" />
			 		</td>
			 	</tr>
			 	<?php $i++; ?>
			 	<?php endforeach; ?>
			 	<tr>
			 		<td>&nbsp;</td>
			 		<td>+</td>
			 	</tr>
			 	<tr>
			 		<td style="text-align:right;">Subtotaalprijs toevoegingen:</td>
			 		<td>
			 		&euro;&nbsp;
			 		<?php echo number_format($this->app->getUserState("option_jbl.attribsSubTotaal"), 2, ',', '.'); ?>
			 		<input type="hidden" name="final[subTotalAttribs]" value="<?php echo $this->app->getUserState("option_jbl.attribsSubTotaal"); ?>" />			 		
			 		</td>
			 	</tr>
			 <?php endif; ?>
			 <?php if($this->app->getUserState("option_jbl.calcattribsSpecial")) : ?>
			 	<tr>
			 		<td colspan="2">&nbsp;</td>
			 	</tr>
			 	<?php $not_percents = $this->app->getUserState("option_jbl.calcattribsSpecial"); ?>
			 		<?php $i = 0; ?>
			 		<?php foreach($not_percents as $item) : ?>
			 			<tr>
			 				<td>
			 					<span class="hasTip" title="<?php echo $item['attribObject']->desc; ?>"><?php echo $item['attribObject']->name; ?></span>&nbsp;<?php echo $item['message']; ?>
			 				</td>
			 				<td>
			 					&euro;&nbsp;<?php echo number_format($item['calculated'], 2, ',', '.'); ?>
			 					<input type="hidden" name="final[special][not_percents][<?php echo $i; ?>][name]" value="<?php echo $item['attribObject']->name; ?>" />
			 					<input type="hidden" name="final[special][not_percents][<?php echo $i; ?>][name]" value="<?php echo $item['calculated']; ?>" />
			 				</td>
			 			</tr>
			 		<?php $i++; ?>
			 		<?php endforeach; ?>
			 		<tr>
				 		<td>&nbsp;</td>
				 		<td>+</td>
			 		</tr>
			 	<tr>
			 		<td style="text-align:right;">Subtotaalprijs extra kosten:</td>
			 		<td>
			 		&euro;&nbsp;
			 		<?php echo number_format($this->app->getUserState("option_jbl.calcAttribsSpecialSubTotaal"), 2, ',', '.'); ?>
			 		<input type="hidden" name="final[subTotalSpecial]" value="<?php echo $this->app->getUserState("option_jbl.calcAttribsSpecialSubTotaal"); ?>" />
			 		</td>
			 	</tr>
			 <?php endif; ?>
			 <?php if($this->app->getUserState("option_jbl.subtotal")) : ?>
			 <?php $subtotal = $this->app->getUserState("option_jbl.subtotal"); ?>
			 		<tr>
				 		<td>&nbsp;</td>
				 		<td>+</td>
			 		</tr>
			 		<tr>
				 		<td style="text-align:right;font-weight:bold;">Subtotaalprijs:</td>
				 		<td style="font-weight:bold;">
				 		&euro;&nbsp;
				 		<?php echo number_format($subtotal, 2, ',', '.'); ?>
				 		<input type="hidden" name="final[subTotalPrice]" value="<?php echo $subtotal; ?>" />
				 		</td>
			 		</tr>
			 <?php endif; ?>
			 <?php if($this->app->getUserState("option_jbl.TotalDiscount")) : ?>
			 <?php
			 	$totalDiscount = $this->app->getUserState("option_jbl.TotalDiscount");
			 	$totalDiscountMessage	= $this->app->getUserState("option_jbl.TotalDiscountMessage");
			  ?>
			 	<tr>
			 		<td><?php echo $totalDiscountMessage; ?></td>
			 		<td>-&nbsp;&euro;&nbsp;<?php echo number_format($totalDiscount, 2, ',', '.'); ?>
				 		<input type="hidden" name="final[subTotalPriceDiscount]" value="<?php echo $totalDiscountMessage; ?>" />
				 		<input type="hidden" name="final[subTotalPriceDiscount]" value="<?php echo $totalDiscount; ?>" />
			 		</td>
			 	</tr>
			 <?php endif; ?>
			 <?php if($this->app->getUserState("option_jbl.TotalAdd")) : ?>
			 <?php
			 	$totalAdd			= $this->app->getUserState("option_jbl.TotalAdd");
			 	$totalAddMessage	= $this->app->getUserState("option_jbl.TotalAddMessage"); 
			 ?>
			 	<tr>
			 		<td><?php echo $totalAddMessage; ?></td>
			 		<td>+&nbsp;&euro;&nbsp;<?php echo number_format($totalAdd, 2, ',', '.'); ?>
			 		<input type="hidden" name="final[totalAdd][message]" value="<?php echo $totalAddMessage; ?>" />
			 		<input type="hidden" name="final[totalAdd][price]" value="<?php echo $totalAdd; ?>" />
			 		</td>
			 	</tr>
			 <?php endif; ?>
			 <?php
			 	$defTotal = $this->app->getUserState("option_jbl.defTotal"); 
			 ?>
			 	<tr>
			 		<td colspan="2">&nbsp;</td>
			 	</tr>
			 	<tr>
			 		<td>&nbsp;</td>
			 		<td>+</td>
			 	</tr>
			 	<tr>
			 		<td style="text-align:right;font-weight:bold;">Totaalprijs:</td>
			 		<td style="font-weight:bold;">
			 			&euro;&nbsp;<?php echo number_format($defTotal, 2, ',', '.'); ?>
			 			<input type="hidden" name="final[defTotal]" value="<?php echo $defTotal; ?>" />
			 		</td>
			 	</tr>
			 </table>
		
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
	<?php if($attribs['extras'] && !empty($attribs['extras'])) : ?>
			<fieldset class="jbl_form" id="jbl_form"><legend>Extra's</legend>
			<table class="jbl_form_table">
				<?php
				$extras = $attribs['extras'];
				if($extras['checked']){
					
					$checked = '';
					foreach($extras['checked'] as $item){
						if($this->default['checked']){							
						}
						
					?>
						<tr>
						<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="final[extras][checked][<?php echo $item->id; ?>]" value="<?php echo $item->name; ?>" <?php if($this->default['checked']){if(array_key_exists($item->id, $this->default['checked'])){ echo 'checked="checked"'; }} ?> /></td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
						</tr>
					<?php 
					}
				}
				if($extras['number']){
					$value= '0';
					foreach($extras['number'] as $item){
					?>
						<tr>
							<td class="jbl_form_checkbox">&nbsp;</td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?></label></td><td class="jbl_form_right">&nbsp;x&nbsp;<input type="text" name="final[extras][number][<?php echo $item->name; ?>]" value="<?php if($this->default['number']){if(array_key_exists($item->id, $this->default['number'])){echo $this->default['number'][$item->id];}} ?>" class="jbl_input_number" /></td>
						</tr>
					<?php
						
					}
				}
				
			if($extras['special']['not_required']){
							if($extras['special']['not_required']['percent']){
								foreach($extras['special']['not_required']['percent'] as $item){
									?>
									<tr>
										<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="final[extras][special][not_required][percent][<?php echo $item->id; ?>]" value="1" <?php if($this->default['special']['not_required']['percent']){
											if(array_key_exists($item->id, $this->default['special']['not_required']['percent']))
										{ echo 'checked="checked"'; }} ?> /></td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
									</tr>
									<?php
								}
							} 
						?>
					
				<?php
			}
			?>
				</table>
			</fieldset>
	<?php endif; ?>
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
				<tr>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td colspan="2">De Papillon is een familiecamping en om een evenwichtige samenstelling van onze gasten te waarborgen, vragen wij u uw geboortedata op te geven. Bij voorbaat onze dank.</td>
				</tr>
				<?php
					$number_pp = $this->default['number_pp'];
					$i = 1;
					while($i <= $number_pp){
						?>
						<tr>
							<td class="jbl_label_naw">Geboortedatum persoon <?php echo $i; ?>:</td>
							<td><input type="text" name="jbl_form[naw][birthdate][<?php echo $i; ?>]" value="<?php echo $naw['birthdate'][$i]; ?>" class="jbl_input_text" required="required" /></td>
						</tr>
						<?php
						$i++;
					} 
				?>					
			</table>
			
		</fieldset>
	<fieldset class="jbl_form"><legend>Eventuele opmerkingen:</legend>

		<textarea rows="15" cols="85" name="jbl_form[comment]"></textarea>

	</fieldset>
	<fieldset class="jbl_form" id="button">			
			<input type="submit" name="sendButton" value="VOLGENDE" class="buttonNext" />			
			 <input type="hidden" name="step" value="3" />
			 <input type="hidden" name="task" value="dates.setStep" />
			 <input type="hidden" name="jbl_form[state_check]" value="1" />
			 <input type="hidden" name="final[start_date]" value="<?php echo $this->data['start_date']; ?>" />
			 <input type="hidden" name="final[end_date]" value="<?php echo $this->data['end_date']; ?>" />
			 <input type="hidden" name="final[number_pp]" value="<?php echo $this->default['number_pp']; ?>" /> 
	</form>
	<div class="clear">&nbsp;</div>
		<form method="post" action="">
			<input type="hidden" name="task" value="dates.setStep" />	
			<input type="hidden" name="step" value="1" />
			<input type="submit" name="buttonprev" class="buttonNext" value="VORIGE" />
		</form>
	</fieldset>
