<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class Tickets_Edit_View extends Vtiger_Edit_View {

	protected function getFormattedAmcValue($targetFieldModel, $fieldValue) {
		if (!$targetFieldModel || $fieldValue === null || $fieldValue === '') {
			return $fieldValue;
		}

		if ($targetFieldModel->getFieldDataType() === 'date') {
			return Vtiger_Date_UIType::getDisplayDateValue($fieldValue);
		}

		return $fieldValue;
	}

	protected function getAmcRecordId(Vtiger_Request $request) {
		if ($request->get('record')) {
			return null;
		}

		$sourceRecord = $request->get('sourceRecord');
		$sourceModule = $request->get('sourceModule');
		if (!empty($sourceRecord) && $sourceModule === 'HelpDesk') {
			return $sourceRecord;
		}

		$amcRecordId = $request->get('amc_id');
		if (!empty($amcRecordId) && getSalesEntityType($amcRecordId) === 'HelpDesk') {
			return $amcRecordId;
		}

		return null;
	}

	protected function populateRequestFromAmc(Vtiger_Request $request, $amcRecordId) {
		$amcRecord = CRMEntity::getInstance('HelpDesk');
		$amcRecord->retrieve_entity_info($amcRecordId, 'HelpDesk');
		$ticketModuleModel = Vtiger_Module_Model::getInstance('Tickets');
		$ticketFieldModels = $ticketModuleModel->getFields();

		foreach (Tickets_Module_Model::getAmcFieldMappings() as $sourceFieldName => $targetFieldName) {
			$targetFieldModel = isset($ticketFieldModels[$targetFieldName]) ? $ticketFieldModels[$targetFieldName] : null;
			$fieldValue = $this->getFormattedAmcValue($targetFieldModel, $amcRecord->column_fields[$sourceFieldName]);
			$request->set($targetFieldName, $fieldValue);
		}

		$request->set('amc_id', $amcRecordId);
	}

    public function process(Vtiger_Request $request) {
        $amcRecordId = $this->getAmcRecordId($request);
        if ($amcRecordId) {
            $this->populateRequestFromAmc($request, $amcRecordId);
        }

        parent::process($request);
    }
}
