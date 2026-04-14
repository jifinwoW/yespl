<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class HelpDesk_GetEngineerMobileNo_Action extends Vtiger_IndexAjax_View {

	public function requiresPermission(Vtiger_Request $request) {
		$permissions[] = array('module_parameter' => 'source_module', 'action' => 'DetailView', 'record_parameter' => 'record');
		return $permissions;
	}

	public function process(Vtiger_Request $request) {
		$recordId = $request->get('record');
		$sourceModule = $request->get('source_module');
		$response = new Vtiger_Response();

		if (empty($sourceModule) && !empty($recordId)) {
			$sourceModule = getSalesEntityType($recordId);
		}

		if ($sourceModule !== 'Engineer') {
			$response->setResult(array('success' => false, 'message' => 'Invalid source module'));
			$response->emit();
			return;
		}

		$engineerRecordModel = Vtiger_Record_Model::getInstanceById($recordId, 'Engineer');
		if (!$engineerRecordModel) {
			$response->setResult(array('success' => false, 'message' => 'Engineer record not found'));
			$response->emit();
			return;
		}

		$response->setResult(array(
			'success' => true,
			'result' => array(
				'mobile_no' => $engineerRecordModel->get('mobile_no'),
			),
		));
		$response->emit();
	}
}
