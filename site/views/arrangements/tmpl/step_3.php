<?php
defined('_JEXEC') or die('Restricted Access');
?>
<pre>
	<?php
		$check = JFactory::getApplication()->input->get('layout');
		$lastStep = JFactory::getApplication()->input->get('step');
		var_dump($check,$lastStep);
		echo '<hr />';
		$app = JFactory::getApplication();
		$state = $app->getUserState('option_jbl');
		var_dump($state); 
	?>
</pre>