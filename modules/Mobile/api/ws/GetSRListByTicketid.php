<?php

include_once dirname(__FILE__) . '/models/Alert.php';
include_once dirname(__FILE__) . '/models/Paging.php';

class Mobile_WS_GetSRListByTicketid extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        $parentId = $request->get('parent_id'); // Get the parent_id from the request
        $smownerId = $request->get('useruniqid'); // Get the assigned user ID

        // Validate parent_id
        if (empty($parentId)) {
            return $this->errorResponse("parent_id is required.");
        }

        global $adb;

        // Create a SQL query to fetch stock transfers with an inner join on vtiger_crmentity
        $query = "SELECT st.*, ce.*
            FROM vtiger_stocktransfer st
            INNER JOIN vtiger_crmentity ce ON st.stocktransferid = ce.crmid
            WHERE st.parent_id = ? AND ce.smownerid = ? AND ce.deleted = 0";

        $result = $adb->pquery($query, [$parentId, $smownerId]);

        $records = [];
        while ($row = $adb->fetch_array($result)) {

            $stockDetailsQuery = "SELECT stp.* FROM vtiger_stocktransferproducts stp
             INNER JOIN vtiger_crmentity ce ON stp.stocktransferproductsid  = ce.crmid
             WHERE stp.st_tra_req_id = ? AND ce.deleted = 0";

            $strequestID = $row['stocktransferid'];
            $stockDetailResult = $adb->pquery($stockDetailsQuery, [$strequestID]);
            $sprlist = [];
            while ($SDrow = $adb->fetch_array($stockDetailResult)) {

                $productID = $SDrow['st_product_id'];
                // print_r($SDrow);exit;
                $recordModel = Vtiger_Record_Model::getInstanceById($productID, 'Products');

                $sprlist[] = [
                    'spare_name' => $recordModel->get('productname'),
                    'spare_qty' => $SDrow['st_qty'],
                    'status' => $SDrow['stp_status']
                ];

            }


            $records[] = [
                'sparereqdate' => $row['sparereqdate'],
                'status' => $row['st_status'],
                'description' => $row['description'],
                'servicecordinator_id' => '41x' . $row['servicecordinator_id'],
                'parent_id' => $row['parent_id'],
                'stocktransferid' => '43x' . $row['stocktransferid'], // Modify as needed
                'assigned_user_id' => '19x' . $row['smownerid'],
                'spare_details' => $sprlist
            ];
        }

        $response = new Mobile_API_Response();
        $response->setResult([
            'records' => $records,
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
