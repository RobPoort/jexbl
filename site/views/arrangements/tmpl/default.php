<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.html.html');

JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');
?>
<?php if($this->itemsByDate) : ?>
<form method="post" action="">
	<fieldset class="jexSelect">
			<table class="jbl_form_table">
			<?php
				$i = 0;
				foreach($this->itemsByDate as $item){
					
					$start_date = new DateTime($item->start_date);
					$end_date = new DateTime($item->end_date);
					
					$start	= $start_date->format('j - n');
					$end	= $end_date->format('j - n - Y');
					$class = '';
					if(!is_int($i/2)){
						$class = 'odd';
					}
					?>
					<tr class="<?php echo $class; ?>">
						<td style="width:5px;">
							<input type="radio" name="arrangementSelect" value="<?php echo $item->id; ?>" class="<?php echo $class; ?>" />
						</td>
						<td class="td_arr_name">
							<?php echo $item->name; ?>
						</td>
						<td>
						<?php echo $start.'&nbsp;tot&nbsp;'.$end; ?>
						</td>
					</tr>
					<?php
					$i++;
				} 
			?>
		</table>
		<input type="submit" name="buttonNext" class="buttonNext" value="VOLGENDE" />
		<input type="hidden" name="task" value="arrangements.setStep" />
		<input type="hidden" name="step" value="1" />
		</form>
		<form method="post" action="">
	<fieldset class="jexSelect">	
	<input type="submit" name="buttonprev" class="buttonNext" value="VORIGE" />
	</fieldset>
	<input type="hidden" name="task" value="arrangements.setStep" />
	<input type="hidden" name="step" value="datepicker" />
</form>
	</fieldset>

<?php elseif(!$this->itemsByDate) : ?>
<form method="post" action="">
	<fieldset class="jexSelect">
	<p>Helaas, er zijn geen arrangementen met deze startdatum.</p>
	<input type="submit" name="buttonprev" class="buttonNext" value="VORIGE" />
	</fieldset>
	<input type="hidden" name="task" value="arrangements.setStep" />
	<input type="hidden" name="step" value="datepicker" />
</form>
<?php endif; ?>