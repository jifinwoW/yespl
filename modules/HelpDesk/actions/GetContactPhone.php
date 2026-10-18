<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class HelpDesk_GetContactPhone_Action extends Vtiger_IndexAjax_View {

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

		if ($sourceModule !== 'Contacts') {
			$response->setResult(array('success' => false, 'message' => 'Invalid source module'));
			$response->emit();
			return;
		}

		$contactRecordModel = Vtiger_Record_Model::getInstanceById($recordId, 'Contacts');
		if (!$contactRecordModel) {
			$response->setResult(array('success' => false, 'message' => 'Contact record not found'));
			$response->emit();
			return;
		}

		// Prefer mobile (Customer Contact No); fall back to office phone
		$phone = trim((string) $contactRecordModel->get('mobile'));
		if ($phone === '') {
			$phone = trim((string) $contactRecordModel->get('phone'));
		}

		$response->setResult(array(
			'success' => true,
			'result' => array(
				'phone' => $phone,
			),
		));
		$response->emit();
	}
}
