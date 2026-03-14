<?php
class Mobile_WS_copyServiceReportAttahments extends Mobile_WS_Controller {
    function process(Mobile_API_Request $request) {
        $response = new Mobile_API_Response();
        $responseObject = [];

        $sourceRecord = $request->get('sourceRecord');
        if (empty($sourceRecord)) {
            $response->setError(100, "sourceRecord Is Missing");
            return $response;
        }
        if (strpos($sourceRecord, 'x') == false) {
            $response->setError(100, 'sourceRecord Is Not Webservice Format');
            return $response;
        }
        $sourceRecord = explode('x', $sourceRecord);
        $sourceRecord = $sourceRecord[1];

        include_once('include/utils/GeneralUtils.php');
        $dataArr = getSingleColumnValue(array(
            'table' => 'vtiger_servicereports',
            'columnId' => 'servicereportsid',
            'idValue' => $sourceRecord,
            'expectedColValue' => 'ticket_id'
        ));
        $ticket_id = $dataArr[0]['ticket_id'];
        $recommisionDetails = $this->getGeneratedRecommisinigId($ticket_id);
        if ($recommisionDetails['alreadySRGenerated'] == true) {
            $recommsionId = $recommisionDetails['generatedSRData']['recommissioningreportsid'];
        } else {
            $response->setError(100, 'Recommmision Report Is Not Generated For Given Service Report');
            return $response;
        }

        $allImages = $this->getImageDetailsForCopy($sourceRecord);
        foreach ($allImages as $attachmentdetails) {
            $this->uploadAndSaveFileCustom($recommsionId, 'RecommissioningReports', $attachmentdetails, 'Attachment', $attachmentdetails['fieldNameFromDB']);
        }
        $responseObject['message'] = 'Successfully Copied All Attachments';
        $response->setResult($responseObject);
        $response->setApiSucessMessage('Successfully Copied All Attachments');
        return $response;
    }

    function getGeneratedRecommisinigId($id) {
        global $adb;
        $sql = 'select recommissioningreportsid,is_submitted  from vtiger_recommissioningreports'
            . ' INNER JOIN vtiger_crmentity '
            . ' ON vtiger_crmentity.crmid = vtiger_recommissioningreports.recommissioningreportsid '
            . ' where vtiger_recommissioningreports.ticket_id = ? and vtiger_crmentity.deleted = 0';
        $sqlResult = $adb->pquery($sql, array($id));
        $num_rows = $adb->num_rows($sqlResult);
        if ($num_rows > 0) {
            $resultObject['alreadySRGenerated'] = true;
            $resultObject['generatedSRData'] = $adb->fetchByAssoc($sqlResult, 0);
        } else {
            $resultObject['alreadySRGenerated'] = false;
        }
        return $resultObject;
    }

    function uploadAndSaveFileCustom($id, $module, $file_details, $attachmentType = 'Attachment', $fieldNameOfAttach = '') {
        global $log;
        $log->debug("Entering into uploadAndSaveFile($id,$module,$file_details) method.");

        global $adb, $current_user;
        global $upload_badext;

        $date_var = date("Y-m-d H:i:s");

        if (!isset($ownerid) || $ownerid == '')
            $ownerid = $current_user->id;

        if (isset($file_details['original_name']) && $file_details['original_name'] != null) {
            $file_name = $file_details['original_name'];
        } else {
            $file_name = $file_details['name'];
        }

        $save_file = 'true';
        $mimeType = vtlib_mime_content_type($file_details['tmp_name']);
        $mimeTypeContents = explode('/', $mimeType);
        if (($module == 'Contacts' || $module == 'Products') && ($attachmentType == 'Image' || ($file_details['size'] && $mimeTypeContents[0] == 'image'))) {
            $save_file = validateImageFile($file_details);
        }
        $log->debug("File Validation status in Check1 save_file => $save_file");
        if ($save_file == 'false') {
            return false;
        }

        $save_file = 'true';
        if ($module == 'Contacts' || $module == 'Products') {
            $save_file = validateImageFile($file_details);
        }
        $log->debug("File Validation status in Check2 save_file => $save_file");
        $binFile = sanitizeUploadFileName($file_name, $upload_badext);
        $current_id = $adb->getUniqueID("vtiger_crmentity");
        $filename = ltrim(basename(" " . $binFile));
        $filetype = $file_details['type'];
        $upload_file_path = decideFilePath();
        $encryptFileName = Vtiger_Util_Helper::getEncryptedFileName($file_details['name']);
        $upload_status = copy($file_details['location'], $upload_file_path . $current_id . "_" . $encryptFileName);
        $log->debug("Upload status of file => $upload_status");
        if ($save_file == 'true' && $upload_status == 'true') {
            if ($attachmentType != 'Image' && $this->mode == 'edit' && ($module != 'HelpDesk' & $module != 'ServiceReports')) {
                $res = $adb->pquery('SELECT vtiger_seattachmentsrel.attachmentsid FROM vtiger_seattachmentsrel 
                        INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_seattachmentsrel.attachmentsid AND vtiger_crmentity.setype = ? 
                        WHERE vtiger_seattachmentsrel.crmid = ?', array($module . ' Attachment', $id));
                $oldAttachmentIds = array();
                for ($attachItr = 0; $attachItr < $adb->num_rows($res); $attachItr++) {
                    $oldAttachmentIds[] = $adb->query_result($res, $attachItr, 'attachmentsid');
                }
                if (count($oldAttachmentIds)) {
                    $adb->pquery('DELETE FROM vtiger_seattachmentsrel WHERE attachmentsid IN (' . generateQuestionMarks($oldAttachmentIds) . ')', $oldAttachmentIds);
                }
            }
            $sql1 = "INSERT INTO vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $params1 = array($current_id, $current_user->id, $ownerid, $module . " " . $attachmentType, $this->column_fields['description'], $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
            $adb->pquery($sql1, $params1);
            $sql2 = "INSERT INTO vtiger_attachments(attachmentsid, name, description, type, path, storedname,subject) values(?, ?, ?, ?, ?, ?,?)";
            $params2 = array($current_id, $filename, NULL, $filetype, $upload_file_path, $encryptFileName, $fieldNameOfAttach);
            $adb->pquery($sql2, $params2);
            $sql3 = 'INSERT INTO vtiger_seattachmentsrel VALUES(?,?)';
            $params3 = array($id, $current_id);
            $adb->pquery($sql3, $params3);
            $log->debug("File uploaded successfully with id => $current_id");
            return $current_id;
        } else {
            $log->debug('File upload failed');
            return false;
        }
    }

    public function getImageDetailsForCopy($recordId) {
        global $site_URL;
        $db = PearDatabase::getInstance();
        $imageDetails = array();
        if ($recordId) {
            $sql = "SELECT vtiger_attachments.*, vtiger_crmentity.setype FROM vtiger_attachments
                INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
                INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attachments.attachmentsid
                WHERE vtiger_crmentity.setype In ('ServiceReports Attachment','HelpDesk Attachment' , 'ServiceReports Image')  AND vtiger_seattachmentsrel.crmid = ?";
            $result = $db->pquery($sql, array($recordId));
            $count = $db->num_rows($result);
            for ($i = 0; $i < $count; $i++) {
                $imageId = $db->query_result($result, $i, 'attachmentsid');
                $imageIdsList[] = $db->query_result($result, $i, 'attachmentsid');
                $imagePathList[] = $db->query_result($result, $i, 'path');
                $storedname[] = $db->query_result($result, $i, 'storedname');
                $imageName = $db->query_result($result, $i, 'name');
                $fieldName[] = $db->query_result($result, $i, 'subject');
                $url = \Vtiger_Functions::getFilePublicURL($imageId, $imageName);
                $imageOriginalNamesList[] = urlencode(decode_html($imageName));
                $imageNamesList[] = $imageName;
                $imageUrlsList[] = $url;
                $descriptionOffield[] = $db->query_result($result, $i, 'description');
                $typeList[] = $db->query_result($result, $i, 'type');
            }
            if (is_array($imageOriginalNamesList)) {
                $countOfImages = count($imageOriginalNamesList);
                for ($j = 0; $j < $countOfImages; $j++) {
                    $imageDetails[] = array(
                        'id' => $imageIdsList[$j],
                        'orgname' => $imageOriginalNamesList[$j],
                        'path' => $imagePathList[$j] . $imageIdsList[$j],
                        'location' => $imagePathList[$j] . $imageIdsList[$j] . '_' . $storedname[$j],
                        'name' => $imageNamesList[$j],
                        'url' => $imageUrlsList[$j],
                        'field' => $imageUrlsList[$j],
                        'fieldNameFromDB' => $fieldName[$j],
                        'descriptionOffield' => $descriptionOffield[$j],
                        'type' => $typeList[$j]
                    );
                }
            }
        }
        return $imageDetails;
    }
}
