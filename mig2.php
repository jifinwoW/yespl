<?php
require_once __DIR__ . '/vendor/autoload.php';

include_once('vtlib/Vtiger/Module.php');
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$moduleInstance = null;
$blockInstance = null;
$fieldInstance = null;
$moduleInstance = Vtiger_Module::getInstance('Tickets');
$blockInstance = Vtiger_Block::getInstance('Call Closing Details', $moduleInstance);
if ($blockInstance) {
    $fieldInstance = Vtiger_Field::getInstance('status_reason', $moduleInstance);
    if (!$fieldInstance) {
        $fieldInstance = new Vtiger_Field();
        $fieldInstance->name = 'status_reason';
        $fieldInstance->label = 'Status Reason';
        $fieldInstance->table = $moduleInstance->basetable;
        $fieldInstance->column = 'status_reason';
        $fieldInstance->uitype = '16';
        $fieldInstance->presence = '0';
        $fieldInstance->typeofdata = 'V~M';
        $fieldInstance->columntype = 'VARCHAR(200)';
        $fieldInstance->defaultvalue = NULL;
        $blockInstance->addField($fieldInstance);
        $fieldInstance->setPicklistValues(array('Engineer Visit', 'Online', 'Force closed', 'Customer Schedule pending','Spare Approval Pending','Engineer Schedule today','Engineer visit pending','Spare in transit-State HUB','Spare in transit-TRC','Spare Pending-TRC','Details Pending'));
        echo "done";
    } else {
        echo "field is already Present --- status_reason in Tickets Module --- <br>";
    }
} else {
    echo " block does not exits --- Call Closing Details -- <br>";
}

