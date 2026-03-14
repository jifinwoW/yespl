<?php
include_once 'include/Webservices/Retrieve.php';
include_once dirname(__FILE__) . '/../FetchRecord.php';
include_once 'include/Webservices/DescribeObject.php';
include_once('include/utils/GeneralUtils.php');
class Mobile_WS_getAnnouncement extends Mobile_WS_FetchRecord {

	function process(Mobile_API_Request $request) {
		$response = new Mobile_API_Response();

		global $adb, $site_URL;
		$query = $adb->pquery("SELECT * FROM vtiger_announcements INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_announcements.announcementsid WHERE vtiger_crmentity.deleted = 0", array());
		$rows = $adb->num_rows($query);
		$flashTextArray = array();
		$flashTextArray = [];
		$imageDetails = [];
		for ($i = 0; $i < $rows; $i++) {
			$announcementsid = $adb->query_result($query, $i, 'announcementsid');
			$recordid = vtws_getWebserviceEntityId('Announcements', $announcementsid);
			$anno_flashtext = $adb->query_result($query, $i, 'anno_flashtext');
			$anno_youtubeurl = $adb->query_result($query, $i, 'anno_youtubeurl');
			$sql = "SELECT vtiger_attachments.*, vtiger_crmentity.setype FROM vtiger_attachments
						INNER JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
						INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_attachments.attachmentsid
						WHERE vtiger_seattachmentsrel.crmid = ?";

			$result = $adb->pquery($sql, array($announcementsid));

			$imageId = $adb->query_result($result, 0, 'attachmentsid');
			$imagePath = $adb->query_result($result, 0, 'path');
			$imageName = $adb->query_result($result, 0, 'name');
			$type = explode('/', $adb->query_result($result, 0, 'type'));
			$url = \Vtiger_Functions::getFilePublicURL($imageId, $imageName);
			//decode_html - added to handle UTF-8 characters in file names
			$imageOriginalName = urlencode(decode_html($imageName));
			if ($url) {
				$url = $site_URL . $url;
			}

			if (!empty($imageName)) {
				$imageDetails[] = array(
					'type' => $type[0],
					'url'  => $url
				);
			}else if($anno_youtubeurl){
				$imageDetails[] = array(
					'type' => 'YouTube',
					'url'  => $anno_youtubeurl
				);
			}else{
				$imageDetails[] = array(
					'type' => '',
					'url'  => ''
				);
			}

			$flashTextArray[] = array('message' => $anno_flashtext, 'record' => $recordid);
		}
		$hasAccess = true;
		if ($hasAccess) {
			$response->setApiSucessMessage('Successfully Fetched Data');
			$response->setResult(array('media_list' => $imageDetails, 'flash_text' => $flashTextArray));
		} else {
			$response->setError(100, 'Permission to read given object is denied');
		}
		return $response;
	}
}
