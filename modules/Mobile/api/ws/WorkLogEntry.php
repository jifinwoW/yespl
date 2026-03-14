<?php

class Mobile_WS_WorkLogEntry extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;

        $response = new Mobile_API_Response();
        $current_user = $this->getActiveUser();

        // Ensure we have a valid active user object before proceeding
        if (!$current_user || !is_object($current_user)) {
            $response->setError(1002, "Invalid or inactive user");
            return $response;
        }

        $ticketID = $request->get('ticket_id');
        $username = $current_user->column_fields['user_name'];

        $ticketQuery = "SELECT ticketsid FROM vtiger_tickets WHERE ticketsid = ? LIMIT 1";
        $ticketResult = $adb->pquery($ticketQuery, array($ticketID));

        if ($ticketResult && $adb->num_rows($ticketResult) > 0) {
            $ticketID = $adb->query_result($ticketResult, 0, 'ticketsid');
        } else {
            $response->setError(1001, "Ticket not found");
            return $response;
        }

        if (empty($ticketID) || !is_numeric($ticketID)) {
            $response->setError(1000, "Missing or invalid required parameters");
            return $response;
        }
        
        $EngineerQuery = "SELECT engineerid FROM vtiger_engineer WHERE engineer_code = ? LIMIT 1";
        $engResult = $adb->pquery($EngineerQuery, array($username));

        if ($engResult && $adb->num_rows($engResult) > 0) {
            $engineerID = $adb->query_result($engResult, 0, 'engineerid');
        } else {
            $response->setError(1001, "Engineer not found for current user");
            return $response;
        }

        // Prepare working date/time values
        $workingDate = $request->get('working_date'); // Working Date (cf_1163)
        $workingStart = $request->get('working_start_time'); // Working Start Time (cf_1165)
        $workingEnd = $request->get('working_end_time'); // Working End Time (cf_1167)

        // Check for an existing TicketTimeLog for the same ticket, same date and same engineer
        $checkQuery = "SELECT t.tickettimelogid FROM vtiger_tickettimelog t
            INNER JOIN vtiger_tickettimelogcf cf ON cf.tickettimelogid = t.tickettimelogid
            WHERE t.ttl_ticketid = ? AND t.ttl_engineer = ? AND cf.cf_1163 = ? LIMIT 1";
        $checkResult = $adb->pquery($checkQuery, array($ticketID, $engineerID, $workingDate));

        if ($checkResult && $adb->num_rows($checkResult) > 0) {
            // Update existing record
            $existingId = $adb->query_result($checkResult, 0, 'tickettimelogid');
            $workLog = Vtiger_Record_Model::getInstanceById($existingId, 'TicketTimeLog');
            $workLog->set('mode', 'edit');
            $isUpdate = true;
        } else {
            // Create new record
            $workLog = Vtiger_Record_Model::getCleanInstance('TicketTimeLog');
            
            $isUpdate = false;
        }
        $workLog->set('ttl_ticketid', $ticketID);
        $workLog->set('ttl_engineer', $engineerID);
        $workLog->set('assigned_user_id', $current_user->id);
        $workLog->set('cf_1163', $workingDate); // Working Date
        $workLog->set('cf_1165', $workingStart); // Working Start Time
        $workLog->set('cf_1167', $workingEnd); // Working End Time
        $workLog->save();
        
        $responseData = [
            'worklog_id' => $workLog->getId(),
            'ticket_id' => $ticketID,
            'engineer_id' => $engineerID,
        ];



        $response->setApiSucessMessage('Successfully Logged Work Time');
        $response->setResult($responseData);
        return $response;
    }
}

?>
