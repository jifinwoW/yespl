<?php
class Mobile_WS_getAppVersionName extends Mobile_WS_Controller {

    function requireLogin() {
        return false;
    }

    function process(Mobile_API_Request $request) {
        $response = new Mobile_API_Response();
        $response->setApiSucessMessage('Successfully Fetched Data');
        $responseObject = [];
        global $currentAppVersion;
        $responseObject['latestAppVersion'] = $currentAppVersion;
        $oldAppVer = $request->get('currentAppVersion');
        $responseObject['updateMessage'] = "App Needs To Be Updated,Latest App Version Is - $currentAppVersion, Your Current Version Is - $oldAppVer";
        $response->setResult($responseObject);
        return $response;
    }
}
