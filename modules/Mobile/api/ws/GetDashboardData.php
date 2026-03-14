<?php

class Mobile_WS_GetDashboardData extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;
        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $zone = '';

        $fieldName = 'zone';
        $fieldResult = Vtiger_Util_Helper::getPickListValues($fieldName);
        $options = [];
        foreach ($fieldResult as $id => $label) {
            $zones[] = [
                'label' => $label
            ];
        }

        // Determine service coordinator id for current user
        $username = '';
        if (is_object($current_user)) {
            if (!empty($current_user->user_name)) {
                $username = $current_user->user_name;
            } elseif (isset($current_user->column_fields['user_name'])) {
                $username = $current_user->column_fields['user_name'];
            }
        }

        foreach ($zones as $zoneItem) {
            $zone = $zoneItem['label'];
            $serviceCordinator = "SELECT servicecordinatorid, sc_zone FROM vtiger_servicecordinator WHERE sm_code = ? LIMIT 1";
            $scResult = $adb->pquery($serviceCordinator, array($username));
            $sc_id = 0;
            if ($scResult && $adb->num_rows($scResult) > 0) {
                $sc_id = $adb->query_result($scResult, 0, 'servicecordinatorid');
                
            }

            $engineersQuery = "SELECT COUNT(*) AS engineer_count FROM vtiger_engineer inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_engineer.engineerid
        WHERE vtiger_engineer.eng_status = 'Accepted' AND vtiger_crmentity.deleted=0 AND vtiger_engineer.servicecordintor_id = ? AND vtiger_engineer.eng_zone = ?";

            // Aggregate ticket counts: total, in progress, pending, closed
            // Aggregate ticket counts only for engineers belonging to this service coordinator
            $ticketsQuery = "SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN tickets_status IN ('In Progress', 'Visit Scheduled') THEN 1 ELSE 0 END) AS in_progress,
                SUM(CASE WHEN tickets_status = 'Pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN tickets_status = 'Closed' THEN 1 ELSE 0 END) AS closed
            FROM vtiger_tickets
            INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_tickets.ticketsid
            WHERE vtiger_crmentity.deleted = 0 AND vtiger_tickets.zone = ?
              AND vtiger_tickets.engineer_id IN (
                  SELECT vtiger_engineer.engineerid FROM vtiger_engineer
                  INNER JOIN vtiger_crmentity AS eng_crm ON eng_crm.crmid = vtiger_engineer.engineerid
                  WHERE vtiger_engineer.servicecordintor_id = ? AND vtiger_engineer.eng_zone = ? AND eng_crm.deleted = 0
              )";

            // Execute engineer count
            $engResult = $adb->pquery($engineersQuery, [$sc_id, $zone]);
            $engRow = $adb->fetch_array($engResult);
            $engineerCount = isset($engRow['engineer_count']) ? (int)$engRow['engineer_count'] : 0;

            // Execute ticket aggregates (filter by service coordinator id)
            $ticketResult = $adb->pquery($ticketsQuery, [$zone,$sc_id,$zone]);
            $ticketRow = $adb->fetch_array($ticketResult);

 $data[] = [
            'zone' => $zone,
            'total_engineers' => $engineerCount,
            'total_tickets' => isset($ticketRow['total']) ? (int)$ticketRow['total'] : 0,
            'in_progress_tickets' => isset($ticketRow['in_progress']) ? (int)$ticketRow['in_progress'] : 0,
            'pending_tickets' => isset($ticketRow['pending']) ? (int)$ticketRow['pending'] : 0,
            'closed_tickets' => isset($ticketRow['closed']) ? (int)$ticketRow['closed'] : 0,
        ];
        }


       



        $response->setApiSucessMessage('Successfully Fetched Data');
        $response->setResult($data);
        return $response;
    }
}
