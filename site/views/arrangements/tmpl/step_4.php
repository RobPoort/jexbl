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

$app = JFactory::getApplication();
$mailfrom	= $app->getCfg('sitename');
$state = $app->getUserState("option_jbl");
$form = $app->input->get("jbl_form",null,null);
//TODO bovenstaande filteren, zitten nog overbodige dingen van oude bedankt pagina in
?>
<h1>Overzicht: (step_4).php</h1>

<form method="post" action="">
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
	</div>
	<input type="hidden" name="task" value="arrangements.process" />
</form>
<pre>
	<?php
		var_dump($form); 
	?>
</pre>