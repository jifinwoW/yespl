<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Tickets_GetAmcData_Action extends Vtiger_IndexAjax_View {

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

		if ($sourceModule !== 'HelpDesk') {
			$response->setResult(array('success' => false, 'data' => array()));
			$response->emit();
			return;
		}

		$amcRecordModel = Vtiger_Record_Model::getInstanceById($recordId, $sourceModule);
		$response->setResult(array(
			'success' => true,
			'data' => Tickets_Module_Model::getAmcFieldValues($amcRecordModel),
		));
		$response->emit();
	}
}