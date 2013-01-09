<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

//alleen omdat het minder typen is :)
$item = $this->item;
$attribs = $this->attribs;
?>
<form action="" method="post">
	<fieldset class="jbl_form" id="jbl_has_number"><legend><?php echo ucfirst($item->name);?></legend>
		<table class="jbl_form_table">
			<tr>
			<td class="jbl_form_checkbox">&nbsp;&nbsp;&nbsp;</td>
			<?php
				if($item->is_pp){
					?>				
					<td class="jbl_form_left"><label>Aantal personen:&nbsp;</label></td><td class="jbl_form_right">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="jbl_form[number_pp]" value="2" class="jbl_input_number" /></td>				
					<?php
				} else{
					?>					
					<td class="jbl_form_left"><label>Prijs:</label></td><td class="jbl_form_right">&euro;&nbsp;<?php echo number_format($item->price, 2, ',','.'); ?></td>					
					<?php
				}
			?>
			</tr>
		</table>
	</fieldset>	
		<?php
			if($attribs){
			?>
			<fieldset class="jbl_form" id="jbl_attribs_has_price">
				<table class="jbl_form_table">
				<?php
					foreach($attribs as $attrib){
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
			if($this->special_attribs['not_required']){
				?>
				<fieldset class="jbl_form" id="jbl_attribs_has_price">
					<table class="jbl_form_table">
						<?php
							foreach($this->special_attribs['not_required'] as $attrib){
								if($attrib->has_number){
									?>
										<tr>
										<td class="jbl_form_checkbox">&nbsp;</td><td class="jbl_form_left"><label <?php if($attrib->desc) : ?>class="hasTip" title="<?php echo $attrib->desc; ?>" <?php endif; ?>><?php echo $attrib->name; ?></label></td><td class="jbl_form_right">&nbsp;x&nbsp;<input type="text" name="jbl_form[special][special_number][<?php echo $attrib->id; ?>]" value="0" class="jbl_input_number" /></td>
										</tr>
									<?php
								} else{
									?>
										<tr>
										<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" name="jbl_form[special][special_checked][<?php echo $attrib->id; ?>]" value="1" /></td><td class="jbl_form_left"><label <?php if($attrib->desc) : ?>class="hasTip" title="<?php echo $attrib->desc; ?>" <?php endif; ?>><?php echo $attrib->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
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
	<fieldset class="jbl_form" id="button">
		<input type="hidden" name="task" value="arrangements.setStep" />
		<input type="hidden" name="step" value="2" />
		<?php
			if($this->special_attribs['required']){
				foreach($this->special_attribs['required'] as $attrib){
					?>
					<input type="hidden" name="jbl_form[special][special_required][<?php echo $attrib->id;  ?>]" value="1" />
					<?php
				}
			}
		?>
		<button class="buttonNext" onClick="this.form.submit()">VOLGENDE</button>
		</form>
		<div class="clear">&nbsp;</div>
		<form method="post" action="">
		<button class="buttonNext" onClick="this.form.submit()" >VORIGE</button>
		<input type="hidden" name="task" value="arrangements.setStep" />
		<input type="hidden" name="step" value="0" />
		</form>
	</fieldset>
	<pre>
		<?php var_dump($this->special_attribs); ?>
	</pre>