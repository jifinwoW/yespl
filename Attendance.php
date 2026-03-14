<?php
include_once 'vendor/autoload.php';
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'Attendance';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance || file_exists('modules/'.$MODULENAME)) {
        echo "Module already present - choose a different name.";
} else {
        $moduleInstance = new Vtiger_Module();
        $moduleInstance->name = $MODULENAME;
        $moduleInstance->parent= 'Marketing';
        $moduleInstance->save();

        // Schema Setup
        $moduleInstance->initTables();

        // Field Setup
        $block = new Vtiger_Block();
        $block->label = 'LBL_'. strtoupper($moduleInstance->name) . '_INFORMATION';
        $moduleInstance->addBlock($block);

        $blockcf = new Vtiger_Block();
        $blockcf->label = 'LBL_CUSTOM_INFORMATION';
        $moduleInstance->addBlock($blockcf);

        $field1  = new Vtiger_Field();
        $field1->name = 'latitude';
        $field1->label= 'Latitude';
        $field1->uitype= 2;
        $field1->column = $field1->name;
        $field1->columntype = 'VARCHAR(255)';
        $field1->typeofdata = 'V~O';
        $block->addField($field1);

        $field11  = new Vtiger_Field();
        $field11->name = 'longitude';
        $field11->label= 'Longitude';
        $field11->uitype= 2;
        $field11->column = $field111->name;
        $field11->columntype = 'VARCHAR(255)';
        $field11->typeofdata = 'V~O';
        $block->addField($field11);

        $moduleInstance->setEntityIdentifier($field1);

        $field2  = new Vtiger_Field();
        $field2->name = 'checkindate';
        $field2->label= 'Check In Date';
        $field2->uitype= 5;
        $field2->column = $field2->name;
        $field2->columntype = 'Date';
        $field2->typeofdata = 'D~O';
        $block->addField($field2);

        $field22  = new Vtiger_Field();
        $field22->name = 'checkintime';
        $field22->label= 'Check In Time';
        $field22->uitype= 14;
        $field22->column = $field22->name;
        $field22->columntype = 'TIME';
        $field22->typeofdata = 'T~O';
        $block->addField($field22);

        $field3  = new Vtiger_Field();
        $field3->name = 'checkoutdate';
        $field3->label= 'Check Out Date';
        $field3->uitype= 5;
        $field3->column = $field3->name;
        $field3->columntype = 'Date';
        $field3->typeofdata = 'D`O';
        $block->addField($field3);

        $field33  = new Vtiger_Field();
        $field33->name = 'checkouttime';
        $field33->label= 'Check Out Time';
        $field33->uitype= 14;
        $field33->column = $field33->name;
        $field33->columntype = 'TIME';
        $field33->typeofdata = 'T~O';
        $block->addField($field33);        

        $field34 = new Vtiger_Field();
        $field34->name = 'description';
        $field34->label= 'Description';
        $field34->uitype= 19;
        $field34->column = 'description';
        $field34->table = 'vtiger_crmentity';
        $blockcf->addField($field34);

        // Recommended common fields every Entity module should have (linked to core table)
        $mfield1 = new Vtiger_Field();
        $mfield1->name = 'assigned_user_id';
        $mfield1->label = 'Assigned To';
        $mfield1->table = 'vtiger_crmentity';
        $mfield1->column = 'smownerid';
        $mfield1->uitype = 53;
        $mfield1->typeofdata = 'V~M';
        $block->addField($mfield1);

        $mfield2 = new Vtiger_Field();
        $mfield2->name = 'createdtime';
        $mfield2->label= 'Created Time';
        $mfield2->table = 'vtiger_crmentity';
        $mfield2->column = 'createdtime';
        $mfield2->uitype = 70;
        $mfield2->typeofdata = 'DT~O';
        $mfield2->displaytype= 2;
        $block->addField($mfield2);

        $mfield3 = new Vtiger_Field();
        $mfield3->name = 'modifiedtime';
        $mfield3->label= 'Modified Time';
        $mfield3->table = 'vtiger_crmentity';
        $mfield3->column = 'modifiedtime';
        $mfield3->uitype = 70;
        $mfield3->typeofdata = 'DT~O';
        $mfield3->displaytype= 2;
        $block->addField($mfield3);

        /* NOTE: Vtiger 7.1.0 onwards */
        $mfield4 = new Vtiger_Field();
        $mfield4->name = 'source';
        $mfield4->label = 'Source';
        $mfield4->table = 'vtiger_crmentity';
        $mfield4->displaytype = 2; // to disable field in Edit View
        $mfield4->quickcreate = 3;
        $mfield4->masseditable = 0;
        $block->addField($mfield4);

        $mfield5 = new Vtiger_Field();
        $mfield5->name = 'starred';
        $mfield5->label = 'starred';
        $mfield5->table = 'vtiger_crmentity_user_field';
        $mfield5->displaytype = 6;
        $mfield5->uitype = 56;
        $mfield5->typeofdata = 'C~O';
        $mfield5->quickcreate = 3;
        $mfield5->masseditable = 0;
        $block->addField($mfield5);

        $mfield6 = new Vtiger_Field();
        $mfield6->name = 'tags';
        $mfield6->label = 'tags';
        $mfield6->displaytype = 6;
        $mfield6->columntype = 'VARCHAR(1)';
        $mfield6->quickcreate = 3;
        $mfield6->masseditable = 0;
        $block->addField($mfield6);
        /* End 7.1.0 */

        // Filter Setup
        $filter1 = new Vtiger_Filter();
        $filter1->name = 'All';
        $filter1->isdefault = true;
        $moduleInstance->addFilter($filter1);
        $filter1->addField($field1)->addField($field2, 1)->addField($field3, 2)->addField($mfield1, 3);

        // Sharing Access Setup
        $moduleInstance->setDefaultSharing();

        // Webservice Setup
        $moduleInstance->initWebservice();

        mkdir('modules/'.$MODULENAME);
        echo "OK\n";
}