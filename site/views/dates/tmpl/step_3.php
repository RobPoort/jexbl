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
<pre><?php var_dump($this->app->getUserState("option_jbl.arrPrice")); ?></pre>
<form method="post" action="">
	<fieldset class="jbl_form" id="jbl_has_number"><legend>Uw prijsberekening:</legend>
		<table class="jbl_form_table">
		<?php if($this->app->getUserState("option_jbl.arrPrice")) : ?>
		<?php $arrs = $this->app->getUserState("option_jbl.arrPrice"); ?>
			
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
						</td>
					</tr>
				<?php endforeach; ?>
			
		<?php endif; ?>
		<?php if($this->app->getUserState("option_jbl.calcPrice")) : ?>
			 
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
			 		</td>
			 	</tr>
			 <?php endforeach; ?>
			 	<tr>
			 		<td>&nbsp;</td>
			 		<td>+</td>
			 	</tr>
			 	<tr>
			 		<td style="text-align:right;">Totaalprijs overnachtingen:</td>
			 		<td>
			 		&euro;&nbsp;
			 		<?php echo number_format($this->app->getUserState("option_jbl.calcPrice"), 2, ',', '.'); ?>
			 		</td>
			 	</tr>
			 <?php endif; ?>
			 <?php if($this->app->getUserState("option_jbl.calcattribs")) :?>
			 	<tr>
			 		<td colspan="2">&nbsp;</td>
			 	</tr>
			 <?php $calcAttribs = $this->app->getUserState("option_jbl.calcattribs"); ?>
			 	<?php foreach($calcAttribs as $item) : ?>			 	
			 	<tr>
			 		<td>
			 			<?php echo $item['attribObject']->name; ?>&nbsp;<?php echo $item['message']; ?>
			 		</td>
			 		<td>
			 			&euro;&nbsp;<?php echo number_format($item['calculated'], 2, ',', '.'); ?>
			 		</td>
			 	</tr>
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
			 		</td>
			 	</tr>
			 <?php endif; ?>
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
						<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[checked][<?php echo $item->id; ?>]" value="1" <?php if($this->default['checked']){if(array_key_exists($item->id, $this->default['checked'])){ echo 'checked="checked"'; }} ?> /></td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
						</tr>
					<?php 
					}
				}
				if($extras['number']){
					$value= '0';
					foreach($extras['number'] as $item){
					?>
						<tr>
							<td class="jbl_form_checkbox">&nbsp;</td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?></label></td><td class="jbl_form_right">&nbsp;x&nbsp;<input type="text" name="jbl_form[number][<?php echo $item->id; ?>]" value="<?php if($this->default['number']){if(array_key_exists($item->id, $this->default['number'])){echo $this->default['number'][$item->id];}} ?>" class="jbl_input_number" /></td>
						</tr>
					<?php
						
					}
				}
				
			if($extras['special']['not_required']){
							if($extras['special']['not_required']['percent']){
								foreach($extras['special']['not_required']['percent'] as $item){
									?>
									<tr>
										<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[special][not_required][percent][<?php echo $item->id; ?>]" value="1" <?php if($this->default['special']['not_required']['percent']){
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

		<textarea rows="15" cols="85" name="jbl_form[comment]">
			
		</textarea>

	</fieldset>
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="1" />
	<input type="submit" name="buttonprev" class="buttonNext" value="VORIGE" />
</form>
<div class="clear"></div>
<pre>
	<?php
		//TODO: var_dump verwijderen
		
		$this->prices = $this->app->getUserState("option_jbl.prices");
		$this->calcPrice = $this->app->getUserState("option_jbl.calcPrice");
		$this->calcAttribs = $this->app->getUserState("option_jbl.calcattribs");
		echo '<h2>$this->calcAttribs</h2>';
		var_dump($this->calcAttribs);
		echo '<h2>$this->data:</h2>';
		var_dump($this->data);
		echo '<h2>$this->default:</h2>';
		var_dump($this->default);
		echo '<h2>$attribs</h2>';
		var_dump($attribs);
		echo '<h2>$this->calcPrice</h2>';
		var_dump($this->calcPrice);
		//echo '<h2>$this->overlap</h2>';
		var_dump($this->overlap);
		echo '<h2>$this->prices</h2>';
		var_dump($this->prices);
		
	?>
</pre>
