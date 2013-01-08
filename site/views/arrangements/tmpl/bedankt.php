<?php
defined('_JEXEC') or die('Restricted Access');
?>
<h1>Bedankt voor uw reservering!</h1>
<p>Een bevestigingsmail is verstuurd naar <?php echo $this->data['mail']; ?></p>
<?php
$app = JFactory::getApplication();
$mailfrom	= $app->getCfg('sitename');
var_dump($mailfrom);
?>