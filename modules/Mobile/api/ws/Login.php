<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Mobile_WS_Login extends Mobile_WS_Controller
{

	function requireLogin()
	{
		return false;
	}

	function process(Mobile_API_Request $request)
	{
		$response = new Mobile_API_Response();
		$username = $request->get('username');
		$password = $request->get('password');
		global $adb;
		if (empty($username)) {
			$response->setError(1501, 'Username Is Missing');
			return $response;
		}
		if (empty($password)) {
			$response->setError(1501, 'Password Is Missing');
			return $response;
		}

		$current_user = CRMEntity::getInstance('Users');
		$current_user->column_fields['user_name'] = $username;
		if (vtlib_isModuleActive('Mobile') === false) {
			$response->setError(1501, 'Service not available');
			return $response;
		}

		$current_user->id = $current_user->retrieve_user_id($username);
		$current_user->retrieveCurrentUserInfoFromFile($current_user->id);

		$userRole = $current_user->roleid;

		if ($userRole === 'H5') {
			$loginPlatDEtails = $this->getAccessiblePlatformsforSC($username);
			$statsField = "sm_status";
			$role = "Service Cordinator / Area Manager";
		} else {
			$loginPlatDEtails = $this->getAccessiblePlatformsforEng($username);
			$statsField = "eng_status";
			$role = "Engineer";
		}


		if (empty($loginPlatDEtails)) {
			$response->setError(1501, 'You Are Not Registered');
			return $response;
		}

		if ($loginPlatDEtails[$statsField] !== 'Accepted' && $loginPlatDEtails[$statsField] !== 'Rejected') {
			$response->setError(1501, 'User Verfication Is Pending');
			return $response;
		}

		if ($loginPlatDEtails[$statsField] == 'Rejected') {
			$response->setError(1501, 'User Verfication Is Rejected , Please Sign Up Again');
			return $response;
		}

		if (!$current_user->doLogin($password)) {
			$response->setError(1210, 'Authentication Failed');
		} else {

			// Start session now
			$sessionid = Mobile_API_Session::init();

			if ($sessionid === false) {
				echo "Session init failed $sessionid\n";
			}

			$current_user->id = $current_user->retrieve_user_id($username);
			$current_user->retrieveCurrentUserInfoFromFile($current_user->id);
			$this->setActiveUser($current_user);

			// JWT Implementation
			//require __DIR__ . DIRECTORY_SEPARATOR .'autoload.php';
			$key = "ONSGVGFDKNBXVDAWTYSVSCDX" . $current_user->user_password . $current_user->user_name;
			$payload = [
				'userid' => $current_user->id,
			];

			//$jwt = JWT::encode($payload, $key, 'HS256');

			if ($userRole === 'H5') {
				$data = $this->getUserDetailsBasedOnSCModule($current_user->user_name);
				$recordModel = Vtiger_Record_Model::getInstanceById($data['servicecordinatorid'], 'ServiceCordinator');
				$usercreatedid = $data['servicecordinatorid'];
				$employeeWsId = Mobile_WS_Utils::getEntityModuleWSId('ServiceCordinator') . 'x' . $data['servicecordinatorid'];
			} else {
				$data = $this->getUserDetailsBasedOnEngineerModule($current_user->user_name);
				$recordModel = Vtiger_Record_Model::getInstanceById($data['engineerid'], 'Engineer');
				$usercreatedid = $data['engineerid'];
				$employeeWsId = Mobile_WS_Utils::getEntityModuleWSId('Engineer') . 'x' . $data['engineerid'];
			}
			if ($data == false) {
				$response->setError(1501, 'Not Able To Find User Details');
				return $response;
			}



			$emData = $recordModel->getData();
			unset($emData['confirm_password']);
			unset($emData['user_password']);

			$date = new DateTime();

			$emData['imagename'] = $this->getUserImageDetails($current_user->id);
			$result = array(
				'assign_user_id' => $current_user->id,
				'usercreatedid' => $usercreatedid,
				'usertype' => $role,
				'employeeWsId' => $employeeWsId,
				'access_token' => $sessionid,
				'username' => $username,
				'password' => $password,
				'usermobilenumber' => $data['mobile_no'],
				'useruniqeid' => $current_user->id,
				'timestamp' => $date->getTimestamp(),
				'message' => 'Thank You Have Been Login Succesfully',
				'profileInfo' => $emData
			);

			// Add servicecordintor_id only if role is h5
			if ($role === 'Engineer') {
				$result['servicecordintor_id'] = '41x' . $emData['servicecordintor_id'];
			}



			//$response->setApiSucessMessage('Successfully Logged In');
			$response->setResult($result);
			$this->postProcess($response);
		}
		return $response;
	}

	protected static $userURLCache = array();

	public function getUserImageDetails($recordId)
	{
		if (empty($recordId)) {
			return NULL;
		}
		global $site_URL_NonHttp;
		$db = PearDatabase::getInstance();
		$url = NULL;
		if (!self::$userURLCache[$recordId]) {
			$query = "SELECT vtiger_attachments.attachmentsid, vtiger_attachments.path, 
					vtiger_attachments.name, vtiger_attachments.storedname FROM vtiger_attachments
					LEFT JOIN vtiger_salesmanattachmentsrel 
					ON vtiger_salesmanattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
					WHERE vtiger_salesmanattachmentsrel.smid=? order by attachmentsid desc limit 1";
			$result = $db->pquery($query, array($recordId));
			$storedname = $db->query_result($result, 0, 'storedname');
			$imagePath = $db->query_result($result, 0, 'path');
			$imageId = $db->query_result($result, 0, 'attachmentsid');
			$url = $site_URL_NonHttp . $imagePath . $imageId . '_' . $storedname;
			self::$userURLCache[$recordId] = $url;
			if (empty($imagePath)) {
				self::$userURLCache[$recordId] = NULL;
			}
		}
		return self::$userURLCache[$recordId];
	}

	function getAccessiblePlatformsforEng($userName)
	{
		global $adb;
		$sql = 'select engineer_code,engineer_name,mobile_no,email,eng_status from vtiger_engineer 
		INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_engineer.engineerid 
		where vtiger_engineer.engineer_code = ? and vtiger_crmentity.deleted=0';
		$result = $adb->pquery($sql, array($userName));
		$loginPlatforms = '';
		while ($row = $adb->fetch_array($result)) {
			$loginPlatforms =  $row;
		}
		return $loginPlatforms;
	}

	function getAccessiblePlatformsforSC($userName)
	{
		global $adb;
		$sql = 'select sm_code,mobile_no,sm_status from vtiger_servicecordinator 
		INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_servicecordinator.servicecordinatorid 
		where vtiger_servicecordinator.sm_code = ? and vtiger_crmentity.deleted=0';
		$result = $adb->pquery($sql, array($userName));
		$loginPlatforms = '';
		while ($row = $adb->fetch_array($result)) {
			$loginPlatforms =  $row;
		}
		return $loginPlatforms;
	}

	function getUserDetailsBasedOnEngineerModule($badgeNo)
	{
		global $adb;
		$sql = 'select engineerid,mobile_no from vtiger_engineer'
			. ' inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_engineer.engineerid'
			. ' where vtiger_engineer.engineer_code = ? and vtiger_crmentity.deleted = 0 ORDER BY engineerid DESC LIMIT 1';
		$sqlResult = $adb->pquery($sql, array($badgeNo));
		$num_rows = $adb->num_rows($sqlResult);
		if ($num_rows == 1) {
			$dataRow = $adb->fetchByAssoc($sqlResult, 0);
			return $dataRow;
		} else {
			return false;
		}
	}

	function getUserDetailsBasedOnSCModule($badgeNo)
	{
		global $adb;
		$sql = 'select servicecordinatorid ,mobile_no from vtiger_servicecordinator'
			. ' inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_servicecordinator.servicecordinatorid '
			. ' where vtiger_servicecordinator.sm_code = ? and vtiger_crmentity.deleted = 0 ORDER BY servicecordinatorid DESC LIMIT 1';
		$sqlResult = $adb->pquery($sql, array($badgeNo));
		$num_rows = $adb->num_rows($sqlResult);
		
		if ($num_rows == 1) {
			$dataRow = $adb->fetchByAssoc($sqlResult, 0);
			return $dataRow;
		} else {
			return false;
		}
	}

	function postProcess(Mobile_API_Response $response)
	{
		return $response;
	}
}
