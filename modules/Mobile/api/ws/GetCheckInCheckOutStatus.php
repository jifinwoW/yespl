<?php
class Mobile_WS_GetCheckInCheckOutStatus extends Mobile_WS_Controller {

    function process(Mobile_API_Request $request) {
        global $adb;
        $response = new Mobile_API_Response();
        $checkindate = $request->get('checkindate');
        $useruniqid = $request->get('useruniqid');
       
        $sql = "select * from vtiger_attendance inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_attendance.attendanceid
            where checkindate = ? and vtiger_crmentity.smownerid= ? and vtiger_crmentity.deleted= 0";

        $sqlResult = $adb->pquery($sql,array($checkindate,$useruniqid));
        $dataRow = $adb->num_rows($sqlResult);

        if ($dataRow >= 1){
            $status = 'Check Out';
        }
        else{
            $status = 'Check In';
        }
        
        $ResponseObject['status'] = $status;
        $response->setResult($ResponseObject);
        $response->setApiSucessMessage('Successfully Fetched Status');
        return $response;
    }
}
