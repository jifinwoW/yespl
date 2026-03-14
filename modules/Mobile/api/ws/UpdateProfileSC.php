<?php

class Mobile_WS_UpdateProfileSC extends Mobile_WS_Controller
{
    function process(Mobile_API_Request $request)
    {

        global $current_user, $adb;
        $response = new Mobile_API_Response();
        $recordId = $current_user->id;
        $userName = $current_user->user_name;

        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');

        if (empty($firstname)) {
            $response->setError(100, 'First name value is empty');
            return $response;
        }
        if (empty($lastname)) {
            $response->setError(100, 'Last name value is empty');
            return $response;
        }
        $email = $request->get('email');
        if (empty($email)) {
            $response->setError(100, 'email Value is Empty');
            return $response;
        }

        $sql = 'select servicecordinatorid from vtiger_servicecordinator ' .
            ' inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_servicecordinator.servicecordinatorid' .
            ' where sm_code = ? and vtiger_crmentity.deleted= 0 ORDER BY servicecordinatorid DESC LIMIT 1';
        $sqlResult = $adb->pquery($sql, array($userName));
        // $employeeRecordModel = '';
        $num_rows = $adb->num_rows($sqlResult);
        if ($num_rows > 0) {
            $dataRow = $adb->fetchByAssoc($sqlResult, 0);
            // $employeeRecordModel = Vtiger_Record_Model::getInstanceById($dataRow['serviceservicecordinatorid'], 'ServiceEngineer');
            $updateSql = "UPDATE vtiger_servicecordinator 
              SET firstname = ?, lastname = ?, sc_email = ? 
              WHERE servicecordinatorid = ?";
            $adb->pquery($updateSql, array(
                $firstname,
                $lastname,
                $email,
                $dataRow['servicecordinatorid']
            ));
        }
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, 'Users');
        if (!empty($recordModel)) {
            $merged_name = trim($firstname . ' ' . $lastname);
            $recordModel->set('mode', 'edit');
            $recordModel->set('last_name', $merged_name);
            $recordModel->set('email1', $email);
            $recordModel->save();

            // $employeeRecordModel->set('mode', 'edit');
            // $employeeRecordModel->set('service_engineer_name', $request->get('service_engineer_name'));
            // $employeeRecordModel->set('email', $request->get('email'));
            // $employeeRecordModel->save();

            $response->setApiSucessMessage('User Profile Is Updated Successfully');
            $responseObject['userDetails'] = $this->getUserDetailsForProfile($recordId, $request->get('designaion'));
            $response->setResult($responseObject);
            return $response;
        } else {
            $response->setError(100, 'Not Able To Update User Profile');
            return $response;
        }
    }

    function getUserDetailsForProfile($recordId, $designation)
    {
        $userDetails = [];
        $recordModel = Vtiger_Record_Model::getInstanceById($recordId, 'Users');
        $imageObject = $recordModel->getImageDetails();
        $imageArray = $imageObject[0];
        $imageName = $imageArray['url'];
        $userDetails['imagename'] = $imageName;
        $userDetails['email'] = $recordModel->get('email1');
        $userDetails['sc_name'] = $recordModel->get('last_name');
        return $userDetails;
    }
}
