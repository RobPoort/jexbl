<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.html.html');
?>
<pre>
	<?php
		$check = JFactory::getApplication()->input->get('layout');
		$lastStep = JFactory::getApplication()->input->get('step');
		var_dump($check,$lastStep); 
	?>
</pre>
<form method="post" action="">
	<select name="step" onChange="this.form.submit()">
		<option value="1">stap 1</option>
		<option value="2">stap 3</option>
	</select>
	<input type="hidden" name="task" value="arrangements.setStep" />
</form>