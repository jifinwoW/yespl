<?php

class Mobile_WS_UpdateSPRequest extends Mobile_WS_Controller {
    function process(Mobile_API_Request $request) {
        global $current_user, $adb;

        $response = new Mobile_API_Response();
        $recordId = $request->get('sparerequestid');
        $status   = $request->get('status');

        if (empty($recordId) || empty($status)) {
            $response->setError(101, 'Missing required parameters.');
            return $response;
        }

       
        $checkSql = "SELECT st_status FROM vtiger_stocktransfer 
                     INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_stocktransfer.stocktransferid
                     WHERE vtiger_crmentity.deleted = 0 AND stocktransferid = ?";
        $checkRes = $adb->pquery($checkSql, [$recordId]);

        if ($adb->num_rows($checkRes) == 0) {
            $response->setError(102, 'Record not found or deleted.');
            return $response;
        }

        $currentStatus = $adb->query_result($checkRes, 0, 'st_status');

       
        if ($currentStatus === $status) {
            $response->setError(103, "Request is already $status.");
            return $response;
        }

        
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, 'StockTransfer');
        if (!empty($recordModel)) {
            $recordModel->set('mode', 'edit');
            $recordModel->set('st_status', $status);
            $recordModel->set('assigned_user_id', $current_user->id); 
            $recordModel->save();

            $response->setApiSucessMessage('Request Updated Successfully');
            $response->setResult(['record_id' => $recordId, 'status' => $status]);
            return $response;
        } else {
            $response->setError(104, 'Not able to update record model.');
            return $response;
        }
    }
}
