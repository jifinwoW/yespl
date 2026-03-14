<?php
require_once 'vendor/autoload.php';
// Include necessary Vtiger files
include_once 'vtlib/Vtiger/Module.php';
include_once 'vtlib/Vtiger/Field.php';
include_once 'vtlib/Vtiger/Block.php';
include_once 'include/database/PearDatabase.php';
include_once 'modules/Vtiger/Module.php';

// Define the module name
$MODULENAME = 'Engineer';

// Check if the module already exists
$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
    echo "Module already exists - choose a different name.";
} else {
    // Create a new module
    $moduleInstance = new Vtiger_Module();
    $moduleInstance->name = $MODULENAME;
    $moduleInstance->parent = 'Tools'; // Set parent module (you can adjust this if needed)
    $moduleInstance->save();

    echo "Module '$MODULENAME' created successfully.<br>";

    // Set up the database tables for the module
    $moduleInstance->initTables();

    // Create Block for the module
    $block = new Vtiger_Block();
    $block->label = 'LBL_' . strtoupper($moduleInstance->name) . '_INFORMATION';
    $moduleInstance->addBlock($block);

    // Create a Custom Information Block
    $blockcf = new Vtiger_Block();
    $blockcf->label = 'LBL_CUSTOM_INFORMATION';
    $moduleInstance->addBlock($blockcf);

    // Add Fields to the block
    $field1 = new Vtiger_Field();
    $field1->name = 'engineer_code';
    $field1->label = 'Engineer Code';
    $field1->uitype = 2;  // Text Field
    $field1->column = $field1->name;
    $field1->columntype = 'VARCHAR(100)';
    $field1->typeofdata = 'V~M'; // Mandatory field
    $block->addField($field1);

    $field2 = new Vtiger_Field();
    $field2->name = 'engineer_name';
    $field2->label = 'Engineer Name';
    $field2->uitype = 2;
    $field2->column = $field2->name;
    $field2->columntype = 'VARCHAR(255)';
    $field2->typeofdata = 'V~M';
    $block->addField($field2);

    $field3 = new Vtiger_Field();
    $field3->name = 'mobile_no';
    $field3->label = 'Mobile No';
    $field3->uitype = 11;  // Phone Field
    $field3->column = $field3->name;
    $field3->columntype = 'VARCHAR(20)';
    $field3->typeofdata = 'V~M';
    $block->addField($field3);

    $field4 = new Vtiger_Field();
    $field4->name = 'email';
    $field4->label = 'Email';
    $field4->uitype = 13;  // Email Field
    $field4->column = $field4->name;
    $field4->columntype = 'VARCHAR(100)';
    $field4->typeofdata = 'V~M';
    $block->addField($field4);

    $field5 = new Vtiger_Field();
    $field5->name = 'address';
    $field5->label = 'Address';
    $field5->uitype = 19;  // Text Area
    $field5->column = $field5->name;
    $field5->columntype = 'TEXT';
    $field5->typeofdata = 'V~O'; // Optional field
    $block->addField($field5);

    $field6 = new Vtiger_Field();
    $field6->name = 'city';
    $field6->label = 'City';
    $field6->uitype = 2;
    $field6->column = $field6->name;
    $field6->columntype = 'VARCHAR(100)';
    $field6->typeofdata = 'V~O';
    $block->addField($field6);

    $field7 = new Vtiger_Field();
    $field7->name = 'state';
    $field7->label = 'State';
    $field7->uitype = 2;
    $field7->column = $field7->name;
    $field7->columntype = 'VARCHAR(100)';
    $field7->typeofdata = 'V~O';
    $block->addField($field7);

    $field8 = new Vtiger_Field();
    $field8->name = 'pincode';
    $field8->label = 'Pincode';
    $field8->uitype = 2;
    $field8->column = $field8->name;
    $field8->columntype = 'VARCHAR(20)';
    $field8->typeofdata = 'V~O';
    $block->addField($field8);

    // Add common fields (e.g., Assigned To, CreatedTime, ModifiedTime)
    $mfield1 = new Vtiger_Field();
    $mfield1->name = 'assigned_user_id';
    $mfield1->label = 'Assigned To';
    $mfield1->table = 'vtiger_crmentity';
    $mfield1->column = 'smownerid';
    $mfield1->uitype = 53;
    $mfield1->typeofdata = 'V~M';
    $block->addField($mfield1);

    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'CreatedTime';
    $mfield2->label = 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'T~O';
    $mfield2->displaytype = 2;
    $block->addField($mfield2);

    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'ModifiedTime';
    $mfield3->label = 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'T~O';
    $mfield3->displaytype = 2;
    $block->addField($mfield3);

    // Add default filter
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $moduleInstance->addFilter($filter1);
    $filter1->addField($field1)->addField($field2, 1)->addField($field3, 2)->addField($mfield1, 3);

    // Set default sharing for the module
    $moduleInstance->setDefaultSharing();

    echo "Module '$MODULENAME' created with fields and blocks successfully!";
}
?>
