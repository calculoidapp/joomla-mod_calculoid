<?php
/**
 * @author         EasyJoomla.org
 * @copyright      ©2014 EasyJoomla.org
 * @license        http://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @package        Joomla
 */
defined('_JEXEC') or die('Restricted access'); ?>
<div<?php echo $ng_attribute; ?> ng-controller="CalculoidMainCtrl" ng-init="init(<?php echo htmlentities(json_encode($init_params)); ?>)" ng-include="load()"></div>