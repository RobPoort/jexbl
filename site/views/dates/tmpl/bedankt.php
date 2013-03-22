<?php
defined('_JEXEC') or die('Restricted Access');
jimport('joomla.html.html');
JHtml::_('behavior.tooltip');
JHtml::stylesheet('jbl.css','components/com_jexbooking/css/');

$naw = $this->app->getUserState("option_process_jbl.naw");
$mailTo = $naw['mail'];
?>
<div class="jbl_final_message">
<p>Bedankt voor uw reservering. Een e-mail met uw reservering is verstuurd naar <?php echo $mailTo; ?>. Zodra uw reservering is verwerkt krijgt u van ons bericht.</p>
</div>
