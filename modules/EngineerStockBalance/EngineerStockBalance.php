<?php

include_once 'modules/Vtiger/CRMEntity.php';

class EngineerStockBalance extends Vtiger_CRMEntity
{
        var $table_name = 'vtiger_engineerstockbalance';
        var $table_index = 'engineerstockbalanceid';

        var $customFieldTable = array('vtiger_engineerstockbalancecf', 'engineerstockbalanceid');

        var $tab_name = array('vtiger_crmentity', 'vtiger_engineerstockbalance', 'vtiger_engineerstockbalancecf');

        var $tab_name_index = array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_engineerstockbalance' => 'engineerstockbalanceid',
                'vtiger_engineerstockbalancecf' => 'engineerstockbalanceid'
        );

        var $list_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Quantity' => array('engineerstockbalance', 'sb_qty'),
                'Stock Transfer Name' => array('engineerstockbalance', 'sb_name'),
                'Product' => array('engineerstockbalance', 'sb_product_id'),
                'Assigned To' => array('crmentity', 'smownerid')
        );
        var $list_fields_name = array(
                /* Format: Field Label => fieldname */
                'Quantity' => 'sb_qty',
                'Stock Transfer Name' => 'sb_name',
                'Product' => 'sb_product_id',
                'Assigned To' => 'assigned_user_id',
        );

        // Make the field link to detail view
        var $list_link_field = 'sb_name';

        // For Popup listview and UI type support
        var $search_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Stock Balance Name' => array('engineerstockbalance', 'sb_name'),
                'Assigned To' => array('vtiger_crmentity', 'assigned_user_id'),
        );
        var $search_fields_name = array(
                /* Format: Field Label => fieldname */
                'Stock Balance Name' => 'sb_name',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = array('sb_name');

        // For Alphabetical search
        var $def_basicsearch_col = 'sb_name';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'sb_name';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = array('sb_name', 'assigned_user_id');

        var $default_order_by = 'sb_name';
        var $default_sort_order = 'ASC';
}
