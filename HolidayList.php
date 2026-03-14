<?php
include_once 'vendor/autoload.php';
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;
$MODULENAME = 'HolidayList';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists("modules/$MODULENAME")) {
    echo "Module already present - choose a different name.";
    exit;
}

// Create new module
$moduleInstance = new Vtiger_Module();
$moduleInstance->name = $MODULENAME;
$moduleInstance->parent = 'MARKETING';
$moduleInstance->save();
$moduleInstance->initTables();

// Block
$block = new Vtiger_Block();
$block->label = 'LBL_HOLIDAYLIST_INFORMATION';
$moduleInstance->addBlock($block);

// Custom block if needed
$blockcf = new Vtiger_Block();
$blockcf->label = 'LBL_CUSTOM_INFORMATION';
$moduleInstance->addBlock($blockcf);

// Name field
$field1 = new Vtiger_Field();
$field1->name = 'holidayname';
$field1->label = 'Holiday Name';
$field1->uitype = 2;
$field1->column = 'holidayname';
$field1->columntype = 'VARCHAR(255)';
$field1->typeofdata = 'V~M';
$block->addField($field1);
$moduleInstance->setEntityIdentifier($field1);

// Holiday date
$field2 = new Vtiger_Field();
$field2->name = 'holidaydate';
$field2->label = 'Holiday Date';
$field2->uitype = 5;
$field2->column = 'holidaydate';
$field2->columntype = 'DATE';
$field2->typeofdata = 'D~M';
$block->addField($field2);

// Day of the week
$field3 = new Vtiger_Field();
$field3->name = 'dayname';
$field3->label = 'Day';
$field3->uitype = 1;
$field3->column = 'dayname';
$field3->columntype = 'VARCHAR(20)';
$field3->typeofdata = 'V~O';
$block->addField($field3);

// Reason
$field4 = new Vtiger_Field();
$field4->name = 'reason';
$field4->label = 'Reason';
$field4->uitype = 19;
$field4->column = 'reason';
$field4->columntype = 'TEXT';
$field4->typeofdata = 'V~O';
$block->addField($field4);

// Type (public, optional, etc.)
$field5 = new Vtiger_Field();
$field5->name = 'holidaytype';
$field5->label = 'Type';
$field5->uitype = 15;
$field5->column = 'holidaytype';
$field5->columntype = 'VARCHAR(100)';
$field5->typeofdata = 'V~O';
$field5->setPicklistValues(['Public', 'Optional', 'Company Specific']);
$block->addField($field5);

// Year
$field6 = new Vtiger_Field();
$field6->name = 'holidayyear';
$field6->label = 'Year';
$field6->uitype = 7;
$field6->column = 'holidayyear';
$field6->columntype = 'INT(4)';
$field6->typeofdata = 'I~M';
$block->addField($field6);

// Location (optional)
$field7 = new Vtiger_Field();
$field7->name = 'location';
$field7->label = 'Location';
$field7->uitype = 1;
$field7->column = 'location';
$field7->columntype = 'VARCHAR(100)';
$field7->typeofdata = 'V~O';
$block->addField($field7);

// Is Active
$field8 = new Vtiger_Field();
$field8->name = 'isactive';
$field8->label = 'Active';
$field8->uitype = 56;
$field8->column = 'isactive';
$field8->columntype = 'TINYINT(1)';
$field8->typeofdata = 'C~O';
$block->addField($field8);

// Description
$field9 = new Vtiger_Field();
$field9->name = 'description';
$field9->label = 'Description';
$field9->table = 'vtiger_crmentity';
$field9->column = 'description';
$field9->uitype = 19;
$field9->typeofdata = 'V~O';
$blockcf->addField($field9);

$createdTime = new Vtiger_Field();
$createdTime->name = 'createdtime';
$createdTime->label = 'Created Time';
$createdTime->table = 'vtiger_crmentity';
$createdTime->column = 'createdtime';
$createdTime->uitype = 70;
$createdTime->typeofdata = 'DT~O';
$createdTime->displaytype = 2; // Read-only in UI
$block->addField($createdTime);

$modifiedTime = new Vtiger_Field();
$modifiedTime->name = 'modifiedtime';
$modifiedTime->label = 'Modified Time';
$modifiedTime->table = 'vtiger_crmentity';
$modifiedTime->column = 'modifiedtime';
$modifiedTime->uitype = 70;
$modifiedTime->typeofdata = 'DT~O';
$modifiedTime->displaytype = 2;
$block->addField($modifiedTime);

$assignedTo = new Vtiger_Field();
$assignedTo->name = 'assigned_user_id';
$assignedTo->label = 'Assigned To';
$assignedTo->table = 'vtiger_crmentity';
$assignedTo->column = 'smownerid';
$assignedTo->uitype = 53;
$assignedTo->typeofdata = 'V~M';
$block->addField($assignedTo);


// Filter setup
$filter1 = new Vtiger_Filter();
$filter1->name = 'All';
$filter1->isdefault = true;
$moduleInstance->addFilter($filter1);
$filter1->addField($field1, 1)
    ->addField($field2, 2)
    ->addField($field5, 3)
    ->addField($field6, 4);

// Access + webservice + directory
$moduleInstance->setDefaultSharing();
$moduleInstance->initWebservice();

mkdir("modules/$MODULENAME");
echo "HolidayList Module created successfully.\n";
