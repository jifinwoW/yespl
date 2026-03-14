<?php

include_once 'modules/Vtiger/CRMEntity.php';

class SparePartsAssignment extends Vtiger_CRMEntity
{
        var $table_name = 'vtiger_sparepartsassignment';
        var $table_index = 'sparepartsassignmentid';

        var $customFieldTable = array('vtiger_sparepartsassignmentcf', 'sparepartsassignmentid');

        var $tab_name = array('vtiger_crmentity', 'vtiger_sparepartsassignment', 'vtiger_sparepartsassignmentcf');

        var $tab_name_index = array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_sparepartsassignment' => 'sparepartsassignmentid',
                'vtiger_sparepartsassignmentcf' => 'sparepartsassignmentid'
        );

        var $list_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Quantity' => array('sparepartsassignment', 'spa_qty'),
                'Stock Transfer Name' => array('sparepartsassignment', 'spa_name'),
                'Product' => array('sparepartsassignment', 'spa_product_id'),
                'Assigned To' => array('crmentity', 'smownerid')
        );
        var $list_fields_name = array(
                /* Format: Field Label => fieldname */
                'Quantity' => 'spa_qty',
                'Stock Transfer Name' => 'spa_name',
                'Product' => 'spa_product_id',
                'Assigned To' => 'assigned_user_id',
        );

        // Make the field link to detail view
        var $list_link_field = 'spa_name';

        // For Popup listview and UI type support
        var $search_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Spare Parts Assignment Name' => array('sparepartsassignment', 'spa_name'),
                'Assigned To' => array('vtiger_crmentity', 'assigned_user_id'),
        );
        var $search_fields_name = array(
                /* Format: Field Label => fieldname */
                'Spare Parts Assignment Name' => 'spa_name',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = array('spa_name');

        // For Alphabetical search
        var $def_basicsearch_col = 'spa_name';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'spa_name';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = array('spa_name', 'assigned_user_id');

        var $default_order_by = 'spa_name';
        var $default_sort_order = 'ASC';
}
