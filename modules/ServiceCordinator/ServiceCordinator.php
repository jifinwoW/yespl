<?php

include_once 'modules/Vtiger/CRMEntity.php';

class ServiceCordinator extends Vtiger_CRMEntity {
        var $table_name = 'vtiger_servicecordinator';
        var $table_index= 'servicecordinatorid';

        var $customFieldTable = Array('vtiger_servicecordinatorcf', 'servicecordinatorid');

        var $tab_name = Array('vtiger_crmentity', 'vtiger_servicecordinator', 'vtiger_servicecordinatorcf');

        var $tab_name_index = Array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_servicecordinator' => 'servicecordinatorid',
                'vtiger_servicecordinatorcf'=>'servicecordinatorid');

        var $list_fields = Array (
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Last Name' => Array('servicecordinator', 'lastname'),
                'Assigned To' => Array('crmentity','smownerid')
        );
        var $list_fields_name = Array (
                /* Format: Field Label => fieldname */
                'Last Name' => 'lastname',
                'Assigned To' => 'assigned_user_id',
        );

        // Make the field link to detail view
        var $list_link_field = 'lastname';

        // For Popup listview and UI type support
        var $search_fields = Array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'lastname' => Array('servicecordinator', 'lastname'),
                'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
        );
        var $search_fields_name = Array (
                /* Format: Field Label => fieldname */
                'Last Name' => 'lastname',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = Array ('lastname');

        // For Alphabetical search
        var $def_basicsearch_col = 'lastname';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'lastname';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = Array('lastname','assigned_user_id');

        var $default_order_by = 'lastname';
        var $default_sort_order='ASC';
}