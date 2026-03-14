<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

class Mobile_WS_UploadAttachment extends Mobile_WS_Controller {

    function process(Mobile_API_Request $request) {
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

        $fieldName = $request->get('fieldName');
        if (empty($fieldName)) {
            $response->setError(100, "fieldName Is Missing");
            return $response;
        }

        if (empty($_FILES[$fieldName])) {
            $response->setError(100, "Uploaded File Is Missing");
            return $response;
        }

        $file = $_FILES[$fieldName];

        global $upload_maxsize;
        if ($file['size'] < $upload_maxsize) {
            // Create a new document instance
            $documentFocus = CRMEntity::getInstance('Documents');

            // Upload the file and save its details
            $recordIdOfUploaded = $documentFocus->uploadAndSaveFile(null, 'Documents', $file, 'Attachment', $fieldName);
           
            if ($recordIdOfUploaded) {
              
                // Prepare document fields
                $documentFocus->id = $recordIdOfUploaded; // Set the ID of the uploaded document
                $documentFocus->column_fields['related_to'] = $recordId; // Link to the ticket
                $documentFocus->column_fields['notes_title'] = $file['name'];
                $documentFocus->column_fields['filename'] = $file['name'];
                $documentFocus->column_fields['filetype'] = $file['type'];
                $documentFocus->column_fields['filesize'] = $file['size'];
                $documentFocus->column_fields['filestatus'] = 1;
                $documentFocus->column_fields['assigned_user_id'] = $request->get('useruniqid'); 
                $documentFocus->column_fields['filelocationtype'] = 'I'; // Internal file location
                $documentFocus->save('Documents'); // Save the document
                
               $relatedid = $recordIdOfUploaded+1;
                // Link the document to the ticket
                $parentModuleModel = Vtiger_Module_Model::getInstance('Tickets');
                $relatedModuleModel = Vtiger_Module_Model::getInstance('Documents');
                $relationModel = Vtiger_Relation_Model::getInstance($parentModuleModel, $relatedModuleModel);
                $relationModel->addRelation($recordId, $relatedid); // Create the relation
;
                // Prepare response
                $ResponseObject['uploadedDocumentId'] = $recordIdOfUploaded;
                $response->setResult($ResponseObject);
                $response->setApiSucessMessage('Successfully Uploaded Document and Linked to Ticket');
                return $response;
            } else {
                $response->setError(100, "Failed to Upload Document");
                return $response;
            }
        } else {
            $response->setError(100, "Filesize larger than $upload_maxsize bytes");
            return $response;
        }
    }
}
