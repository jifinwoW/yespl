<?php

class Mobile_WS_UpdateSPRequestByProduct extends Mobile_WS_Controller {
    function process(Mobile_API_Request $request) {
        global $current_user, $adb;

        $response = new Mobile_API_Response();
        $spr_id = $request->get('sparerequestid');
        $stocktransferproductsid = $request->get('product_id');
        $status   = $request->get('status');

        

        if (empty($stocktransferproductsid) || empty($status)) {
            $response->setError(101, 'Missing required parameters.');
            return $response;
        }

       
        $checkSql = "SELECT stp_status, st_product_id, st_qty, st_tra_req_id FROM vtiger_stocktransferproducts 
                     INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_stocktransferproducts.stocktransferproductsid
                     WHERE vtiger_crmentity.deleted = 0 AND stocktransferproductsid = ?";
        $checkRes = $adb->pquery($checkSql, [$stocktransferproductsid]);

        if ($adb->num_rows($checkRes) == 0) {
            $response->setError(102, 'Record not found or deleted.');
            return $response;
        }

        $product_id = $adb->query_result($checkRes, 0, 'st_product_id');
        $currentStatus = $adb->query_result($checkRes, 0, 'stp_status');
        $qty = $adb->query_result($checkRes, 0, 'st_qty');
        $sparetransferrequestid = $adb->query_result($checkRes, 0, 'st_tra_req_id');

        $service_cordinator = "SELECT servicecordinator_id from vtiger_stocktransfer where stocktransferid = ?";
        $service_cordinator_result = $adb->pquery($service_cordinator, array($sparetransferrequestid));
        $service_cordinator_id = $adb->query_result($service_cordinator_result, 0, 'servicecordinator_id');

       
        if ($currentStatus === $status) {
            $response->setError(103, "Request is already $status.");
            return $response;
        }

        $CheckSCProductBalance = "SELECT scsb_qty from vtiger_scstockbalance inner join vtiger_crmentity on vtiger_scstockbalance.scstockbalanceid = vtiger_crmentity.crmid where scsb_product_id = ? and sb_sc_id = ? and vtiger_crmentity.deleted = 0";
        $CheckSCProductBalanceResult = $adb->pquery($CheckSCProductBalance, array($product_id, $service_cordinator_id));
        $scProductBalance = 0;
        if($CheckSCProductBalanceResult && $adb->num_rows($CheckSCProductBalanceResult) > 0) {
            $scProductBalance = (float) $adb->query_result($CheckSCProductBalanceResult, 0, 'scsb_qty');
        }

        if($status === 'Approved' && $scProductBalance < $qty) {
            $response->setError(105, "Not enough stock balance for the product.");
            return $response;
        }


        
        $recordModel = Vtiger_Record_Model::getInstanceById($stocktransferproductsid, 'StockTransferProducts');
        if (!empty($recordModel)) {
            $recordModel->set('mode', 'edit');
            $recordModel->set('stp_status', $status);
            $recordModel->set('assigned_user_id', $current_user->id); 
            $recordModel->save();

            $response->setApiSucessMessage('Request Updated Successfully');
            $response->setResult(['record_id' => $stocktransferproductsid, 'status' => $status]);
            return $response;
        } else {
            $response->setError(104, 'Not able to update record model.');
            return $response;
        }
    }
}
