<?php
function UpdateStockBalance($entityData) {
    $data = $entityData->{'data'};
    require_once('modules/Users/Users.php');
    require_once('include/utils/utils.php');
    require_once('data/CRMEntity.php');
    global $adb;

    $spa_engineer_id = $data['spa_engineer_id'];
    $spa_sc_id       = $data['spa_sc_id'];

    if (empty($spa_engineer_id) && empty($spa_sc_id)) {
        return;
    }

    $product_id = explode('x', $data['spa_product_id']);
    $product_id = $product_id[1] ?? '';

    if (empty($product_id)) {
        return;
    }

    // Identify module/table/columns
    if (!empty($spa_engineer_id)) {
        $FocusModule = "EngineerStockBalance";
        $table_name  = "vtiger_engineerstockbalance";
        $prod_column = "sb_product_id";
        $userColumn  = "sb_engineer_id";
        $qtyColumn   = "sb_qty";
		$pkField = strtolower($FocusModule) . 'id';
        $userId      = explode('x', $spa_engineer_id)[1];
    } else {
        $FocusModule = "SCStockBalance";
        $table_name  = "vtiger_scstockbalance";
        $prod_column = "scsb_product_id";
        $userColumn  = "sb_sc_id";
        $qtyColumn   = "scsb_qty";
		$pkField = strtolower($FocusModule) . 'id';
        $userId      = explode('x', $spa_sc_id)[1];
    }

    $qtyChange = (int)($data['spa_qty'] ?? 0);
    if ($qtyChange === 0) {
        return; // no point in doing anything
    }

    // Check if record exists
    $checkQuery = "SELECT {$pkField} FROM {$table_name}
                   INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = {$pkField}
                   WHERE vtiger_crmentity.deleted = 0
                   AND {$prod_column} = ? AND {$userColumn} = ?";
    $checkRes = $adb->pquery($checkQuery, [$product_id, $userId]);

    if ($adb->num_rows($checkRes) > 0) {
        // Update existing record
       	$recordId = $adb->query_result($checkRes, 0, $pkField);
        $focus = CRMEntity::getInstance($FocusModule);
        $focus->retrieve_entity_info($recordId, $FocusModule);
        $focus->mode = 'edit';
        $focus->id   = $recordId;
        $currentQty = (int)$focus->column_fields[$qtyColumn];
        $focus->column_fields[$qtyColumn] = $currentQty + $qtyChange;
        $focus->save($FocusModule);
    } else {
        // Create new record
        $focus = CRMEntity::getInstance($FocusModule);	
        $focus->column_fields[$prod_column] = $product_id;	
        $focus->column_fields[$userColumn]  = $userId;
        $focus->column_fields[$qtyColumn]   = $qtyChange;
        $focus->save($FocusModule);
    }
}
