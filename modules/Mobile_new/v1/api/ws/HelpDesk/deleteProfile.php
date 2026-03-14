<?php
class Mobile_WS_deleteProfile extends Mobile_WS_Controller {

    function process(Mobile_API_Request $request) {
        $response = new Mobile_API_Response();
        global $adb;
        global $current_user;
        require_once('include/utils/GeneralUtils.php');
        $data = getUserDetailsBasedOnEmployeeModuleG($current_user->user_name);
        $userId = $data['serviceengineerid'];
        if (empty($userId)) {
            $response->setError(100, "Unable To Find User id From The current user");
            return $response;
        } else {
            $sql = 'select req_for_del from vtiger_serviceengineer where serviceengineerid=?';
            $sqlResult = $adb->pquery($sql, array($userId));
            $delRequest = $adb->query_result($sqlResult, 0, 'req_for_del');
            if ($delRequest == '1') {
                $response->setError(1501, 'Already Profile Delete Is requested');
                return $response;
            } else {
                $sql = 'update vtiger_serviceengineer set req_for_del = 1 where serviceengineerid=?';
                $sqlResult = $adb->pquery($sql, array($userId));
                $response->setApiSucessMessage('User Profile Is Marked For Delete Successfully');
                $response->setResult([]);
                return $response;
            }
        }
    }
}
