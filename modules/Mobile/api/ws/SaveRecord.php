<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/FetchRecordWithGrouping.php';

include_once 'include/Webservices/Create.php';
include_once 'include/Webservices/Update.php';

class Mobile_WS_SaveRecord extends Mobile_WS_FetchRecordWithGrouping {
	protected $recordValues = false;
	
	// Avoid retrieve and return the value obtained after Create or Update
	protected function processRetrieve(Mobile_API_Request $request) {
		return $this->recordValues;
	}
	
	function process(Mobile_API_Request $request) {
		global $current_user,$adb; // Required for vtws_update API
		$current_user = $this->getActiveUser();

       // $sql="SELECT MAX(fieldid) AS last_fieldid FROM vtiger_field";
	   // $sqlResult = $adb->pquery($sql);
      //  $fieldid = $adb->query_result($sqlResult, 0, 'last_fieldid');
     // $adb->pquery("INSERT INTO `vtiger_fieldmodulerel` (`fieldid`, `module`, `relmodule`, `status`, `sequence`) VALUES ('1036', 'StockTransfer', 'Tickets', NULL, NULL)");
     // echo $fieldid;
     // exit();
      
       //$adb->pquery("INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`, `summaryfield`, `headerfield`, `isunique`) 
      //VALUES ('58', '1034', 'tickets_no', 'vtiger_tickets', '1', '4', 'tickets_no', 'Ticket No', '1', '0', '', '100',
             // '2', '140', '1', 'V~O', '3', NULL, 'BAS', '0', NULL, '0', '0', '0')");
       //$adb->pquery("update vtiger_field_seq set id= 1034");
      //$adb->pquery("INSERT INTO `vtiger_modentity_num` (`num_id`, `semodule`, `prefix`, `start_id`, `cur_id`, `active`) VALUES ('23', 'Tickets', 'Tickets', '1', '25', '1')");
      //$adb->pquery("update vtiger_modentity_num_seq set id= 24");  
    // $adb->pquery("ALTER TABLE `vtiger_tickets` ADD `tickets_no` VARCHAR(100) NOT NULL AFTER `ticketsid`");
    //  $adb->pquery("ALTER TABLE `vtiger_tickets` DROP COLUMN `ticket_no`");
     // $adb->pquery("UPDATE `vtiger_entityname` SET `fieldname` = 'tickets_no' WHERE `vtiger_entityname`.`tabid` = 58");
      //$adb->pquery("UPDATE `vtiger_relatedlists` SET `name` = 'get_dependents_list',`relationtype`='N:N' WHERE `vtiger_relatedlists`.`relation_id` = 179;");
 //$adb->pquery("UPDATE `vtiger_field` SET `headerfield` = '1' WHERE `vtiger_field`.`fieldname` = 'tickets_no' and  `vtiger_field`.`tablename` = 'vtiger_tickets';");
     // $adb->pquery("INSERT INTO `vtiger_fieldmodulerel` (`fieldid`, `module`, `relmodule`, `status`, `sequence`) VALUES ('1065', 'Tickets', 'Accounts', NULL, NULL);");
      
     // $result = $adb->pquery("SELECT *  FROM `vtiger_field` WHERE `columnname` LIKE 'tickets_no' and tablename = 'vtiger_tickets';");
   	 // $ticket_no = $adb->query_result($result, 0, 'headerfield');
     // echo $ticket_no;
      //$adb->pquery("UPDATE vtiger_field SET headerfield = '1' WHERE fieldid = 1039");
      
      //$result = $adb->pquery("SELECT tickets_no FROM `vtiger_tickets` WHERE ticketsid =884");
   	 // $ticket_no = $adb->query_result($result, 0, 'tickets_no');
     // echo $ticket_no;
     // exit();
		$module = $request->get('module');
		$recordid = $request->get('record');
		$valuesJSONString =  $request->get('values');
		
		$values = "";
		if(!empty($valuesJSONString) && is_string($valuesJSONString)) {
			$values = Zend_Json::decode($valuesJSONString);
		} else {
			$values = $valuesJSONString; // Either empty or already decoded.
		}
		$response = new Mobile_API_Response();
		if (empty($values)) {
			$response->setError(1501, "Values cannot be empty!");
			return $response;
		}
		try {
			if (empty($recordid) || vtws_recordExists($recordid)) {
				// Retrieve or Initalize
                if (!empty($recordid) && !$this->isTemplateRecordRequest($request)) {
					$this->recordValues = vtws_retrieve($recordid, $current_user);
                } else {
					$this->recordValues = array();
				}
				// Set the modified values
				foreach($values as $name => $value) {
                    $this->recordValues[$name] = $value;
				}
                // Update or Create
               if (isset($this->recordValues['id'])) {
                    $this->recordValues = vtws_update($this->recordValues, $current_user);
                } else {
                    // Set right target module name for Calendar/Event record
                    if ($module == 'Calendar') {
                       if (!empty($this->recordValues['eventstatus']) && $this->recordValues['activitytype'] != 'Task') { 
                         $module = 'Events';
                       }
                    }
					// to save Source of Record while Creating
                    $this->recordValues['source'] = 'MOBILE';
                    $this->recordValues = vtws_create($module, $this->recordValues, $current_user);
               }
                // Update the record id
                $request->set('record', $this->recordValues['id']);
                // Gather response with full details
                $response = parent::process($request);
				$response->setApiSucessMessage('Successfully Insert Data');
				return $response;
            } else {
                $response->setError("RECORD_NOT_FOUND", "Record does not exist");
                return $response;
			}
		} catch (DuplicateException $e) {
			$response->setError($e->getCode(), $e->getMessage());
        } catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
		return $response;
	}

}
