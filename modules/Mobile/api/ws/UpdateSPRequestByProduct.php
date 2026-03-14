<?php

class Mobile_WS_UpdateSPRequestByProduct extends Mobile_WS_Controller {
    function process(Mobile_API_Request $request) {
        global $current_user, $adb;

        $response = new Mobile_API_Response();
        $spr_id = $request->get('sparerequestid');
        $productid = $request->get('product_id');
        $status   = $request->get('status');

        if (empty($productid) || empty($status)) {
            $response->setError(101, 'Missing required parameters.');
            return $response;
        }

       
        $checkSql = "SELECT stp_status FROM vtiger_stocktransferproducts 
                     INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_stocktransferproducts.stocktransferproductsid
                     WHERE vtiger_crmentity.deleted = 0 AND stocktransferproductsid = ?";
        $checkRes = $adb->pquery($checkSql, [$productid]);

        if ($adb->num_rows($checkRes) == 0) {
            $response->setError(102, 'Record not found or deleted.');
            return $response;
        }

        $currentStatus = $adb->query_result($checkRes, 0, 'stp_status');

       
        if ($currentStatus === $status) {
            $response->setError(103, "Request is already $status.");
            return $response;
        }

        
        $recordModel = Vtiger_Record_Model::getInstanceById($productid, 'StockTransferProducts');
        if (!empty($recordModel)) {
            $recordModel->set('mode', 'edit');
            $recordModel->set('stp_status', $status);
            $recordModel->set('assigned_user_id', $current_user->id); 
            $recordModel->save();

            $response->setApiSucessMessage('Request Updated Successfully');
            $response->setResult(['record_id' => $productid, 'status' => $status]);
            return $response;
        } else {
            $response->setError(104, 'Not able to update record model.');
            return $response;
        }
    }
}
