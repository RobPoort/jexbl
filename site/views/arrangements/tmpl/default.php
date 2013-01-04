<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.html.html');

JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');
?>

<form method="post" action="">
	<fieldset class="jexSelect">
		<select name="arrangementSelect" onChange="this.form.submit()">
			<option value=""> -- Kies een arrangement -- </option>
			<?php
				$i = 0;
				foreach($this->items as $item){
					$class = '';
					if(!is_int($i/2)){
						$class = 'odd';
					}
					?>
					<option value="<?php echo $item->id; ?>" class="<?php echo $class; ?>" ><?php echo $item->name.':&nbsp;van&nbsp;'.$item->start_date.'&nbsp;tot&nbsp;'.$item->end_date; ?></option>
					<?php
					$i++;
				} 
			?>
		</select>
		<input type="hidden" name="task" value="" />
	</fieldset>
</form>
<hr />
<pre>
<?php
$rob = $this->input->get('arrangementSelect');
$choose = $this->input->get('choose');
var_dump($choose);
?>
</pre>