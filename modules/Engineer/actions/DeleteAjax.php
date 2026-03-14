<?php
class Engineer_DeleteAjax_Action extends Vtiger_DeleteAjax_Action {

	public function process(Vtiger_Request $request) {
		$response = new Vtiger_Response();
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$recordModel = Vtiger_Record_Model::getInstanceById($recordId, $moduleName);
		if ($recordModel->get('eng_status') == 'Accepted') {
			$isAlreadyUserExits = $this->isUserAlreadyCraeted($recordModel->get('badge_no'));
			if ($isAlreadyUserExits == true) {
				$response->setError('Please Delete User In User Management Section');
				$response->emit();
				exit();
			}
		}
		$recordModel->delete();

		$cvId = $request->get('viewname');
		deleteRecordFromDetailViewNavigationRecords($recordId, $cvId, $moduleName);
		$response->setResult(array('viewname' => $cvId, 'module' => $moduleName));
		$response->emit();
	}

	public function isUserAlreadyCraeted($badgeNumber) {
		global $adb;
		$result = $adb->pquery('SELECT 1 FROM `vtiger_users` where user_name = ?', array($badgeNumber));
		$rowCount = $adb->num_rows($result);
		if ($rowCount > 0) {
			return true;
		} else {
			return false;
		}
	}
}
