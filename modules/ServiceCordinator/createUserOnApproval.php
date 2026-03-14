<?php
function createUserOnApproval($entityData) {
	$data = $entityData->{'data'};
	require_once('modules/Users/Users.php');
	global $adb;

	// getting the id of the created record it in the format 12x237
	$recId = $data['id'];
	$idsOfCreated = explode('x', $recId);
	$data['id'] = $idsOfCreated[1];

	$username = preg_replace('/\s+/', '', $data['sm_code']);
	$data['confirm_password'] = Vtiger_Functions::fromProtectedText($data['confirm_password']);
	$password = preg_replace('/\s+/', '', $data['confirm_password']);
	$result = $adb->pquery('SELECT 1 FROM `vtiger_users` where user_name = ?', array($username));
	$rowCount = $adb->num_rows($result);
	global $ajaxEditingInSEmod;
	if ($rowCount > 0) {
		unSetAcceptValue($data['id']);
		if ($ajaxEditingInSEmod) {
			$response = new Vtiger_Response();
			$response->setEmitType(Vtiger_Response::$EMIT_JSON);
			$response->setError('Service Cordinator is Already Exits, User Is Not Created');
			$response->emit();
			exit();
		} else {
			$viewer = new Vtiger_Viewer();
			$viewer->assign('MESSAGE', "Cordinator is Alraedy Exits");
			$viewer->view('OperationNotPermitted.tpl', 'Vtiger');
			exit();
		}
	}


	$focus = new Users();
	$password = $password;
	$focus->column_fields['user_name'] =   $data['sm_code'];
	$focus->column_fields['first_name'] = $data['firstname'];
	$focus->column_fields['last_name'] =  $data['lastname'];
	$focus->column_fields['status'] =  'Active';
	$focus->column_fields['is_admin'] =  'off';
	$focus->column_fields['user_password'] =  $password;
	$focus->column_fields['confirm_password'] =  $password;
	$focus->column_fields['email1'] =   $data['email'];
	$focus->column_fields['phone_mobile'] = $data['mobile_no'];
	$focus->column_fields['roleid'] = 'H5';
	$focus->column_fields['tz'] =  'Asia/Kolkata';
	$focus->column_fields['time_zone'] =  'Asia/Kolkata';
	$focus->column_fields['date_format'] =  'dd/mm/yyyy';
	$focus->column_fields['title'] =  'Asia';
	$focus->save("Users");
	ignore_user_abort(true);

	ob_start();
	$response = new Vtiger_Response();
	$response->setResult(array('success' => true, 'message' => 'Successfuly Approved'));
	$response->emit();
	header($_SERVER["SERVER_PROTOCOL"] . " 202 Accepted");
	header("Status: 202 Accepted");
	header("Content-Type: application/json");
	header('Content-Length: ' . ob_get_length());
	ob_end_flush();
	ob_flush();
	flush();

	global $smsEndPoint;
	$name = $data['last_name'];
	$badgeNo = $data['sm_code'];
	$text = urlencode("Dear User, Hi, $name, Your account has been successfully validated. You can now login with $badgeNo and set your password. CRM Project");
	$reusultOfCUrl = '';
	$mobile = $data['mobile_no'];
	$url = "$smsEndPoint?loginID=beml_htuser&mobile=$mobile&text=$text&senderid=BEMLHQ"
	. "&DLT_TM_ID=1001096933494158&DLT_CT_ID=1007766184092857501"
	. "&DLT_PE_ID=1001209734454178165&route_id=DLT_SERVICE_IMPLICT&Unicode=0&camp_name=beml_htuser&password=beml@123";
	if (!empty($mobile)) {
		$header = array('Content-Type:multipart/form-data');
		$resource = curl_init();
		curl_setopt($resource, CURLOPT_URL, $url);
		curl_setopt($resource, CURLOPT_HTTPHEADER, $header);
		curl_setopt($resource, CURLOPT_POST, 1);
		curl_setopt($resource, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($resource, CURLOPT_POSTFIELDS, array());
		$reusultOfCUrl = trim(curl_exec($resource));
	}

	/*$dashboardSql = "INSERT INTO `vtiger_dashboard_tabs` 
	(`id`, `tabname`, `isdefault`, `sequence`, `appname`, `modulename`, `userid`) 
	VALUES (NULL, 'My Dashboard', '1', '1', '', '', $focus->id)";
	$result = $adb->pquery($dashboardSql, array());

	$sql = 'SELECT id FROM vtiger_dashboard_tabs where userid = ? and tabname = ?';
	$params = array($focus->id, 'My Dashboard');
	$result = $adb->pquery($sql, $params);
	$noOfUsers = $adb->num_rows($result);
	$dashboardTabId = '';
	if ($noOfUsers > 0) {
		for ($i = 0; $i < $noOfUsers; ++$i) {
			$dashboardTabId = $adb->query_result($result, $i, 'id');
		}
	}
	$dashboardTabIds = array(67);
	foreach ($dashboardTabIds as $value) {
		$sql = 'SELECT id FROM vtiger_module_dashboard_widgets WHERE linkid = ? AND userid = ? AND dashboardtabid=?';
		$params = array($value, $focus->id, $dashboardTabId);
		$result = $adb->pquery($sql, $params);
		$size = '';
		if ($value == 117 || $value == 120 || $value == 121) {
			$size = '{"sizex":"1","sizey":"1"}';
		} else {
			$size = '{"sizex":"2","sizey":"1"}';
		}
		if (!$adb->num_rows($result)) {
			$adb->pquery(
				'INSERT INTO vtiger_module_dashboard_widgets(linkid, userid,'
					. ' dashboardtabid,size) '
					. 'VALUES(?,?,?,?)',
				array($value, $focus->id, $dashboardTabId, $size)
			);
		}
	}

	Settings_SharingAccess_Module_Model::recalculateSharingRules();*/
}

function unSetAcceptValue($id) {
	$db = PearDatabase::getInstance();
	$query = "UPDATE vtiger_serviecordinator SET rejection_reason=?,sm_status=? WHERE serviecordinatorid=?";
	$db->pquery($query, array('', '', $id));
}

