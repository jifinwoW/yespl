<?php

include_once 'modules/Vtiger/CRMEntity.php';

class TicketTimeLog extends Vtiger_CRMEntity
{
        var $table_name = 'vtiger_tickettimelog';
        var $table_index = 'tickettimelogid';

        var $customFieldTable = array('vtiger_tickettimelogcf', 'tickettimelogid');

        var $tab_name = array('vtiger_crmentity', 'vtiger_tickettimelog', 'vtiger_tickettimelogcf');

        var $tab_name_index = array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_tickettimelog' => 'tickettimelogid',
                'vtiger_tickettimelogcf' => 'tickettimelogid'
        );

        var $list_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Working Date' => array('TicketTimeLog', 'cf_1163'),
                'Working Start Time' => array('TicketTimeLog', 'cf_1165'),
                'Working End Time' => array('TicketTimeLog', 'cf_1167'),
                'Ticket' => array('TicketTimeLog', 'ttl_ticketid'),
                'Engineer' => array('TicketTimeLog', 'ttl_engineer'),
                'Assigned To' => array('crmentity', 'assigned_user_id')
        );
        var $list_fields_name = array(
                /* Format: Field Label => fieldname */
                'Working Date' => 'cf_1163',  
                'Working Start Time' => 'cf_1165',
                'Working End Time' => 'cf_1167',
                'Ticket' => 'ttl_ticketid',
                'Engineer' => 'ttl_engineer',
                'Assigned To' => 'assigned_user_id'             
        );

        // Make the field link to detail view
        var $list_link_field = 'ttl_no';

        // For Popup listview and UI type support
        var $search_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Time Log Number' => array('TicketTimeLog', 'ttl_no'),
                'Assigned To' => array('vtiger_crmentity', 'assigned_user_id'),
        );
        var $search_fields_name = array(
                /* Format: Field Label => fieldname */
                'Stock Balance Name' => 'ttl_no',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = array('ttl_no');

        // For Alphabetical search
        var $def_basicsearch_col = 'ttl_no';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'ttl_no';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = array('ttl_no', 'assigned_user_id');

        var $default_order_by = 'ttl_no';
        var $default_sort_order = 'ASC';
}
