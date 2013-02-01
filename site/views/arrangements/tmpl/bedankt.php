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
?>
<h1>Bedankt voor uw reservering!</h1>
<p>Een bevestigingsmail is verstuurd naar <?php echo $this->data['mail']; ?></p>
<?php
$app = JFactory::getApplication();
$mailfrom	= $app->getCfg('sitename');
$state = $app->getUserState("option_jbl");
$form = $app->input->get("jbl_form",null,null);
?>

<pre>
	<?php
		var_dump($form); 
	?>
</pre>