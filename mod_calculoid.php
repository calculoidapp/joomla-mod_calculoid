<?php
/**
 * @author         EasyJoomla.org
 * @copyright      ©2014 EasyJoomla.org
 * @license        http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @package        Joomla
 */
defined('_JEXEC') or die('Restricted access');

// init --------------------------------------------------------------------------------------------
$module_name   = 'mod_calculoid';
$unique_id     = uniqid($module_name . '_');
$document      = JFactory::getDocument();
$calc_id       = (int) $params->get('calc_id', 0);
$show_title    = (int) $params->get('show_title', 1);
$show_desc     = (int) $params->get('show_description', 1);
$calc_key      = trim($params->get('calc_key', 'demo2014'));
$calc_url      = trim($params->get('calc_url', 'https://embed.calculoid.com'));
$calc_path_css = trim($params->get('calc_path_css', 'styles/main.css'));
$calc_path_js  = trim($params->get('calc_path_js', 'scripts/combined.min.js'));
$values_str    = trim($params->get('values', ''));
$uri_js        = JUri::getInstance($calc_url);
$ng_attribute  = (defined('PLG_SYSTEM_CALCULOID_ENABLED') ? '' : ' ng-app="calculoid"');

// Do the thing only if plugin is disabled ---------------------------------------------------------
if (!defined('PLG_SYSTEM_CALCULOID_ENABLED'))
{
	$uri_js->setPath('/' . $calc_path_js);
	$document->addScript($uri_js->toString());

	// Prepare document ----------------------------------------------------------------------------
	if ($params->get('use_css', 1))
	{
		$uri_css = JUri::getInstance($calc_url);
		$uri_css->setPath('/' . $calc_path_css);
		$document->addStyleSheet($uri_css->toString());
	}
}

$init_params = (object) [
	'calcId'          => $calc_id,
	'apiKey'          => $calc_key,
	'showTitle'       => $show_title,
	'showDescription' => $show_desc,
	'values'          => new \StdClass()
];

if ($values_str != '')
{
	$values_str = str_replace(["\r\n", "\r", "&#13;&#10;", "&#10;", "&#13;"], "\n", $values_str); //one field=value per line
	$v_rows     = explode("\n", $values_str);

	foreach ($v_rows as $i => $v_row)
	{
		$pair  = explode('=', $v_row, 2);
		$field = trim($pair[0]);
		$value = trim($pair[1]);

		if ($field == '' or $value == '')
		{
			continue;
		}

		if (strpos($field, 'billing') === 0)
		{
			$billing_fieldname = substr($field, 8);

			if ($billing_fieldname == '')
			{
				continue;
			}

			if (!isset($init_params->values->billing))
			{
				$init_params->values->billing = new \StdClass();
			}

			$init_params->values->billing->$billing_fieldname = $value;
		}
		else
		{
			$init_params->values->$field = $value;
		}
	}
}

// Show tmpl ---------------------------------------------------------------------------------------
echo '<div class="' . $module_name . '" id="' . $unique_id . '">';
require JModuleHelper::getLayoutPath($module_name, 'default');

if ($params->get('show_footer', 1))
{
	echo '<div class="' . $module_name . '_footer"><p class="text-center muted"><small>Module by <a href="http://www.easyjoomla.org?utm_source=' . $module_name . '&utm_medium=footer" target="_blank">EasyJoomla.org</a></small></p></div>';
}
echo '</div>';
