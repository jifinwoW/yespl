<?php

class Mobile_WS_GetEngineerList extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;
        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $username = $current_user->column_fields['user_name'];
        $zone = $request->get('zone');

        // Get Service Coordinator ID
        $scquery = "SELECT servicecordinatorid FROM vtiger_servicecordinator WHERE sm_code = ? LIMIT 1";
        $SCresult = $adb->pquery($scquery, [$username]);

        if ($adb->num_rows($SCresult) == 0) {
            $response->setError(404, 'Service Coordinator not found');
            return $response;
        }

        while ($scrow = $adb->fetch_array($SCresult)) {
            $scid = $scrow['servicecordinatorid'];

            // Build zone filter for engineers
            $where = '';
            $params = [$scid]; // first param is SC ID

            if (!empty($zone) && strtolower($zone) !== 'all') {
                $where .= " AND vtiger_engineer.eng_zone = ? ";
                $params[] = $zone;
            }

            // Get engineers under this SC with zone filter
            $query = "SELECT engineerid, engineer_name, mobile_no, email, eng_status, eng_zone 
                      FROM vtiger_engineer
                      INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_engineer.engineerid
                      WHERE vtiger_engineer.servicecordintor_id = ? AND vtiger_crmentity.deleted = 0 $where";

            $result = $adb->pquery($query, $params);

            $engineers = [];
            while ($row = $adb->fetch_array($result)) {
                $engID = $row['engineerid'];

                // Get ticket counts for this engineer (no zone filter needed here)
                $ticketStatsQuery = "SELECT tickets_status, COUNT(*) as count
                                     FROM vtiger_tickets
                                     INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_tickets.ticketsid
                                     WHERE engineer_id = ? AND vtiger_crmentity.deleted = 0
                                     GROUP BY tickets_status";

                $ticketStatsResult = $adb->pquery($ticketStatsQuery, [$engID]);

                $ticketStats = ['Total' => 0];
                while ($statusRow = $adb->fetch_array($ticketStatsResult)) {
                    $status = $statusRow['tickets_status'];
                    $count = (int)$statusRow['count'];
                    $ticketStats['Total'] += $count;
                    $ticketStats[$status] = $count;
                }

                $engineers[] = [
                    'engineerid' => $row['engineerid'],
                    'name' => $row['engineer_name'],
                    'mobile' => $row['mobile_no'],
                    'email' => $row['email'],
                    'status' => $row['eng_status'],
                    'zone'  => $row['eng_zone'],
                    'ticket_stats' => $ticketStats
                ];
            }

            if (empty($engineers)) {
                $response->setError(404, 'No engineers found');
            } else {
                $response->setApiSucessMessage('Successfully Fetched Data');
                $response->setResult($engineers);
            }

            return $response;
        }
    }
}
