<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/models/Alert.php';
include_once dirname(__FILE__) . '/models/SearchFilter.php';
include_once dirname(__FILE__) . '/models/Paging.php';

class Mobile_WS_ListModuleRecords extends Mobile_WS_Controller {

	function isCalendarModule($module) {
		return ($module == 'Events' || $module == 'Calendar');
	}
	
	function getSearchFilterModel($module, $search) {
		return Mobile_WS_SearchFilterModel::modelWithCriterias($module, Zend_JSON::decode($search));
	}
	
	function getPagingModel(Mobile_API_Request $request) {
		$page = $request->get('page', 0);
		return Mobile_WS_PagingModel::modelWithPageStart($page);
	}

	function process(Mobile_API_Request $request) {
		$current_user = $this->getActiveUser();
		$module = $request->get('module');
		$filterId = $request->get('filterid');
		$page = $request->get('page','1');
		$orderBy = $request->getForSql('orderBy');
		$sortOrder = $request->getForSql('sortOrder');
		
		$moduleModel = Vtiger_Module_Model::getInstance($module);
		$headerFieldModels = $moduleModel->getHeaderViewFieldsList();
		
		$headerFields = array();
		$fields = array();
		$headerFieldColsMap = array();
		$headerFieldStatusValue = $request->get('tickets_status');

		$subcalltype = $request->get('subcalltype');

		$nameFields = $moduleModel->getNameFields();

		$additinalConditions = [];
		if(!empty($subcalltype)){
			$additinalCondition = array('subcalltype', 'e', $subcalltype);
			array_push($additinalConditions, $additinalCondition);
		}
		if (!empty($headerFieldStatusValue)) {
			$additinalCondition = array('tickets_status', 'e', $headerFieldStatusValue);
			array_push($additinalConditions, $additinalCondition);
			$nameFields = $this->getConfiguredStatusFields($headerFieldStatusValue);
		} else {
			if (empty($headerFieldStatusValue)) {
				$headerFieldStatusValue = 'All-Mobile-Field-List';
			}
			$nameFields = $this->getConfiguredStatusFields($headerFieldStatusValue);
			// $nameFields =  $moduleModel->getNameFields();
		}

		

		if(is_string($nameFields)) {
			$nameFieldModel = $moduleModel->getField($nameFields);
			$headerFields[] = $nameFields;
			$fields = array('name'=>$nameFieldModel->get('name'), 'label'=>$nameFieldModel->get('label'), 'fieldType'=>$nameFieldModel->getFieldDataType());
		} else if(is_array($nameFields)) {
			foreach($nameFields as $nameField) {
				$nameFieldModel = $moduleModel->getField($nameField);
				$headerFields[] = $nameField;
				$fields[] = array('name'=>$nameFieldModel->get('name'), 'label'=>$nameFieldModel->get('label'), 'fieldType'=>$nameFieldModel->getFieldDataType());
			}
		}
		
		foreach($headerFieldModels as $fieldName => $fieldModel) {
			$headerFields[] = $fieldName;
			$fields[] = array('name'=>$fieldName, 'label'=>$fieldModel->get('label'), 'fieldType'=>$fieldModel->getFieldDataType());
			$headerFieldColsMap[$fieldModel->get('column')] = $fieldName;
		}

		if ($module == 'HelpDesk') $headerFieldColsMap['title'] = 'ticket_title';
		if ($module == 'Documents') $headerFieldColsMap['title'] = 'notes_title';

		$listViewModel = Vtiger_ListView_Model::getInstance($module, $filterId, $headerFields);
		
		if(!empty($sortOrder)) {
			$listViewModel->set('orderby', $orderBy);
			$listViewModel->set('sortorder',$sortOrder);
		}

		if (!empty($request->get('search_key'))) {
			$listViewModel->set('search_key', $request->get('search_key'));
			$listViewModel->set('search_value', $request->get('search_value'));
			$listViewModel->set('operator', $request->get('operator'));
		}
		$response = new Mobile_API_Response();
		if (!empty($request->get('search_params')) || !empty($request->get('tickets_status')) || !empty($request->get('subcalltype')) ) {
			if (is_string($request->get('search_params'))) {
				$searchParams = json_decode($request->get('search_params'), true);
			} else {
				$searchParams = $request->get('search_params');
			}
		
			if (empty($searchParams)) {
				$searchParams = [];
			}
		
			// Wipe any empty arrays to prevent preg_match() errors
			$searchParams = array_filter($searchParams, function ($val) {
				return is_array($val) && !empty($val);
			});
		
			// ✅ Proper OR-style filter using comma-separated values
			if (!empty($request->get('tickets_status'))) {
				if($request->get('tickets_status') == 'In Progress'){
				$searchParams[] = [['tickets_status', 'c', 'In Progress,Visit Scheduled,Waiting for Input,Approved,Rejected,Spare Requested']];
				}else{
					$searchParams[] = $additinalConditions;
				}
			}

			if (!empty($request->get('subcalltype'))) {
			
				$searchParams[] = [['subcalltype', 'c', $request->get('subcalltype')]];
				
			}

			
		
			// Now transform
			$transformedSearchParams = $this->transferListSearchParamsToFilterCondition($searchParams, $listViewModel->getModule());
		
			$listViewModel->set('search_params', $transformedSearchParams);
		}
		

		$listViewModel->set('searchAllFieldsValue', $request->get('searchAllFieldsValue'));
		
		$pagingModel = new Vtiger_Paging_Model();
		$pageLimit = $pagingModel->getPageLimit();
		$pagingModel->set('page', $page);
		$pagingModel->set('limit', $pageLimit+1);
		
		$listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		
		if(empty($filterId)) {
			$customView = new CustomView($module);
			$filterId = $customView->getViewId($module);
		}

	
		
		if ($listViewEntries) {
			foreach ($listViewEntries as $index => $listViewEntryModel) {
				$data = $listViewEntryModel->getRawData();
				$record = array('id' => $listViewEntryModel->getId());
				
				foreach ($data as $i => $value) {
					if (is_string($i)) {
						// Transform header-field (column to fieldname) in response.
						if (isset($headerFieldColsMap[$i])) {
							$i = $headerFieldColsMap[$i];
						}
						
						$record[$i] = decode_html($value);
						
						// Add custom processing for specific fields
						if ($i == 'status') {
							$record['tickets_status'] = $value;
						} elseif ($i == 'ticketsid') {
							$record[$i] = '40x' . $record[$i];
						} elseif ($i == 'assigned_user_id') {
							$record[$i] = Vtiger_Functions::getUserRecordLabel($record[$i]);
						} elseif ($i == 'contact_id') {
							$record[$i] = Vtiger_Functions::getCRMRecordLabel($record[$i]);
						} elseif ($i == 'vendor_id') {
							$record[$i] = Vtiger_Functions::getCRMRecordLabel($record[$i]);
						}
					}
				}
		
				// Retrieve and add tickets_no to the record
                global $adb;
				if (!isset($data['tickets_no'])) {
            
                  $result = $adb->pquery("SELECT tickets_no FROM `vtiger_tickets` WHERE ticketsid =".$record['id']);
   	              $tickets_no = $adb->query_result($result, 0, 'tickets_no');
                  
				  $record['tickets_no'] = $tickets_no;
				}
				if ($data['location_type'] == 'Local') {
					$record['sla'] = 'SLA 24 HRS';
				} elseif ($data['location_type'] == 'Remote') {
					$record['sla'] = 'SLA 48 HRS';
				} elseif ($data['location_type'] == 'UpCountry') {
					$record['sla'] = 'SLA 72 HRS';
				}
				$records[] = $record;
			}
		}
		
		
		$moreRecords = false;
		if(php7_count($listViewEntries) > $pageLimit) {
			$moreRecords = true;
			array_pop($records);
		}

		$response = new Mobile_API_Response();
		$response->setApiSucessMessage('Successfully Fetched Data');

		$moduleModel = Vtiger_Module_Model::getInstance('Tickets');
		global $current_user;
        require_once('include/utils/GeneralUtils.php');
        $data = getUserDetailsBasedOnEmployeeModuleG($current_user->user_name);
        $counts = $moduleModel->getTicketsByStatusCountsForUser($current_user->id, $headerFieldStatusValue);
		$response->setResult(array(
		                        	'ticketStatusCounts' => $counts,
		                            'records'=>$records, 
									'headers'=>$fields, 
									'selectedFilter'=>$filterId, 
									'nameFields'=>$nameFields,
									'moreRecords'=>$moreRecords,
									'orderBy'=>$orderBy,
									'sortOrder'=>$sortOrder,
									'page'=>$page));
		return $response;
	}
	public function transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel) {
		return Vtiger_Util_Helper::transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel);
	}


	
	function getConfiguredStatusFields($statusFilterName) {
		global $adb;
		$statusFilterName = 'All-Mobile-Field-List';
		$sql = "SELECT columnname FROM `vtiger_customview` inner join vtiger_cvcolumnlist " .
			"on vtiger_cvcolumnlist.cvid = vtiger_customview.cvid " .
			"where vtiger_customview.viewname = ? and vtiger_customview.userid = 1 
			and vtiger_customview.entitytype = 'HelpDesk' 
			ORDER BY `vtiger_cvcolumnlist`.`columnindex` ASC";
		$result = $adb->pquery($sql, array($statusFilterName));
		$columns = [];
		while ($row = $adb->fetch_array($result)) {
			$columnname = $row['columnname'];
			$columnname = explode(':', $columnname);
			array_push($columns, $columnname[2]);
		}
		return $columns;
	}

	function processSearchRecordLabelForCalendar(Mobile_API_Request $request, $pagingModel = false) {
		$current_user = $this->getActiveUser();
		
		// Fetch both Calendar (Todo) and Event information
		$moreMetaFields = array('date_start', 'time_start', 'activitytype', 'location');
		$eventsRecords = $this->fetchRecordLabelsForModule('Events', $current_user, $moreMetaFields, false, $pagingModel);
		$calendarRecords=$this->fetchRecordLabelsForModule('Calendar', $current_user, $moreMetaFields, false, $pagingModel);

		// Merge the Calendar & Events information
		$records = array_merge($eventsRecords, $calendarRecords);
		
		$modifiedRecords = array();
		foreach($records as $record) {
			$modifiedRecord = array();
			$modifiedRecord['id'] = $record['id'];                      unset($record['id']);
			$modifiedRecord['eventstartdate'] = $record['date_start'];  unset($record['date_start']);
			$modifiedRecord['eventstarttime'] = $record['time_start'];  unset($record['time_start']);
			$modifiedRecord['eventtype'] = $record['activitytype'];     unset($record['activitytype']);
			$modifiedRecord['eventlocation'] = $record['location'];     unset($record['location']);
			
			$modifiedRecord['label'] = implode(' ',array_values($record));
			
			$modifiedRecords[] = $modifiedRecord;
		}
		
		$response = new Mobile_API_Response();
		$response->setResult(array('records' =>$modifiedRecords, 'module'=>'Calendar'));
		
		return $response;
	}
	
	function fetchRecordLabelsForModule($module, $user, $morefields=array(), $filterOrAlertInstance=false, $pagingModel = false) {
		if($this->isCalendarModule($module)) {
			$fieldnames = Mobile_WS_Utils::getEntityFieldnames('Calendar');
		} else {
			$fieldnames = Mobile_WS_Utils::getEntityFieldnames($module);
		}
		
		if(!empty($morefields)) {
			foreach($morefields as $fieldname) $fieldnames[] = $fieldname;
		}

		if($filterOrAlertInstance === false) {
			$filterOrAlertInstance = Mobile_WS_SearchFilterModel::modelWithCriterias($module);
			$filterOrAlertInstance->setUser($user);
		}
			
		return $this->queryToSelectFilteredRecords($module, $fieldnames, $filterOrAlertInstance, $pagingModel);
	}
	
	function queryToSelectFilteredRecords($module, $fieldnames, $filterOrAlertInstance, $pagingModel) {
		
		if ($filterOrAlertInstance instanceof Mobile_WS_SearchFilterModel) {
			return $filterOrAlertInstance->execute($fieldnames, $pagingModel);
		}
		
		global $adb;
		
		$moduleWSId = Mobile_WS_Utils::getEntityModuleWSId($module);
		$columnByFieldNames = Mobile_WS_Utils::getModuleColumnTableByFieldNames($module, $fieldnames);

		// Build select clause similar to Webservice query
		$selectColumnClause = "CONCAT('{$moduleWSId}','x',vtiger_crmentity.crmid) as id,";
		foreach($columnByFieldNames as $fieldname=>$fieldinfo) {
			$selectColumnClause .= sprintf("%s.%s as %s,", $fieldinfo['table'],$fieldinfo['column'],$fieldname);
		}
		$selectColumnClause = rtrim($selectColumnClause, ',');
		
		$query = $filterOrAlertInstance->query();
		$query = preg_replace("/SELECT.*FROM(.*)/i", "SELECT $selectColumnClause FROM $1", $query);
		
		if ($pagingModel !== false) {
			$query .= sprintf(" LIMIT %s, %s", $pagingModel->currentCount(), $pagingModel->limit());
		}

		$prequeryResult = $adb->pquery($query, $filterOrAlertInstance->queryParameters());
		return new SqlResultIterator($adb, $prequeryResult);
	}
	
}
