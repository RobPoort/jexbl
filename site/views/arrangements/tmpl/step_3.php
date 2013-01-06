<?php
defined('_JEXEC') or die('Restricted Access');
?>
<pre>
	<?php
		
		$app = JFactory::getApplication();
		$state = $app->getUserState('option_jbl');
		var_dump($state); 
	?>
</pre>