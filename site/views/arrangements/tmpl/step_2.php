<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.html.html');
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
					<td class="jbl_form_left"><label>Aantal personen:&nbsp;</label></td><td class="jbl_form_right">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="number_of_persons" value="2" class="jbl_input_number" /></td>				
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
	<fieldset class="jbl_form" id="jbl_attribs_has_price">
		<?php
			if($attribs){
			?>
			<table class="jbl_form_table">
			<?php
				foreach($attribs as $attrib){
					if($attrib->has_number){
						?>
							<tr>
							<td class="jbl_form_checkbox">&nbsp;</td><td class="jbl_form_left"><label><?php echo $attrib->name; ?></label></td><td class="jbl_form_right">&nbsp;x&nbsp;<input type="text" name="<?php echo $attrib->name; ?>" value="" class="jbl_input_number" /></td>
							</tr>
						<?php
					} else{
						?>
							<tr>
							<td class="jbl_form_checkbox"><input type="checkbox" class="jbl_input_checkbox" /></td><td class="jbl_form_left"><label><?php echo $attrib->name; ?>&nbsp;</label></td><td class="jbl_form_right">&nbsp;</td>
							</tr>
						<?php
					}
				}
			?>
			</table>
			<?php
			}
		?>
	</fieldset>
	<fieldset class="jbl_form" id="button">
		<button class="buttonNext" onClick="this.form.submit()">VOLGENDE</button>
	</fieldset>	
	
	<input type="hidden" name="task" value="arrangements.setStep" />
	<input type="hidden" name="step" value="2" />
</form>