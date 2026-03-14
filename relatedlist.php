<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'vtlib/Vtiger/Module.php';
include_once 'vtlib/Vtiger/Package.php';
include_once 'includes/main/WebUI.php';
include_once 'include/Webservices/Utils.php';

$parentModule = Vtiger_Module::getInstance('Engineer');
$childModule = Vtiger_Module::getInstance('SparePartsAssignment');
$parentModule->setRelatedList($childModule, 'Spare Part List', ['ADD', 'SELECT'], 'get_dependents_list');

$parentModule = Vtiger_Module::getInstance('ServiceCordinator');
$childModule = Vtiger_Module::getInstance('SparePartsAssignment');
$parentModule->setRelatedList($childModule, 'Spare Part List', ['ADD', 'SELECT'], 'get_dependents_list');