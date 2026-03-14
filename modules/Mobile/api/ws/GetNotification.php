<?php
include_once dirname(__FILE__) . '/models/Alert.php';
include_once dirname(__FILE__) . '/models/Paging.php';

class Mobile_WS_GetNotification extends Mobile_WS_Controller {

    function process(Mobile_API_Request $request) {
        $smownerId = $request->get('useruniqid');

        // Validate useruniqid
        if (empty($smownerId)) {
            return $this->errorResponse("User ID is required.");
        }

        global $adb;

        // Get today's date
        $today = date('Y-m-d');

        // Create a SQL query to fetch activities with today's date
        $query = "SELECT a.*, se.*, ce.*
            FROM vtiger_activity a
            INNER JOIN vtiger_seactivityrel se ON a.activityid = se.activityid
            INNER JOIN vtiger_crmentity ce ON a.activityid = ce.crmid
            WHERE ce.smownerid = ? AND ce.deleted = 0 AND DATE(a.date_start) = ?";
        
        $result = $adb->pquery($query, [$smownerId, $today]);

        $records = [];
        while ($row = $adb->fetch_array($result)) {
            $records[] = [
                'activityid' => $row['activityid'],
                'title' => $row['subject'],
                'activitytype' => $row['activitytype'],
                'date_start' => $row['date_start'],
                'time_start' => $row['time_start'],
                'due_date' => $row['due_date'],
                'status' => $row['status'],
                'tickets_id' => $row['crmid'],
                'description' => $row['description'],
                'source' => $row['source'],
                'assigned_user_id' => '19x' . $row['smownerid'],
            ];
        }

         // Get total count of records
         $totalCountQuery = "SELECT COUNT(*) AS total_count
         FROM vtiger_activity a
         INNER JOIN vtiger_seactivityrel se ON a.activityid = se.activityid
         INNER JOIN vtiger_crmentity ce ON a.activityid = ce.crmid
         WHERE ce.smownerid = ? AND ce.deleted = 0 AND DATE(a.date_start) = ?";
     
     $countResult = $adb->pquery($totalCountQuery, [$smownerId, $today]);
     $totalCount = $adb->fetch_array($countResult)['total_count'];

        $response = new Mobile_API_Response();
        $response->setResult([
            'total_count' => $totalCount,
            'records' => $records,
            'records_per_page' => count($records),
            'moreRecords' => false, // Set this based on your pagination logic
            'page' => 1 // Adjust according to your pagination
        ]);
        $response->setApiSucessMessage('Successfully Fetched Data');
        return $response;
    }

    private function errorResponse($message) {
        $response = new Mobile_API_Response();
        $response->setError(400, $message);
        return $response;
    }
}
