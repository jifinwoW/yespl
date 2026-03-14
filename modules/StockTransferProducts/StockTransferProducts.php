<?php

include_once 'modules/Vtiger/CRMEntity.php';

class StockTransferProducts extends Vtiger_CRMEntity
{
        var $table_name = 'vtiger_stocktransferproducts';
        var $table_index = 'stocktransferproductsid';

        var $customFieldTable = array('vtiger_stocktransferproductscf', 'stocktransferproductsid');

        var $tab_name = array('vtiger_crmentity', 'vtiger_stocktransferproducts', 'vtiger_stocktransferproductscf');

        var $tab_name_index = array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_stocktransferproducts' => 'stocktransferproductsid',
                'vtiger_stocktransferproductscf' => 'stocktransferproductsid'
        );

        var $list_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Quantity' => array('stocktransferproducts', 'st_qty'),
                'Stock Transfer Name' => array('stocktransferproducts', 'st_name'),
                'Product' => array('stocktransferproducts', 'st_product_id'),
                'Assigned To' => array('crmentity', 'smownerid')
        );
        var $list_fields_name = array(
                /* Format: Field Label => fieldname */
                'Quantity' => 'st_qty',
                'Stock Transfer Name' => 'st_name',
                'Product' => 'st_product_id',
                'Assigned To' => 'assigned_user_id',
        );

        // Make the field link to detail view
        var $list_link_field = 'st_name';

        // For Popup listview and UI type support
        var $search_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Stock Transfer Name' => array('stocktransferproducts', 'st_name'),
                'Assigned To' => array('vtiger_crmentity', 'assigned_user_id'),
        );
        var $search_fields_name = array(
                /* Format: Field Label => fieldname */
                'Stock Transfer Name' => 'st_name',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = array('st_name');

        // For Alphabetical search
        var $def_basicsearch_col = 'st_name';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'st_name';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = array('st_name', 'assigned_user_id');

        var $default_order_by = 'st_name';
        var $default_sort_order = 'ASC';
}
