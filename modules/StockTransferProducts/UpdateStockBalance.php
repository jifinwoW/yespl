<?php
function UpdateStockBalance($entityData) {
    $data = $entityData->{'data'};
    // print_r($data);
    global $adb;

    $productid  = explode('x',$data['st_product_id'])[1];    // Product ID
    $qty        = (float) $data['st_qty'];   // Quantity to reduce
    $sparerequestid = explode('x',$data['st_tra_req_id'])[1];   // Spare Request ID (fix from your original code)
    $engineerid = explode('x',$data['engineer_id'])[1];   // Engineer ID (fix from your original code)
    $status = $data['stp_status'];   // Status (fix from your original code)
    $service_cordinator = "SELECT servicecordinator_id from vtiger_stocktransfer where stocktransferid = ?";
    $service_cordinator_result = $adb->pquery($service_cordinator, array($sparerequestid));
    $service_cordinator_id = $adb->query_result($service_cordinator_result, 0, 'servicecordinator_id');

    if($status === 'Approved') {
       
        $CheckEngineerStockRecord = "SELECT * from vtiger_engineerstockbalance inner join vtiger_crmentity on vtiger_engineerstockbalance.engineerstockbalanceid = vtiger_crmentity.crmid where sb_product_id = ? and sb_engineer_id = ? and vtiger_crmentity.deleted = 0";
        $CheckEngineerStockRecordResult = $adb->pquery($CheckEngineerStockRecord, array($productid, $engineerid));
        $row = $adb->fetch_array($CheckEngineerStockRecordResult);
        // print_r($row);

        if($CheckEngineerStockRecordResult && $adb->num_rows($CheckEngineerStockRecordResult) > 0) {
            $currentQty = (float) $row['sb_qty'];
            $newQty = $currentQty + $qty;

            $recordId = $row['engineerstockbalanceid'];
            $focus = CRMEntity::getInstance('EngineerStockBalance');
            $focus->retrieve_entity_info($recordId, 'EngineerStockBalance');
            $focus->mode = 'edit';
            $focus->id   = $recordId;
            $focus->column_fields['sb_qty'] = $newQty;
            $focus->save('EngineerStockBalance');
        } else {
            $moduleInstance = CRMEntity::getInstance('EngineerStockBalance');	
            $moduleInstance->column_fields['sb_product_id'] = $productid;
            $moduleInstance->column_fields['sb_engineer_id'] = $engineerid;
            $moduleInstance->column_fields['sb_qty'] = $qty;
            $moduleInstance->save('EngineerStockBalance');
        }

        if($service_cordinator_id){
            $CheckSCStockRecord = "SELECT * from vtiger_scstockbalance inner join vtiger_crmentity on vtiger_scstockbalance.scstockbalanceid = vtiger_crmentity.crmid where scsb_product_id = ? and sb_sc_id = ? and vtiger_crmentity.deleted = 0";
            $CheckSCStockRecordResult = $adb->pquery($CheckSCStockRecord, array($productid, $service_cordinator_id));
            $row = $adb->fetch_array($CheckSCStockRecordResult);
            if($CheckSCStockRecordResult && $adb->num_rows($CheckSCStockRecordResult) > 0) {
                $currentQty = (float) $row['scsb_qty'];
                $newQty = $currentQty - $qty;

                $recordId = $row['scstockbalanceid'];
                $focus = CRMEntity::getInstance('SCStockBalance');
                $focus->retrieve_entity_info($recordId, 'SCStockBalance');
                $focus->mode = 'edit';
                $focus->id   = $recordId;
                $focus->column_fields['scsb_qty'] = $newQty;
                $focus->save('SCStockBalance');
            } else {
                // Handle case where SC stock record doesn't exist, if needed
            }
        }
    }
    
}