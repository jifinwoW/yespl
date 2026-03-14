<?php
require_once __DIR__ . '/vendor/autoload.php';

include_once('vtlib/Vtiger/Module.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


$moduleInstance = Vtiger_Module::getInstance('Tickets');
$blockInstance = Vtiger_Block::getInstance('LBL_TICKETS_INFORMATION', $moduleInstance);

$fieldInstance = Vtiger_Field::getInstance('amc_id', $moduleInstance);

if (!$fieldInstance && $blockInstance) {
        $fieldInstance = new Vtiger_Field();
    $fieldInstance->name = "amc_id";
    $fieldInstance->label = "AMC";
    $fieldInstance->table = $moduleInstance->basetable;
    $fieldInstance->column = "amc_id";
    $fieldInstance->columntype = 'VARCHAR(200)';
    $fieldInstance->uitype = 10;
    $fieldInstance->displaytype = 1;
    $fieldInstance->presence = 2;
    $fieldInstance->quickcreate = 0;
    $fieldInstance->typeofdata = 'V~O';
    $blockInstance->addField($fieldInstance);
    $fieldInstance->setRelatedModules(['HelpDesk']);
    echo "Field created.";
} else {
    echo "Field already exists or block not found.";
}

