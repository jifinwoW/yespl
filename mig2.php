<?php
require_once __DIR__ . '/vendor/autoload.php';

include_once('vtlib/Vtiger/Module.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$moduleInstance = null;
$blockInstance = null;
$fieldInstance = null;
$moduleInstance = Vtiger_Module::getInstance('HelpDesk');
$blockInstance = Vtiger_Block::getInstance('LBL_TICKET_INFORMATION', $moduleInstance);
if ($blockInstance) {
    $fieldInstance = Vtiger_Field::getInstance('amc_end_date', $moduleInstance);
    if (!$fieldInstance) {
        $fieldInstance = new Vtiger_Field();
        $fieldInstance->name = 'amc_end_date';
        $fieldInstance->label = 'AMC End Date';
        $fieldInstance->table = $moduleInstance->basetable;
        $fieldInstance->column = 'amc_end_date';
        $fieldInstance->uitype = 5;
        $fieldInstance->presence = '0';
        $fieldInstance->typeofdata = 'D~O';
        $fieldInstance->columntype = 'DATE';
        $fieldInstance->defaultvalue = NULL;
        $blockInstance->addField($fieldInstance);
    } else {
        echo "field is already Present --- amc_end_date in HelpDesk Module --- <br>";
    }
} else {
    echo " block does not exits --- LBL_TICKET_INFORMATION -- <br>";
}

