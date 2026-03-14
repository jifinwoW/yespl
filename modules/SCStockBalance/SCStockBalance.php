<?php

include_once 'modules/Vtiger/CRMEntity.php';

class SCStockBalance extends Vtiger_CRMEntity
{
        var $table_name = 'vtiger_scstockbalance';
        var $table_index = 'scstockbalanceid';

        var $customFieldTable = array('vtiger_scstockbalancecf', 'scstockbalanceid');

        var $tab_name = array('vtiger_crmentity', 'vtiger_scstockbalance', 'vtiger_scstockbalancecf');

        var $tab_name_index = array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_scstockbalance' => 'scstockbalanceid',
                'vtiger_scstockbalancecf' => 'scstockbalanceid'
        );

        var $list_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Quantity' => array('scstockbalance', 'scsb_qty'),
                'Stock Transfer Name' => array('scstockbalance', 'scsb_name'),
                'Product' => array('scstockbalance', 'scsb_product_id'),
                'Assigned To' => array('crmentity', 'smownerid')
        );
        var $list_fields_name = array(
                /* Format: Field Label => fieldname */
                'Quantity' => 'scsb_qty',
                'Stock Transfer Name' => 'scsb_name',
                'Product' => 'scsb_product_id',
                'Assigned To' => 'assigned_user_id',
        );

        // Make the field link to detail view
        var $list_link_field = 'scsb_name';

        // For Popup listview and UI type support
        var $search_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Stock Balance Name' => array('scstockbalance', 'scsb_name'),
                'Assigned To' => array('vtiger_crmentity', 'assigned_user_id'),
        );
        var $search_fields_name = array(
                /* Format: Field Label => fieldname */
                'Stock Balance Name' => 'scsb_name',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = array('scsb_name');

        // For Alphabetical search
        var $def_basicsearch_col = 'scsb_name';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'scsb_name';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = array('scsb_name', 'assigned_user_id');

        var $default_order_by = 'scsb_name';
        var $default_sort_order = 'ASC';
}
