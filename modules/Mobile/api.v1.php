<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Mobile_APIV1_Controller
{

	static $opControllers = array(
		'login'						=> array('file' => '/api/ws/Login.php',						'class' => 'Mobile_WS_Login'),
		'loginAndFetchModules'		=> array('file' => '/api/ws/LoginAndFetchModules.php',		'class' => 'Mobile_WS_LoginAndFetchModules'),
		'fetchModuleFilters'		=> array('file' => '/api/ws/FetchModuleFilters.php',		'class' => 'Mobile_WS_FetchModuleFilters'),
		'filterDetailsWithCount'	=> array('file' => '/api/ws/FilterDetailsWithCount.php',	'class' => 'Mobile_WS_FilterDetailsWithCount'),
		'fetchAllAlerts'			=> array('file' => '/api/ws/FetchAllAlerts.php',			'class' => 'Mobile_WS_FetchAllAlerts'),
		'alertDetailsWithMessage'	=> array('file' => '/api/ws/AlertDetailsWithMessage.php',	'class' => 'Mobile_WS_AlertDetailsWithMessage'),
		'listModuleRecords'			=> array('file' => '/api/ws/ListModuleRecords.php',			'class' => 'Mobile_WS_ListModuleRecords'),
		'fetchRecord'				=> array('file' => '/api/ws/FetchRecord.php',				'class' => 'Mobile_WS_FetchRecord'),
		'fetchRecordWithGrouping'	=> array('file' => '/api/ws/FetchRecordWithGrouping.php',	'class' => 'Mobile_WS_FetchRecordWithGrouping'),
		'fetchRecordsWithGrouping'	=> array('file' => '/api/ws/FetchRecordsWithGrouping.php',	'class' => 'Mobile_WS_FetchRecordsWithGrouping'),
		'fetchReferenceRecords'		=> array('file' => '/api/ws/FetchReferenceRecords.php',		'class' => 'Mobile_WS_FetchReferenceRecords'),
		'describe'					=> array('file' => '/api/ws/Describe.php',					'class' => 'Mobile_WS_Describe'),
		'saveRecord'				=> array('file' => '/api/ws/SaveRecord.php',				'class' => 'Mobile_WS_SaveRecord'),
		'syncModuleRecords'			=> array('file' => '/api/ws/SyncModuleRecords.php',			'class' => 'Mobile_WS_SyncModuleRecords'),
		'query'						=> array('file' => '/api/ws/Query.php',						'class' => 'Mobile_WS_Query'),
		'queryWithGrouping'			=> array('file' => '/api/ws/QueryWithGrouping.php',			'class' => 'Mobile_WS_QueryWithGrouping'),
		'relatedRecordsWithGrouping' => array('file' => '/api/ws/RelatedRecordsWithGrouping.php', 'class' => 'Mobile_WS_RelatedRecordsWithGrouping'),
		'deleteRecords'				=> array('file' => '/api/ws/DeleteRecords.php',				'class' => 'Mobile_WS_DeleteRecords'),
		'logout'					=> array('file' => '/api/ws/Logout.php',					'class' => 'Mobile_WS_Logout'),
		'fetchModules'				=> array('file' => '/api/ws/FetchModules.php',				'class' => 'Mobile_WS_FetchModules'),
		'userInfo'					=> array('file' => '/api/ws/UserInfo.php',					'class' => 'Mobile_WS_UserInfo'),
		'addRecordComment'			=> array('file' => '/api/ws/AddRecordComment.php',			'class' => 'Mobile_WS_AddRecordComment'),
		'history'					=> array('file' => '/api/ws/History.php',					'class' => 'Mobile_WS_History'),
		'taxByType'					=> array('file' => '/api/ws/TaxByType.php',					'class' => 'Mobile_WS_TaxByType'),
		'fetchModuleOwners'			=> array('file' => '/api/ws/FetchModuleOwners.php',			'class' => 'Mobile_WS_FetchModuleOwners'),
		'GetProfileData'			=> array('file' => '/api/ws/GetProfileInfo.php',			'class' => 'Mobile_WS_GetProfileInfo'),
		'UpdateProfile'			    => array('file' => '/api/ws/UpdateProfile.php',			    'class' => 'Mobile_WS_UpdateProfile'),
		'UpdateProfileSC'			=> array('file' => '/api/ws/UpdateProfileSC.php',		'class' => 'Mobile_WS_UpdateProfileSC'),
		'GetTicketListByStatus'	    => array('file' => '/api/ws/GetTicketListByStatus.php',	    'class' => 'Mobile_WS_GetTicketListByStatus'),
		'GetRecordDetail'           => array('file' => '/api/ws/GetRecordDetail.php',	        'class' => 'Mobile_WS_GetRecordDetail'),
		'GetCheckInCheckOutStatus'  => array('file' => '/api/ws/GetCheckInCheckOutStatus.php',	'class' => 'Mobile_WS_GetCheckInCheckOutStatus'),
		'CheckOut'                  => array('file' => '/api/ws/CheckOut.php',               	'class' => 'Mobile_WS_CheckOut'),
		'AttendanceRegularization'  => array('file' => '/api/ws/AttendanceRegularization.php',  'class' => 'Mobile_WS_AttendanceRegularization'),
		'GetPickListValues'         => array('file' => '/api/ws/GetPickListValues.php',         'class' => 'Mobile_WS_GetPickListValues'),
		'GetSRListByTicketid'       => array('file' => '/api/ws/GetSRListByTicketid.php',       'class' => 'Mobile_WS_GetSRListByTicketid'),
		'GetSRPLCMNTListByTicketid'       => array('file' => '/api/ws/GetSRPLCMNTListByTicketid.php',       'class' => 'Mobile_WS_GetSRPLCMNTListByTicketid'),
		'GetFollowUpListByTicketid' => array('file' => '/api/ws/GetFollowUpListByTicketid.php', 'class' => 'Mobile_WS_GetFollowUpListByTicketid'),
		'GetCommentListByTicketid' => array('file' => '/api/ws/GetCommentListByTicketid.php',   'class' => 'Mobile_WS_GetCommentListByTicketid'),
		'GetNotification'          => array('file' => '/api/ws/GetNotification.php',            'class' => 'Mobile_WS_GetNotification'),
		'UploadAttachment'          => array('file' => '/api/ws/UploadAttachment.php',            'class' => 'Mobile_WS_UploadAttachment'),
		'CreateSTRequest'          => array('file' => '/api/ws/CreateSTRequest.php',            'class' => 'Mobile_WS_CreateSTRequest'),
		'GetSpareParts'          => array('file' => '/api/ws/GetSpareParts.php',            'class' => 'Mobile_WS_GetSpareParts'),
		'GetSparePartsByUser'          => array('file' => '/api/ws/GetSparePartsByUser.php',            'class' => 'Mobile_WS_GetSparePartsByUser'),
		'CreateSPR'          => array('file' => '/api/ws/CreateSPR.php',            'class' => 'Mobile_WS_CreateSPR'),
		'GetEngineerList'          => array('file' => '/api/ws/GetEngineerList.php',            'class' => 'Mobile_WS_GetEngineerList'),
		'GetTicketsByEngineer'          => array('file' => '/api/ws/GetTicketsByEngineer.php',            'class' => 'Mobile_WS_GetTicketsByEngineer'),
		'GetSPRequestByTicket'          => array('file' => '/api/ws/GetSPRequestByTicket.php',            'class' => 'Mobile_WS_GetSPRequestByTicket'),
		'UpdateSPRequest'          => array('file' => '/api/ws/UpdateSPRequest.php',            'class' => 'Mobile_WS_UpdateSPRequest'),
		'UpdateSPRequestByProduct'          => array('file' => '/api/ws/UpdateSPRequestByProduct.php',            'class' => 'Mobile_WS_UpdateSPRequestByProduct'),
		'GetAllSPRequest'          => array('file' => '/api/ws/GetAllSPRequest.php',            'class' => 'Mobile_WS_GetAllSPRequest'),
		'GetZoneValues'          => array('file' => '/api/ws/GetZoneValues.php',            'class' => 'Mobile_WS_GetZoneValues'),
		'GetDashboardData'          => array('file' => '/api/ws/GetDashboardData.php',            'class' => 'Mobile_WS_GetDashboardData'),
		'WorkLogEntry'          => array('file' => '/api/ws/WorkLogEntry.php',            'class' => 'Mobile_WS_WorkLogEntry'),
		'GetWorkLogs'          => array('file' => '/api/ws/GetWorkLogs.php',            'class' => 'Mobile_WS_GetWorkLogs'),
	);

	protected function initSession(Mobile_API_Request $request)
	{
		$sessionid = $request->getSession();
		return Mobile_API_Session::init($sessionid);
	}

	protected function getController(Mobile_API_Request $request)
	{
		$operation = $request->getOperation();
		if (isset(self::$opControllers[$operation])) {
			$operationFile = self::$opControllers[$operation]['file'];
			$operationClass = self::$opControllers[$operation]['class'];

			include_once dirname(__FILE__) . $operationFile;
			$operationController = new $operationClass;
			return $operationController;
		}
	}


	function process(Mobile_API_Request $request)
	{
		$operation = $request->getOperation();

		$response = false;
		$operationController = $this->getController($request);
		if ($operationController) {
			$operationSession = false;
			if ($operationController->requireLogin()) {
				$operationSession = $this->initSession($request);
				if ($operationController->hasActiveUser() === false) {
					$operationSession = false;
				}
				//Mobile_WS_Utils::initAppGlobals();
			} else {
				// By-pass login
				$operationSession = true;
			}

			if ($operationSession === false) {
				$response = new Mobile_API_Response();
				$response->setError(1501, 'Login required');
			} else {

				try {
					$response = $operationController->process($request);
				} catch (Exception $e) {
					$response = new Mobile_API_Response();
					$response->setError($e->getCode(), $e->getMessage());
				}
			}
		} else {
			$response = new Mobile_API_Response();
			$response->setError(1404, 'Operation not found: ' . $operation);
		}

		if ($response !== false) {
			echo $response->emitJSON();
		}
	}

	static function getInstance()
	{
		$instance = new static();
		return $instance;
	}
}
