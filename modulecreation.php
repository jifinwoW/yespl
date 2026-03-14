<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'vtlib/Vtiger/Module.php';
include_once 'vtlib/Vtiger/Package.php';
include_once 'includes/main/WebUI.php';
include_once 'include/Webservices/Utils.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'TicketTimeLog';
$moduleLabel = 'Ticket Work Time Log';


$moduleInstance = new Vtiger_Module();
$moduleInstance->name = $MODULENAME;
$moduleInstance->parent = 'SUPPORT';
$moduleInstance->save();

$moduleInstance->label = $moduleLabel;
$moduleInstance->customized = 1;
$moduleInstance->save();

$moduleInstance->initTables();
$moduleInstance->initWebservice();

$block1 = new Vtiger_Block();
$block1->label = 'LBL_TTL_DETAILS';
$moduleInstance->addBlock($block1);

$fields = [
    [
        'name' => 'ttl_no',
        'label' => 'Time Log Number',
        'uitype' => 1,
        'columntype' => 'VARCHAR(255)',
        'typeofdata' => 'V~O'
    ],
    [
        'name' => 'time_in',
        'label' => 'Time In',
        'uitype' => 70,
        'columntype' => 'datetime',
        'typeofdata' => 'DT~O'
    ],
    [
        'name' => 'time_out',
        'label' => 'Time Out',
        'uitype' => 70,
        'columntype' => 'datetime',
        'typeofdata' => 'DT~O'
    ],
];

foreach ($fields as $f) {
    $field = new Vtiger_Field();
    $field->name = $f['name'];
    $field->label = $f['label'];
    $field->table = $moduleInstance->basetable;
    $field->column = $f['name'];
    $field->columntype = $f['columntype'];
    $field->uitype = $f['uitype'];
    $field->displaytype = 1;
    $field->quickcreate = 0;
    $field->presence = 2;
    $field->typeofdata = $f['typeofdata'];
    $block1->addField($field);
}



$relFields = [
    ['name' => 'ttl_ticketid', 'label' => 'Ticket', 'relatedto' => 'Tickets'],
    ['name' => 'ttl_engineer', 'label' => 'Engineer', 'relatedto' => 'Engineer']
    // ['name' => 'sb_sc_id', 'label' => 'Service Cordinator', 'relatedto' => 'ServiceCordinator']
];

foreach ($relFields as $rf) {
    $field = new Vtiger_Field();
    $field->name = $rf['name'];
    $field->label = $rf['label'];
    $field->table = $moduleInstance->basetable;
    $field->column = $rf['name'];
    $field->columntype = 'INT(11)';
    $field->uitype = 10;
    $field->displaytype = 1;
    $field->presence = 2;
    $field->quickcreate = 0;
    $field->typeofdata = 'I~O';
    $block1->addField($field);
    $field->setRelatedModules([$rf['relatedto']]);
}

$assignUser = new Vtiger_Field();
$assignUser->name = 'assigned_user_id';
$assignUser->label = 'Assigned To';
$assignUser->table = 'vtiger_crmentity';
$assignUser->column = 'smownerid';
$assignUser->uitype = 53;
$assignUser->displaytype = 1;
$assignUser->presence = 2;
$assignUser->typeofdata = 'V~M';
$block1->addField($assignUser);

$createdField = new Vtiger_Field();
$createdField->name = 'createdtime';
$createdField->label = 'Created Time';
$createdField->table = 'vtiger_crmentity';
$createdField->column = 'createdtime';
$createdField->displaytype = 2;
$createdField->uitype = 70;
$createdField->typeofdata = 'DT~O';
$block1->addField($createdField);

$modifiedField = new Vtiger_Field();
$modifiedField->name = 'modifiedtime';
$modifiedField->label = 'Modified Time';
$modifiedField->table = 'vtiger_crmentity';
$modifiedField->column = 'modifiedtime';
$modifiedField->displaytype = 2;
$modifiedField->uitype = 70;
$modifiedField->typeofdata = 'DT~O';
$block1->addField($modifiedField);

$filter = new Vtiger_Filter();
$filter->name = 'All';
$filter->isdefault = true;
$moduleInstance->addFilter($filter);

$moduleInstance->setDefaultSharing('Public');
Settings_MenuEditor_Module_Model::addModuleToApp($moduleInstance->name, $moduleInstance->parent);

$parentModule = Vtiger_Module::getInstance('Tickets');
$childModule = Vtiger_Module::getInstance('TicketTimeLog');
$parentModule->setRelatedList($childModule, 'Ticket Time Log', ['ADD', 'SELECT'], 'get_dependents_list');


$moduleDir = "modules/{$moduleInstance->name}";
if (!is_dir($moduleDir)) {
    mkdir($moduleDir, 0777, true);
    echo $moduleInstance->name . " module created successfully.";
} else {
    echo $moduleInstance->name . " module already exists.";
}
