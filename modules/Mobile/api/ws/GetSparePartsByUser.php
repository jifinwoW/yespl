<?php

class Mobile_WS_GetSparePartsByUser extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb, $current_user;

        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $username = $current_user->column_fields['user_name'];
        $roleId = $current_user->column_fields['roleid'];

        // Get Role Name
        $roleQuery = $adb->pquery("SELECT rolename FROM vtiger_role WHERE roleid = ?", array($roleId));
        $roleName = $adb->query_result($roleQuery, 0, 'rolename');

        if ($roleName == "Engineer") {
            $moduleToFetch = "engineer";
            $fieldToMatch = "engineer_code";
            $relatedField = "sb_engineer_id";
            $get_module_table = "engineerstockbalance";
        } else if ($roleName == "Service Cordinator / Area Manager") {
            $moduleToFetch = "servicecordinator";
            $fieldToMatch = "sm_code";
            $relatedField = "sb_sc_id";
            $get_module_table = "scstockbalance";
        } else {
            $response->setError(100, 'User role is not Engineer or Service Cordinator');
            return $response;
        }

        // Fetch Engineer/ServiceCordinator Record ID for this user
        $recordQuery = $adb->pquery(
            "SELECT crmid FROM vtiger_crmentity
             INNER JOIN vtiger_$moduleToFetch ON vtiger_$moduleToFetch.${moduleToFetch}id = vtiger_crmentity.crmid
             WHERE vtiger_$moduleToFetch.$fieldToMatch = ? AND vtiger_crmentity.deleted = 0",
            array($username)
        );

        if ($adb->num_rows($recordQuery) == 0) {
            $response->setError(101, 'No Engineer/ServiceCordinator Record Found');
            return $response;
        }

        $userRecordId = $adb->query_result($recordQuery, 0, 'crmid');

        // Fetch SparePartsAssignment linked to this record and GROUP BY product
        if( $moduleToFetch == "engineer"){

        
        $assignmentQuery = $adb->pquery(
    "SELECT spa.sb_product_id, 
            SUM(spa.sb_qty) AS total_qty, 
            sp.productname
     FROM vtiger_engineerstockbalance spa
     INNER JOIN vtiger_crmentity ce 
         ON ce.crmid = spa.engineerstockbalanceid 
        AND ce.deleted = 0
     INNER JOIN vtiger_products sp 
         ON sp.productid = spa.sb_product_id
     WHERE spa.$relatedField = ?
     GROUP BY spa.sb_product_id
     HAVING total_qty > 0",
    array($userRecordId)
);


         $assignedParts = [];
        while ($row = $adb->fetch_array($assignmentQuery)) {
            $assignedParts[] = [
                'sparepartid' => $row['sb_product_id'],
                'productname' => $row['productname'],
                'total_quantity' => (int)$row['total_qty']
            ];
        }

    }else{
        $assignmentQuery = $adb->pquery(
            "SELECT spa.scsb_product_id, SUM(spa.scsb_qty) AS total_qty, sp.productname
             FROM vtiger_scstockbalance spa
             INNER JOIN vtiger_crmentity ce ON ce.crmid = spa.scstockbalanceid AND ce.deleted = 0
             INNER JOIN vtiger_products sp ON sp.productid = spa.scsb_product_id
             WHERE spa.$relatedField = ?
             GROUP BY spa.scsb_product_id",
            array($userRecordId)
        );

         $assignedParts = [];
        while ($row = $adb->fetch_array($assignmentQuery)) {
            $assignedParts[] = [
                'sparepartid' => $row['scsb_product_id'],
                'productname' => $row['productname'],
                'total_quantity' => (int)$row['total_qty']
            ];
        }
    }

        $response->setApiSucessMessage('Successfully Fetched Spare Parts');
        $response->setResult($assignedParts);
        return $response;
    }
}
?>
