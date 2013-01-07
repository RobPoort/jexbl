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
?>
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
							<td>&nbsp;</td>
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
			<tr>				
				<td style="text-align:right;font-weight:bold;">totaal:</td>
				<td style="font-weight:bold;">&euro;<?php echo number_format($this->total_price, 2, ',','.'); ?></td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</fieldset>
	<form method="post" action="">
		<?php
		if($this->extras){
			?>
			<fieldset class="jbl_form"><legend class="hasTip" title="Hier kunt u nog extra wensen opgeven">Extra's:</legend>
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
					<td class="jbl_label_naw">Achternaam:</td>
					<td><input type="text" name="jbl_form[name]" value="" class="jbl_input_text" /></td>
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
					<td class="jbl_label_naw">e-mail:</td>
					<td><input type="text" name="jbl_form[mail]" value="" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Telefoon:</td>
					<td><input type="text" name="jbl_form[phone]" value="" class="jbl_input_text" /></td>
				</tr>
				<tr>
					<td class="jbl_label_naw">Mobiel:</td>
					<td><input type="text" name="jbl_form[mobile]" value="" class="jbl_input_text" /></td>
				</tr>				
			</table>
		</fieldset>
		<fieldset class="jbl_form" id="button">
			<button class="buttonNext" onClick="this.form.submit()" name="final" value="1">VERZENDEN</button>
		</fieldset>
		<input type="hidden" name="task" value="arrangements.setStep" />
	<input type="hidden" name="step" value="3" />
	</form>
</div>