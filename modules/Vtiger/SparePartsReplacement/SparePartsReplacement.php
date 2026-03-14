<?php

include_once 'modules/Vtiger/CRMEntity.php';

class SparePartsReplacement extends Vtiger_CRMEntity
{
        var $table_name = 'vtiger_sparepartsreplacement';
        var $table_index = 'sparepartsreplacementid';

        var $customFieldTable = array('vtiger_sparepartsreplacementcf', 'sparepartsreplacementid');

        var $tab_name = array('vtiger_crmentity', 'vtiger_sparepartsreplacement', 'vtiger_sparepartsreplacementcf');

        var $tab_name_index = array(
                'vtiger_crmentity' => 'crmid',
                'vtiger_sparepartsreplacement' => 'sparepartsreplacementid',
                'vtiger_sparepartsreplacementcf' => 'sparepartsreplacementid'
        );

        var $list_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Quantity' => array('sparepartsreplacement', 'spr_qty'),
                'Stock Transfer Name' => array('sparepartsreplacement', 'spr_name'),
                'Product' => array('sparepartsreplacement', 'spr_product_id'),
                'Assigned To' => array('crmentity', 'smownerid')
        );
        var $list_fields_name = array(
                /* Format: Field Label => fieldname */
                'Quantity' => 'spr_qty',
                'Stock Transfer Name' => 'spr_name',
                'Product' => 'spr_product_id',
                'Assigned To' => 'assigned_user_id',
        );

        // Make the field link to detail view
        var $list_link_field = 'spr_name';

        // For Popup listview and UI type support
        var $search_fields = array(
                /* Format: Field Label => Array(tablename, columnname) */
                // tablename should not have prefix 'vtiger_'
                'Spare Parts Assignment Name' => array('sparepartsreplacement', 'spr_name'),
                'Assigned To' => array('vtiger_crmentity', 'assigned_user_id'),
        );
        var $search_fields_name = array(
                /* Format: Field Label => fieldname */
                'Spare Parts Assignment Name' => 'spr_name',
                'Assigned To' => 'assigned_user_id',
        );

        // For Popup window record selection
        var $popup_fields = array('spr_name');

        // For Alphabetical search
        var $def_basicsearch_col = 'spr_name';

        // Column value to use on detail view record text display
        var $def_detailview_recname = 'spr_name';

        // Used when enabling/disabling the mandatory fields for the module.
        // Refers to vtiger_field.fieldname values.
        var $mandatory_fields = array('spr_name', 'assigned_user_id');

        var $default_order_by = 'spr_name';
        var $default_sort_order = 'ASC';
}
