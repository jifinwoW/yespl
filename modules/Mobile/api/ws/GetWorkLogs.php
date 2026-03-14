<?php

class Mobile_WS_GetWorkLogs extends Mobile_WS_Controller
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

        $worktimelogs = "SELECT t.ttl_ticketid, t.ttl_engineer, cf.cf_1163 AS working_date,
            cf.cf_1165 AS working_start_time, cf.cf_1167 AS working_end_time,
            e.engineer_name
            FROM vtiger_tickettimelog t
            INNER JOIN vtiger_tickettimelogcf cf ON cf.tickettimelogid = t.tickettimelogid
            INNER JOIN vtiger_engineer e ON e.engineerid = t.ttl_engineer
            WHERE t.ttl_ticketid = ? AND t.ttl_engineer = ?";
        $params = array($ticketID, $engineerID);
        if (!empty($workingDate)) {
            $worktimelogs .= " AND cf.cf_1163 = ?";
            $params[] = $workingDate;
        }
        $logResult = $adb->pquery($worktimelogs, $params);
        $responseData = array();
        while ($logRow = $adb->fetch_array($logResult)) {
            $responseData[] = array(
                'ticket_id' => $logRow['ttl_ticketid'],
                'engineer_id' => $logRow['ttl_engineer'],
                'engineer_name' => $logRow['engineer_name'],
                'working_date' => $logRow['working_date'],
                'working_start_time' => $logRow['working_start_time'],
                'working_end_time' => $logRow['working_end_time']
            );
        }
        if (empty($responseData)) {
            $response->setError(1003, 'No work log data found');
        } else {
            $response->setApiSucessMessage('Successfully Logged Work Time');
            $response->setResult($responseData);
        }
        return $response;
    }
}

?>
