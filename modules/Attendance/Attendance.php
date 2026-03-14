<?php

include_once 'modules/Vtiger/CRMEntity.php';

class Attendance extends Vtiger_CRMEntity {
        var $table_name = 'vtiger_attendance';
        var $table_index= 'attendanceid';

        var $customFieldTable = Array('vtiger_attendancecf', 'attendanceid');

        var $tab_name = Array('vtiger_crmentity', 'vtiger_attendance', 'vtiger_attendancecf');

        var $tab_name_index = Array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_attendance' => 'attendanceid',
                'vtiger_attendancecf'=>'attendanceid');

        var $list_fields = Array (
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Latitide' => Array('attendance', 'latitude'),
                'Assigned To' => Array('crmentity','smownerid')
        );
        var $list_fields_name = Array (
                /* Format: Field Label => fieldname */
                'Latitide' => 'latitude',
                'Assigned To' => 'assigned_user_id',
        );

        // Make the field link to detail view
        var $list_link_field = 'latitude';

        // For Popup listview and UI type support
        var $search_fields = Array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Latitide' => Array('attendance', 'latitude'),
                'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
        );
        var $search_fields_name = Array (
                /* Format: Field Label => fieldname */
                'Latitide' => 'latitude',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = Array ('latitude');

        // For Alphabetical search
        var $def_basicsearch_col = 'latitude';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'latitude';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = Array('latitude','assigned_user_id');

        var $default_order_by = 'latitude';
        var $default_sort_order='ASC';
}