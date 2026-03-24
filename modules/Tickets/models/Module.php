<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ************************************************************************************/

class Tickets_Module_Model extends Vtiger_Module_Model {

	public static function getAmcFieldMappings() {
		return array(
			'boid' => 'boid',
			'sapcode' => 'sapcode',
			'custcode' => 'custcode',
			'depocode' => 'depocode',
			'zone' => 'zone',
			'serviceengineer' => 'servicecoordintor',
			'typeofmc' => 'typeofmc',
			'model' => 'model',
			'serialno' => 'serialno',
			'doi' => 'doi',
			'vendor_id' => 'vendor_id',
			'service_location' => 'service_location',
			'connectivity' => 'connectivity',
			'device_details' => 'device_details',
			'gyro_type' => 'gyro_type',
			'gyro_model' => 'gyro_model',
			'gyro_serialno' => 'gyro_serialno',
			'ups_details' => 'ups_details',
			'ups_serialno' => 'ups_serialno',
			'warranty_month' => 'warranty_month',
			'warranty_start_date' => 'warranty_start_date',
			'warranty_end_date' => 'warranty_end_date',
			'engineer_id' => 'engineer_id',
			'contact_mobileno' => 'contact_mobileno',
			'address' => 'address',
			'city' => 'city',
			'state' => 'state',
			'pincode' => 'pincode',
			'parent_id' => 'account_id',
			'location_type' => 'location_type',
		);
	}

	public static function getAmcFieldValues(Vtiger_Record_Model $amcRecordModel) {
		$ticketModuleModel = Vtiger_Module_Model::getInstance('Tickets');
		$ticketFieldModels = $ticketModuleModel->getFields();
		$mappedFieldValues = array();

		foreach (self::getAmcFieldMappings() as $sourceFieldName => $targetFieldName) {
			$fieldValue = $amcRecordModel->get($sourceFieldName);
			$fieldModel = isset($ticketFieldModels[$targetFieldName]) ? $ticketFieldModels[$targetFieldName] : null;
			$isReferenceField = $fieldModel && $fieldModel->getFieldDataType() === 'reference';
			$displayValue = '';

			if ($isReferenceField && !empty($fieldValue)) {
				$displayValue = decode_html(Vtiger_Util_Helper::getRecordName($fieldValue));
			}

			$mappedFieldValues[$targetFieldName] = array(
				'value' => $fieldValue,
				'displayValue' => $displayValue,
				'isReference' => $isReferenceField,
			);
		}

		return $mappedFieldValues;
	}

	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Vtiger_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$parentQuickLinks = parent::getSideBarLinks($linkParams);

		$quickLink = array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_DASHBOARD',
				'linkurl' => $this->getDashBoardUrl(),
				'linkicon' => '',
		);

		//Check profile permissions for Dashboards
		$moduleModel = Vtiger_Module_Model::getInstance('Dashboard');
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if($permission) {
			$parentQuickLinks['SIDEBARLINK'][] = Vtiger_Link_Model::getInstanceFromValues($quickLink);
		}

		return $parentQuickLinks;
	}

	/**
	 * Function to get Settings links for admin user
	 * @return Array
	 */
	public function getSettingLinks() {
		$settingsLinks = parent::getSettingLinks();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		if ($currentUserModel->isAdminUser()) {
			$settingsLinks[] = array(
				'linktype' => 'LISTVIEWSETTING',
				'linklabel' => 'LBL_EDIT_MAILSCANNER',
				'linkurl' =>'index.php?parent=Settings&module=MailConverter&view=List',
				'linkicon' => ''
			);
		}
		return $settingsLinks;
	}


	/**
	 * Function returns Tickets grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	public function getOpenTickets() {
		$db = PearDatabase::getInstance();
		//TODO need to handle security
		$params = array();
		$picklistvaluesmap = getAllPickListValues("tickets_status");
        if(in_array('Open', $picklistvaluesmap)) $params[] = 'Open';
        
		if(php7_count($params) > 0) {
		$result = $db->pquery('SELECT count(*) AS count, COALESCE(vtiger_groups.groupname,vtiger_users.userlabel) as name, COALESCE(vtiger_groups.groupid,vtiger_users.id) as id  FROM vtiger_tickets
						INNER JOIN vtiger_crmentity ON vtiger_tickets.ticketsid = vtiger_crmentity.crmid
						LEFT JOIN vtiger_users ON vtiger_users.id=vtiger_crmentity.smownerid AND vtiger_users.status="ACTIVE"
						LEFT JOIN vtiger_groups ON vtiger_groups.groupid=vtiger_crmentity.smownerid
						'.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()).
						' WHERE vtiger_tickets.tickets_status = ? AND vtiger_crmentity.deleted = 0 GROUP BY smownerid', $params);
		}
		$data = array();
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
						$row['name'] = decode_html($row['name']);
			$data[] = $row;
		}
		return $data;
	}

	/**
	 * Function returns Tickets grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	public function getTicketsByStatus($owner, $dateFilter) {
		$db = PearDatabase::getInstance();

		$ownerSql = $this->getOwnerWhereConditionForDashBoards($owner);
		if(!empty($ownerSql)) {
			$ownerSql = ' AND '.$ownerSql;
		}

		$params = array();
		$dateFilterSql = '';
		if(!empty($dateFilter)) {
			$dateFilterSql = ' AND createdtime BETWEEN ? AND ? ';
			//appended time frame and converted to db time zone in showwidget.php
			$params[] = $dateFilter['start'];
			$params[] = $dateFilter['end'];
		}
		$picklistvaluesmap = getAllPickListValues("tickets_status");
        foreach($picklistvaluesmap as $picklistValue) {
            $params[] = $picklistValue;
        }

		$result = $db->pquery('SELECT COUNT(*) as count, CASE WHEN vtiger_tickets.tickets_status IS NULL OR vtiger_tickets.status = "" THEN "" ELSE vtiger_tickets.status END AS statusvalue 
							FROM vtiger_tickets INNER JOIN vtiger_crmentity ON vtiger_tickets.ticketid = vtiger_crmentity.crmid AND vtiger_crmentity.deleted=0
							'.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()). $ownerSql .' '.$dateFilterSql.
							' INNER JOIN vtiger_tickets_status ON vtiger_tickets.tickets_status = vtiger_tickets_status.tickets_status 
							WHERE vtiger_tickets.status IN ('.generateQuestionMarks($picklistvaluesmap).') 
							GROUP BY statusvalue ORDER BY vtiger_tickets_status.sortorderid', $params);

		$response = array();

		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$response[$i][0] = $row['count'];
			$ticketStatusVal = $row['statusvalue'];
			if($ticketStatusVal == '') {
				$ticketStatusVal = 'LBL_BLANK';
			}
			$response[$i][1] = vtranslate($ticketStatusVal, $this->getName());
			$response[$i][2] = $ticketStatusVal;
		}
		return $response;
	}

	/**
	 * Function to get relation query for particular module with function name
	 * @param <record> $recordId
	 * @param <String> $functionName
	 * @param Vtiger_Module_Model $relatedModule
	 * @return <String>
	 */
	public function getRelationQuery($recordId, $functionName, $relatedModule, $relationId) {
		if ($functionName === 'get_activities') {
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'vtiger_users.first_name', 'last_name' => 'vtiger_users.last_name'), 'Users');

			$query = "SELECT CASE WHEN (vtiger_users.user_name not like '') THEN $userNameSql ELSE vtiger_groups.groupname END AS user_name,
						vtiger_crmentity.*, vtiger_activity.activitytype, vtiger_activity.subject, vtiger_activity.date_start, vtiger_activity.time_start,
						vtiger_activity.recurringtype, vtiger_activity.due_date, vtiger_activity.time_end, vtiger_activity.visibility, vtiger_seactivityrel.crmid AS parent_id,
						CASE WHEN (vtiger_activity.activitytype = 'Task') THEN (vtiger_activity.status) ELSE (vtiger_activity.eventstatus) END AS status
						FROM vtiger_activity
						INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_activity.activityid
						LEFT JOIN vtiger_seactivityrel ON vtiger_seactivityrel.activityid = vtiger_activity.activityid
						LEFT JOIN vtiger_cntactivityrel ON vtiger_cntactivityrel.activityid = vtiger_activity.activityid
						LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
						LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
							WHERE vtiger_crmentity.deleted = 0 AND vtiger_activity.activitytype <> 'Emails'
								AND vtiger_seactivityrel.crmid = ".$recordId;

			$relatedModuleName = $relatedModule->getName();
			$query .= $this->getSpecificRelationQuery($relatedModuleName);
			$nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);
			if ($nonAdminQuery) {
				$query = appendFromClauseToQuery($query, $nonAdminQuery);

				if(trim($nonAdminQuery)) {
					$relModuleFocus = CRMEntity::getInstance($relatedModuleName);
					$condition = $relModuleFocus->buildWhereClauseConditionForCalendar();
					if($condition) {
						$query .= ' AND '.$condition;
					}
				}
			}
		} else {
			$query = parent::getRelationQuery($recordId, $functionName, $relatedModule, $relationId);
		}

		return $query;
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if (in_array($sourceModule, array('Assets', 'Project', 'ServiceContracts', 'Services'))) {
			$condition = " vtiger_tickets.ticketid NOT IN (SELECT relcrmid FROM vtiger_crmentityrel WHERE crmid = ? UNION SELECT crmid FROM vtiger_crmentityrel WHERE relcrmid = ?) ";
			$db = PearDatabase::getInstance();
            		$condition = $db->convert2Sql($condition, array($record, $record));
			$pos = stripos($listQuery, 'where');

			if ($pos) {
				$split = preg_split('/where/i', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery . ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}

	 /**
	 * Function to get list of field for header view
	 * @return <Array> list of field models <Vtiger_Field_Model>
	 */
	function getConfigureRelatedListFields(){
		$summaryViewFields = $this->getSummaryViewFieldsList();
		$headerViewFields = $this->getHeaderViewFieldsList();
		$allRelationListViewFields = array_merge($headerViewFields,$summaryViewFields);
		$relatedListFields = array();
		if(php7_count($allRelationListViewFields) > 0) {
			foreach ($allRelationListViewFields as $key => $field) {
				$relatedListFields[$field->get('column')] = $field->get('name');
			}
		}

		if(php7_count($relatedListFields)>0) {
			$nameFields = $this->getNameFields();
			foreach($nameFields as $fieldName){
				if(!isset($relatedListFields[$fieldName])) {
					$fieldModel = $this->getField($fieldName);
					$relatedListFields[$fieldModel->get('column')] = $fieldModel->get('name');
				}
			}
		}

		return $relatedListFields;
	}

	public function getTicketsByStatusCountsForUser($userId, $statusFilter = '', $conditions = []) {
		global $adb;
	
		$query = "SELECT tickets_status AS status, COUNT(*) as count
				  FROM vtiger_tickets
				  INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_tickets.ticketsid
				  WHERE vtiger_crmentity.deleted = 0 AND vtiger_crmentity.smownerid = ?";
	
		$params = array($userId);
	
		// Handle special In Progress group
		if (!empty($statusFilter) && $statusFilter !== 'All-Mobile-Field-List') {
			if ($statusFilter === 'In Progress') {
				$inStatuses = ['In Progress', 'Visit Scheduled', 'Spare Requested'];
				$placeholders = implode(',', array_fill(0, count($inStatuses), '?'));
				$query .= " AND vtiger_tickets.tickets_status IN ($placeholders)";
				$params = array_merge($params, $inStatuses);
			} else {
				$query .= " AND vtiger_tickets.tickets_status = ?";
				$params[] = $statusFilter;
			}
		}
	
		if (!empty($conditions)) {
			foreach ($conditions as $cond) {
				$field = $cond[0];
				$operator = $cond[1];
				$value = $cond[2];
				$query .= " AND vtiger_tickets.$field = ?";
				$params[] = $value;
			}
		}
	
		$query .= " GROUP BY tickets_status";
	
		$result = $adb->pquery($query, $params);
	
		$counts = [];
		while ($row = $adb->fetch_array($result)) {
			$counts[$row['status']] = $row['count'];
		}
	
		return $counts;
	}

	/**
	 * Declare which fields in Tickets should be auto-filled from a HelpDesk parent record.
	 * Used by RelationListView when building the create-record URL.
	 */
	public function getAutoFillModuleAndField($moduleName) {
		if ($moduleName === 'HelpDesk') {
			return [
				['module' => 'Contacts',          'fieldname' => 'contact_id'],
				['module' => 'Engineer',           'fieldname' => 'engineer_id'],
				['module' => 'Vendors',            'fieldname' => 'vendor_id'],
				['module' => 'ServiceCordinator',  'fieldname' => 'servicecoordintor'],
			];
		}
		return parent::getAutoFillModuleAndField($moduleName);
	}
	
	
}

