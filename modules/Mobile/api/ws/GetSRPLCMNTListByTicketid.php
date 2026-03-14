<?php

include_once dirname(__FILE__) . '/models/Alert.php';
include_once dirname(__FILE__) . '/models/Paging.php';

class Mobile_WS_GetSRPLCMNTListByTicketid extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        $ticketId = $request->get('ticket_id'); // Get the parent_id from the request

        // Validate parent_id
        if (empty($ticketId)) {
            return $this->errorResponse("Ticket id is required.");
        }

        global $adb;

        // Create a SQL query to fetch stock transfers with an inner join on vtiger_crmentity
        $query = "SELECT st.*, ce.*
            FROM vtiger_sparepartsreplacement st
            INNER JOIN vtiger_crmentity ce ON st.sparepartsreplacementid  = ce.crmid
            WHERE st.spr_ticket_id = ? AND ce.deleted = 0";

        $result = $adb->pquery($query, [$ticketId]);

        $records = [];
        while ($row = $adb->fetch_array($result)) {
            $productID = $row['spr_product_id'];
            $engineerId = $row['spr_engineer_id'];
            $productModel = Vtiger_Record_Model::getInstanceById($productID, 'Products');
            $engineerModel = Vtiger_Record_Model::getInstanceById($engineerId, 'Engineer');
             // Convert UTC to IST
    $createdUTC = new DateTime($row['createdtime'], new DateTimeZone('UTC'));
    $createdUTC->setTimezone(new DateTimeZone('Asia/Kolkata'));

    $records[] = [
        'id'           => $row['sparepartsreplacementid'],
        'product_name' => $productModel->get('productname'),
        'qty'          => $row['spr_qty'],
        'date'         => $createdUTC->format('Y-m-d'),     // e.g. 2025-08-13
        'time'         => $createdUTC->format('h:i A'),     // e.g. 08:45 PM
    ];
        }

        $TicketModel = Vtiger_Record_Model::getInstanceById($ticketId, 'Tickets');



        $response = new Mobile_API_Response();
        $response->setResult([
            'ticket_id' => $TicketModel->getId(),
            'status' => $TicketModel->get('tickets_status'),
            'spare_part_replacement_records' => $records,
            'records_per_page' => count($records),
            'moreRecords' => false, // Set this based on your pagination logic
            'page' => 1 // Adjust according to your pagination
        ]);
        $response->setApiSucessMessage('Successfully Fetched Data');
        return $response;
    }

    private function errorResponse($message)
    {
        $response = new Mobile_API_Response();
        $response->setError(400, $message);
        return $response;
    }
}
