<?php

function getUserDetailsBasedOnEmployeeModuleG($badgeNo) {
	global $adb;
	// static $cacheOfBadges = array();
	// if (!isset($cacheOfBadges[$badgeNo]) && empty($cacheOfBadges[$badgeNo])) {
	$sql = 'select engineerid from vtiger_engineer '
		. ' inner join vtiger_crmentity on vtiger_crmentity.crmid = vtiger_engineer.engineerid '
		. ' where vtiger_engineer.engineer_code = ? and vtiger_crmentity.deleted = 0';
	$sqlResult = $adb->pquery($sql, array($badgeNo));
	$num_rows = $adb->num_rows($sqlResult);

	if ($num_rows > 0) {
		$userData = '';
		while ($row = $adb->fetch_array($sqlResult)) {
			$userData =  $row;
		}
		// $cacheOfBadges[$badgeNo] = $userData;
		return $userData;
	} else {
		return false;
	}
	// } else {
	// 	$cacheOfBadges[$badgeNo];
	// }
}
