<?php
require_once __DIR__ . '/vendor/autoload.php';

include_once('vtlib/Vtiger/Module.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$moduleInstance = null;
$blockInstance = null;
$fieldInstance = null;
$moduleInstance = Vtiger_Module::getInstance('Tickets   ');
$blockInstance = Vtiger_Block::getInstance('Call Closing Details', $moduleInstance);
if ($blockInstance) {
    $fieldInstance = Vtiger_Field::getInstance('g_data_version', $moduleInstance);
    if (!$fieldInstance) {
         $field = new Vtiger_Field();
        $field->name = 'g_data_version';
        $field->column = 'g_data_version';
        $field->uitype = 1;
        $field->table = $moduleInstance->basetable;
        $field->label = 'G Data Version';
        $field->summaryfield = 0;
        $field->readonly = 0;
        $field->presence = 2;
        $field->typeofdata = 'V~O';
        $field->columntype = 'VARCHAR(50)';
        $field->quickcreate = 3;
        $field->displaytype = 1;
        $field->masseditable = 1;
        $field->defaultvalue = '0';
        $blockInstance->addField($field);
        // $field->setRelatedModules(['Contacts']);
        echo "done";
    } else {
        echo "field is already Present --- status_reason in Tickets Module --- <br>";
    }
} else {
    echo " block does not exits --- Call Closing Details -- <br>";
}

