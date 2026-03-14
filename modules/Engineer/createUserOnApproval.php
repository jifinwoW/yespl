<?php
function createUserOnApproval($entityData) {
	$data = $entityData->{'data'};
	require_once('modules/Users/Users.php');
	global $adb;

	$recId = $data['id'];
	$idsOfCreated = explode('x', $recId);
	$data['id'] = $idsOfCreated[1];

	$username = preg_replace('/\s+/', '', $data['engineer_code']);

	$data['user_password'] = Vtiger_Functions::fromProtectedText($data['user_password'] ?? '');
	$data['confirm_password'] = Vtiger_Functions::fromProtectedText($data['confirm_password'] ?? '');
	$userPassword = trim($data['user_password']);
	$confirmPassword = trim($data['confirm_password']);

	global $ajaxEditingInSEmod;

	if (empty($userPassword) || $userPassword !== $confirmPassword) {
		unSetAcceptValue($data['id']);
		return true;
	}

	// Check for existing user
	$result = $adb->pquery('SELECT 1 FROM `vtiger_users` WHERE user_name = ?', array($username));
	if ($adb->num_rows($result) > 0) {
		unSetAcceptValue($data['id']);
		return true;
	}

	try {
		$focus = new Users();
		$focus->column_fields['user_name'] = $username;
		$focus->column_fields['first_name'] = 'SE';
		$focus->column_fields['last_name'] = $data['engineer_name'];
		$focus->column_fields['status'] = 'Active';
		$focus->column_fields['is_admin'] = 'off';
		$focus->column_fields['user_password'] = $userPassword;
		$focus->column_fields['confirm_password'] = $userPassword;
		$focus->column_fields['email1'] = $data['email'];
		$focus->column_fields['phone_mobile'] = $data['mobile_no'];
		$focus->column_fields['roleid'] = 'H6';
		$focus->column_fields['tz'] = 'Asia/Kolkata';
		$focus->column_fields['time_zone'] = 'Asia/Kolkata';
		$focus->column_fields['date_format'] = 'dd/mm/yyyy';
		$focus->column_fields['title'] = 'Asia';
		$focus->save("Users");
	} catch (Exception $e) {
		unSetAcceptValue($data['id']);
		error_log('User creation failed: ' . $e->getMessage());
		return true; // let the workflow continue
	}

	// Optional: send SMS
	global $smsEndPoint;
	$name = $data['engineer_name'];
	$badgeNo = $data['engineer_code'];
	$text = urlencode("Dear User, Hi, $name, Your account has been successfully validated. You can now login with $badgeNo and set your password. CRM Project");
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
		curl_exec($resource);
		curl_close($resource);
	}

	return true;
}

function unSetAcceptValue($id) {
	$db = PearDatabase::getInstance();
	$query = "UPDATE vtiger_engineer SET rejection_reason=?, eng_status=? WHERE engineerid=?";
	$db->pquery($query, array('', '', $id));
}