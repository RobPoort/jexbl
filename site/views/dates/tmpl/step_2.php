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
<form action="" method="post">
	<fieldset class="jbl_form" id="jbl_has_number"><legend <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo ucfirst($item->name);?></legend>
		<table class="jbl_form_table">
			<?php
				if($overlap){
				?>
					<tr><td colspan="2"><p><?php echo ucfirst($overlap['overlap_message']); ?></p></td></tr>
					<tr><td colspan="2"><hr /></td></tr>
					<tr><td class="tdleft">Gekozen periode:</td><td></td></tr>
					<tr><td class="tdleft">Van:&nbsp;<?php echo $this->item['aankomst']; ?>&nbsp;&nbsp;Tot:&nbsp;<?php echo $this->item['vertrek']; ?></td><td></td></tr>
					<tr><td class="tdleft">Arrangement:&nbsp;<?php echo ucwords($overlap['arrangement']->name); ?></td><td>Van:&nbsp;<?php echo $overlap['arrangement']->start_date; ?><br />Tot:&nbsp;<?php echo $overlap['arrangement']->end_date; ?></td></tr>
					<tr>
					<?php
						if($overlap['arrangement']->is_pp){
							?>
							<td class="tdLeft">
								Kosten van dit arrangement per persoon:<br />
								<?php if($overlap['arrangement']->use_extra_pp == 0) : ?>
								&euro;&nbsp;<?php echo number_format($overlap['arrangement']->price, 2, ',','.'); ?>&nbsp;pp.
								<?php elseif($overlap['arrangement']->use_extra_pp == 1) : ?>
								Eerste persoon:
								&nbsp;&euro;&nbsp;<?php echo number_format($overlap['arrangement']->price, 2, ',','.'); ?><br />
								Daarop volgende personen:
								&nbsp;&euro;&nbsp;<?php echo number_format($overlap['arrangement']->extra_pp, 2, ',','.'); ?>&nbsp;pp.
								<?php endif; ?>
							</td>
							<td></td>
							<?php
						} else{
							?>
							<td class="tdLeft">
								Kosten van dit arrangement:<br />
								&euro;&nbsp;<?php echo number_format($overlap['arrangement']->price, 2, ',','.'); ?>
							</td>
							<td></td>
							<?php
						}
					?>
					</tr>
					<tr><td colspan="2"></td></tr>
					<tr><td class="tdleft">Dagen buiten het arrangement:&nbsp;<?php echo $overlap['buiten_arr']; ?>&nbsp;
					<?php
						if($overlap['buiten_arr'] == 1){
							echo 'dag';
						} else{
							echo 'dagen';
						}
					?>
					</td><td></td>
					</tr>
					<?php if(!empty($this->prices->pricePeriods)) : ?>
						<?php foreach($this->prices->pricePeriods as $key=>$value) : ?>
							<tr>
								<td>
								<?php echo $value['nachten']; ?>&nbsp;dagen in de periode&nbsp;<?php echo $value['priceObject']->name; ?>
								</td>
								<td>
									<?php if($key == 0 && $value['priceObject']->is_pn_extra) : ?>
										<?php if($value['nachten'] == 1) : ?>
											1 nacht &aacute;&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->min_price, 2, ',', '.'); ?>&nbsp;pp/pn.
										<?php elseif($value['nachten'] > 1) : ?>
											Eerste dag:&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->min_price); ?>&nbsp;pp/pn<br />
											Daarop volgende <?php echo ($value['nachten'] - 1); ?> dag(en):&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->extra, 2, ',', '.'); ?>&nbsp;pp/pn
										<?php endif; ?>
										<?php elseif($value['priceObject']->is_pn_extra) : ?>
										<?php echo $value['nachten']; ?>&nbsp;nacht(en)&nbsp;&aacute;&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->extra, 2, ',', '.'); ?>&nbsp;pp/pn
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					
					
				<?php
				} else{
					?>
						<tr><td class="tdleft">Gekozen periode:</td><td></td></tr>
						<tr><td class="tdleft">Van:&nbsp;<?php echo $this->item['aankomst']; ?></td><td>Tot:&nbsp;<?php echo $this->item['vertrek']; ?></td></tr>
						<tr><td class="tdleft">Dit is <?php echo floor($this->item['nights']); ?>&nbsp;
						<?php
							if($this->item['nights'] == 1){
								echo 'dag';
							} else {
								echo 'dagen';
							}
						?>
						</td><td></td></tr>
						
					<?php if(!empty($this->prices->pricePeriods)) : ?>
						<?php foreach($this->prices->pricePeriods as $key=>$value) : ?>
							<tr>
								<td>
								<?php echo $value['nachten']; ?>&nbsp;dagen in de periode&nbsp;<?php echo $value['priceObject']->name; ?>
								</td>
								<td>
									<?php if($key == 0 && $value['priceObject']->is_pn_extra) : ?>
										<?php if($value['nachten'] == 1) : ?>
											1 nacht &aacute;&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->min_price, 2, ',', '.'); ?>&nbsp;pp/pn
										<?php elseif($value['nachten'] > 1) : ?>
											Eerste dag:&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->min_price, 2, ',', '.'); ?>&nbsp;pp/pn<br />
											Daarop volgende <?php echo ($value['nachten'] - 1); ?> dag(en):&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->extra, 2, ',', '.'); ?>&nbsp;pp/pn
										<?php endif; ?>
										<?php elseif($value['priceObject']->is_pn_extra) : ?>
										<?php echo $value['nachten']; ?>&nbsp;nacht(en)&nbsp;&aacute;&nbsp;&euro;&nbsp;<?php echo number_format($value['priceObject']->extra, 2, ',', '.'); ?>&nbsp;pp/pn
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					
					
					<?php
				}
			?>
		</table>
	</fieldset>
	<fieldset class="jbl_form" id="jbl_has_number">
		<table class="jbl_form_table">			
			<tr><td class="jbl_form_left"><label>Aantal personen:&nbsp;</label></td><td class="jbl_form_right"><input type="text" name="jbl_form[number_pp]" value="<?php echo (int)$this->default['number_pp'] > 1 ? $this->default['number_pp'] : 2;	?>" class="jbl_input_number" />
			</td></tr>
		</table>
	</fieldset>
	<?php
		if($attribs){
		?>
		<fieldset class="jbl_form" id="jbl_form"><legend class="hasTip" title="Extra faciliteiten tegen een meerprijs">Toevoegingen:</legend>
			<table class="jbl_form_table">
				<?php
				if($attribs['checked']){
					
					$checked = '';
					foreach($attribs['checked'] as $item){
						if($this->default['checked']){							
						}
						
					?>
						<tr>
						<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[checked][<?php echo $item->id; ?>]" value="1" <?php if($this->default['checked']){if(array_key_exists($item->id, $this->default['checked'])){ echo 'checked="checked"'; }} ?> /></td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
						</tr>
					<?php 
					}
				}
				if($attribs['number']){
					$value= '0';
					foreach($attribs['number'] as $item){
					?>
						<tr>
							<td class="jbl_form_checkbox">&nbsp;</td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?></label></td><td class="jbl_form_right">&nbsp;x&nbsp;<input type="text" name="jbl_form[number][<?php echo $item->id; ?>]" value="<?php if($this->default['number']){if(array_key_exists($item->id, $this->default['number'])){echo $this->default['number'][$item->id];}} ?>" class="jbl_input_number" /></td>
						</tr>
					<?php
						
					}
				}
			if(!empty($attribs['special'])){
			?>
			<tr>
				<td colspan="2" style="text-align:left;font-weight:bold;">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:left;font-weight:bold;">Speciaal:</td>
			</tr>
			<?php 
			if($attribs['special']['not_required']){
							if($attribs['special']['not_required']['percent']){
								foreach($attribs['special']['not_required']['percent'] as $item){
									?>
									<tr>
										<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[special][not_required][percent][<?php echo $item->id; ?>]" value="1" <?php if($this->default['special']['not_required']['percent']){
											if(array_key_exists($item->id, $this->default['special']['not_required']['percent']))
										{ echo 'checked="checked"'; }} ?> /></td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
									</tr>
									<?php
								}
							}
							if($attribs['special']['not_required']['not_percent']){
								foreach($attribs['special']['not_required']['not_percent'] as $item){
	?>
									<tr>
										<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[special][not_required][not_percent][<?php echo $item->id; ?>]" value="1" <?php if($this->default['special']['not_required']['not_percent']){
											if(array_key_exists($item->id, $this->default['special']['not_required']['not_percent']))
										{ echo 'checked="checked"'; }} ?> /></td><td class="jbl_form_left"><label <?php if($item->desc) : ?>class="hasTip" title="<?php echo $item->desc; ?>" <?php endif; ?>><?php echo $item->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
									</tr>
									<?php
								}
							}
						?>
					
				<?php
			}			
			?>
				<?php if($attribs['special']['required']) :?>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:left;font-weight:bold;">Verplicht:</td>
				</tr>
				<?php if($attribs['special']['required']['percent']) :?>
					<?php foreach($attribs['special']['required']['percent'] as $item) : ?>
						<tr>
							<td class="hasTip" title="<?php echo $item->desc; ?>" colspan="2"><?php echo $item->name; ?></td>							
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php if($attribs['special']['required']['not_percent']) : ?>
					<?php foreach($attribs['special']['required']['not_percent'] as $item) : ?>
						<tr>
							<td class="hasTip" title="<?php echo $item->desc; ?>" colspan="2"><?php echo $item->name; ?></td>							
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php endif; ?>
				<?php
				} 
				?>
				</table>
			</fieldset>			
			<?php
		} 
	?>
	<input type="hidden" name="jbl_form[start_date]" value="<?php echo $this->data['start_date']; ?>" />
	<input type="hidden" name="jbl_form[end_date]" value="<?php echo $this->data['end_date']; ?>" />
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="2" />
	<input type="submit" name="buttonNext" class="buttonNext" value="VOLGENDE" />
</form>
<form method="post" action="">
	<input type="hidden" name="jbl_form[start_date]" value="<?php echo $this->data['start_date']; ?>" />
	<input type="hidden" name="jbl_form[end_date]" value="<?php echo $this->data['end_date']; ?>" />
	<input type="hidden" name="task" value="dates.setStep" />	
	<input type="hidden" name="step" value="0" />
	<input type="submit" name="buttonprev" class="buttonNext" value="VORIGE" />
</form>
<div class="clear"></div>
<pre>
	<?php
		//TODO: var_dump verwijderen
		//echo '<h2>$this->overlap</h2>';
		//var_dump($this->overlap);
		//$this->calcAttribs = $this->app->getUserState("option_jbl.calcattribs");
		//var_dump($this->prices);
		//echo '<h2>$this->calcAttribs</h2>';
		//var_dump($this->calcAttribs);
			
		//echo '<h2>$this->prices</h2>';
		//var_dump($this->prices);
		//echo '<h2>$this->data:</h2>';
		//var_dump($this->data);
		//echo '<h2>$this->default:</h2>';
		//var_dump($this->default);
	?>
</pre>