<?php
include_once dirname(__FILE__) . '/models/Alert.php';
include_once dirname(__FILE__) . '/models/Paging.php';

class Mobile_WS_GetFollowUpListByTicketid extends Mobile_WS_Controller
{

    function process(Mobile_API_Request $request)
    {
        $parentId = $request->get('parent_id'); // Get the parent_id from the request
        $smownerId = $request->get('useruniqid');

        // Validate parent_id
        if (empty($parentId)) {
            return $this->errorResponse("parent_id is required.");
        }

        global $adb;

        // Create a SQL query to fetch stock transfers where parent_id matches
        if (!empty($smownerId)) {
            $query = "SELECT a.*, se.*, ce.*
            FROM vtiger_activity a
            INNER JOIN vtiger_seactivityrel se ON a.activityid = se.activityid
            INNER JOIN vtiger_crmentity ce ON a.activityid = ce.crmid
            WHERE se.crmid = ? AND ce.smownerid = ? AND ce.deleted = 0";
            $result = $adb->pquery($query, [$parentId, $smownerId]);
        }else{
             $query = "SELECT a.*, se.*, ce.*
            FROM vtiger_activity a
            INNER JOIN vtiger_seactivityrel se ON a.activityid = se.activityid
            INNER JOIN vtiger_crmentity ce ON a.activityid = ce.crmid
            WHERE se.crmid = ? AND ce.deleted = 0";
            $result = $adb->pquery($query, [$parentId]);
        }


        $records = [];
        while ($row = $adb->fetch_array($result)) {
            $records[] = [
                'activityid' => $row['activityid'],
                'title' => $row['subject'],
                'activitytype' => $row['activitytype'],
                'date_start' => $row['date_start'],
                'time_start' => $row['time_start'],
                'due_date' => $row['due_date'],
                'status' => $row['eventstatus'],
                'tickets_id' => $row['crmid'],
                'description' => $row['description'],
                'source' => $row['source'],
                'assinged_user_id' => '19x' . $row['smownerid'],
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
