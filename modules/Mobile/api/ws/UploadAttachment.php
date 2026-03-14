<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

class Mobile_WS_UploadAttachment extends Mobile_WS_Controller {

    function process(Mobile_API_Request $request) {
        global $adb;
        $response = new Mobile_API_Response();
        $module = $request->get('module');

        if (empty($module)) {
            $response->setError(100, "Module Is Missing");
            return $response;
        }

        $recordId = $request->get('recordId');
        if ($module == "Users") {
            global $uploadingUserImageFormTheApi;
            $uploadingUserImageFormTheApi = true;
            $recordId = '19x' . $request->get('useruniqueid');
        }

        if (empty($recordId)) {
            $response->setError(100, "recordId Is Missing");
            return $response;
        }

        if (strpos($recordId, 'x') === false) {
            $response->setError(100, 'RecordId Is Not Webservice Format');
            return $response;
        }

        $recordId = explode('x', $recordId)[1];
        $fieldName = $request->get('fieldname');
        $notecontent = $request->get('comment');

        if (empty($fieldName)) {
            $response->setError(100, "fieldname Is Missing");
            return $response;
        }

        if (!isset($_FILES[$fieldName])) {
            $response->setError(100, "Uploaded Files Are Missing");
            return $response;
        }

        global $upload_maxsize;
        $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
        $uploadedFiles = $_FILES[$fieldName];
        $responseData = [];

        $fileCount = count($uploadedFiles['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $file = [
                'name' => $uploadedFiles['name'][$i],
                'type' => $uploadedFiles['type'][$i],
                'tmp_name' => $uploadedFiles['tmp_name'][$i],
                'error' => $uploadedFiles['error'][$i],
                'size' => $uploadedFiles['size'][$i]
            ];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                continue;
            }

            if ($file['size'] > $upload_maxsize) {
                continue;
            }

            if (!in_array($file['type'], $validMimeTypes)) {
                continue;
            }

            // Upload and save the file
            $current_id = $adb->getUniqueID("vtiger_crmentity");
            $documentFocus = CRMEntity::getInstance('Documents');
            $recordIdOfUploaded = $documentFocus->uploadAndSaveFile(null, 'Documents', $file, 'Attachment', $fieldName);
            if ($recordIdOfUploaded) {
                // Set document details
                $documentFocus->id = $recordIdOfUploaded;
                $documentFocus->column_fields['related_to'] = $recordId;
                $documentFocus->column_fields['notes_title'] = $file['name'];
                $documentFocus->column_fields['notecontent'] = $notecontent;
                $documentFocus->column_fields['filename'] = $file['name'];
                $documentFocus->column_fields['filetype'] = $file['type'];
                $documentFocus->column_fields['filesize'] = $file['size'];
                $documentFocus->column_fields['filestatus'] = 1;
                $documentFocus->column_fields['assigned_user_id'] = $request->get('useruniqid');
                $documentFocus->column_fields['filelocationtype'] = 'I';
                $documentFocus->save('Documents');

                // Link document to the ticket
                $relatedid = $recordIdOfUploaded + 1;
                $parentModuleModel = Vtiger_Module_Model::getInstance('Tickets');
                $relatedModuleModel = Vtiger_Module_Model::getInstance('Documents');
                $relationModel = Vtiger_Relation_Model::getInstance($parentModuleModel, $relatedModuleModel);
                $relationModel->addRelation($recordId, $relatedid);

                $sql3 = 'INSERT INTO vtiger_seattachmentsrel VALUES(?,?)';
                $params3 = array($relatedid,$recordIdOfUploaded);
                $adb->pquery($sql3, $params3);

                $responseData[] = [
                    'uploadedDocumentId' => $recordIdOfUploaded,
                    'filename' => $file['name']
                ];
            }
        }

        if (!empty($responseData)) {
            $response->setResult(['uploadedDocuments' => $responseData]);
            $response->setApiSucessMessage('Successfully Uploaded Files');
        } else {
            $response->setError(100, "No Valid Files Were Uploaded");
        }

        return $response;
    }
}
