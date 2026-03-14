<?php

class Mobile_WS_GetTicketsByEngineer extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;
        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $engID = $request->get('engineerid');
        $filter = $request->get('filter'); // e.g., 'Open', 'Closed'
        $type   = $request->get('type');   // e.g., 'All', 'Incident'
        $zone   = $request->get('zone'); 
        
        if (empty($engID)) {   
            $response->setError(1501, 'Engineer ID is missing');
            return $response;
        }

        // ------------------------------
        // Base WHERE conditions
        // ------------------------------
        $where = " WHERE engineer_id = ? AND vtiger_crmentity.deleted = 0 ";
        $params = [$engID];

        // Apply filter (tickets_status)
        if (!empty($filter)) {
            $where .= " AND vtiger_tickets.tickets_status = ? ";
            $params[] = $filter;
        }

        // Apply type (subcalltype)
        if (!empty($type) && strtolower($type) !== 'all') {
            $where .= " AND vtiger_tickets.subcalltype = ? ";
            $params[] = $type;
        }

         if (!empty($zone) && strtolower($zone) !== 'all') {
            $where .= " AND vtiger_tickets.zone = ? ";
            $params[] = $zone;
        }

        // ------------------------------
        // Ticket status counts
        // ------------------------------
        $statusCountsQuery = $adb->pquery(
            "SELECT tickets_status, COUNT(*) as count
             FROM vtiger_tickets
             INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_tickets.ticketsid
             $where
             GROUP BY tickets_status",
            $params
        );

        $ticketStats = ['Total' => 0];
        while ($statusRow = $adb->fetch_array($statusCountsQuery)) {
            $status = $statusRow['tickets_status'];
            $count = (int)$statusRow['count'];
            $ticketStats['Total'] += $count;
            $ticketStats[$status] = $count;
        }

        // ------------------------------
        // Ticket details
        // ------------------------------
        $ticketsQuery = $adb->pquery(
            "SELECT vtiger_tickets.ticketsid, vtiger_tickets.typeofmc, vtiger_tickets.tickets_status,
                    vtiger_tickets.subcalltype, vtiger_tickets.zone, vtiger_crmentity.createdtime
             FROM vtiger_tickets
             INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_tickets.ticketsid
             $where",
            $params
        );

        $ticketDetails = [];
        while ($ticketRow = $adb->fetch_array($ticketsQuery)) {
            $ticketDetails[] = [
                'ticketsid'      => $ticketRow['ticketsid'],
                'typeofmc'       => $ticketRow['typeofmc'],
                'tickets_status' => $ticketRow['tickets_status'],   
                'subcalltype'    => $ticketRow['subcalltype'],
                'zone'           => $ticketRow['zone'],
                'createdtime'    => $ticketRow['createdtime']
            ];
        }

        // ------------------------------
        // Prepare response
        // ------------------------------
       if (empty($ticketDetails)) {
    $response->setResult([
        'ticketStats'   => $ticketStats,
        'ticketDetails' => [],
        'message'       => 'No tickets found for the given filters'
    ]);
} else {
    $response->setResult([  
        'ticketStats'   => $ticketStats,
        'ticketDetails' => $ticketDetails
    ]);
}

        return $response;
    }
}
