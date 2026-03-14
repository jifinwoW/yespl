<?php

class Mobile_WS_CheckOut extends Mobile_WS_Controller {
    function process(Mobile_API_Request $request) {

        global $current_user, $adb;
        $response = new Mobile_API_Response();
        $recordId = $current_user->id;
        $userName = $current_user->user_name;

        $checkoutdate = $request->get('checkoutdate');
        $useruniqid = $request->get('useruniqid');
    
        $sql = "select attendanceid,attedance_status from vtiger_attendance inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_attendance.attendanceid
        where checkindate = ? and vtiger_crmentity.smownerid=? and vtiger_crmentity.deleted= 0";

        $sqlResult = $adb->pquery($sql, array($checkoutdate,$useruniqid));
        $num_rows = $adb->num_rows($sqlResult);
        if ($num_rows > 0) {
            $dataRow = $adb->fetchByAssoc($sqlResult, 0); 
        }
        $recordModel = Vtiger_Record_Model::getInstanceById($dataRow['attendanceid'], 'Attendance');
        if (!empty($recordModel)) {
            $recordModel->set('mode', 'edit');
            $recordModel->set('checkoutdate', $request->get('checkoutdate'));
            $recordModel->set('checkouttime', $request->get('checkouttime'));
            $recordModel->set('checkout_latitude', $request->get('checkout_latitude'));
            $recordModel->set('checkout_longitude', $request->get('checkout_longitude'));
            $recordModel->set('attedance_status', $request->get('attedance_status'));
            $recordModel->save();

            if($dataRow['attedance_status'] == 'Check Out'){
                $response->setApiSucessMessage('You Already Check Out Successfully');
                $responseObject['status'] = "Check Out";
                $response->setResult($responseObject);
                return $response;
            }

            $response->setApiSucessMessage('Check Out Successfully');
            $responseObject['status'] = "Check Out";
            $response->setResult($responseObject);
            return $response;
        } else {
            $response->setError(100, 'Not Able To Check Out');
            return $response;
        }
    }
}
