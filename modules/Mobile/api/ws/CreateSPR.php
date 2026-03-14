<?php

class Mobile_WS_CreateSPR extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;

        $response = new Mobile_API_Response();
        $current_user = $this->getActiveUser();

        $ticketID = $request->get('ticket_id');
        $spareparts = json_decode($request->get('spareparts'), true);
        $username = $current_user->column_fields['user_name'];

        if (empty($ticketID) || empty($spareparts) || !is_array($spareparts)) {
            $response->setError(1000, "Missing or invalid required parameters");
            return $response;
        }

        $sparepartIds = array_map(function($item) {
            return $item['spare_id'];
        }, $spareparts);

        $placeholders = implode(',', array_fill(0, count($sparepartIds), '?'));
        $params = array_merge([$username], $sparepartIds);

        // Fetch total assigned qty for each product (SUM of multiple assignment records)
        $recordQuery = $adb->pquery(
            "SELECT sb_product_id, SUM(sb_qty) AS total_assigned_qty, vtiger_engineer.engineerid AS engineerid 
             FROM vtiger_crmentity
             INNER JOIN vtiger_engineer ON vtiger_engineer.engineerid = vtiger_crmentity.crmid
             INNER JOIN vtiger_engineerstockbalance ON vtiger_engineer.engineerid = vtiger_engineerstockbalance.sb_engineer_id
             WHERE vtiger_engineer.engineer_code = ? 
             AND vtiger_engineerstockbalance.sb_product_id IN ($placeholders)
             AND vtiger_crmentity.deleted = 0
             GROUP BY sb_product_id",
            $params
        );

        $assignedProducts = [];
        $userRecordId = null;

        while ($row = $adb->fetch_array($recordQuery)) {
            $assignedProducts[$row['sb_product_id']] = (int)$row['total_assigned_qty'];
            $userRecordId = $row['engineerid'];  // Same for all rows
        }

        $insufficientStock = [];

        foreach ($spareparts as $product) {
            $productId = $product['spare_id'];
            $requestedQty = (int)$product['qty'];

            // If product is not assigned at all, treat as 0 stock
            $assignedQty = isset($assignedProducts[$productId]) ? $assignedProducts[$productId] : 0;

            // Fetch total issued qty from SparePartsReplacement
            $issuedResult = $adb->pquery(
                "SELECT SUM(spr_qty) AS total_issued_qty FROM vtiger_sparepartsreplacement
                 INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_sparepartsreplacement.sparepartsreplacementid
                 WHERE spr_engineer_id = ? AND spr_product_id = ? AND vtiger_crmentity.deleted = 0",
                [$userRecordId, $productId]
            );

            $issuedQty = (int)$adb->query_result($issuedResult, 0, 'total_issued_qty');
            $availableQty = $assignedQty - $issuedQty;

            if ($availableQty < $requestedQty) {
                $productName = Vtiger_Functions::getCRMRecordLabel($productId);
                $insufficientStock[] = [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'assigned_qty' => $assignedQty,
                    'requested_qty' => $requestedQty
                ];
            }
        }

        if (!empty($insufficientStock)) {
            $response->setError(103, 'Insufficient stock for some products.');
            $response->setResult(['insufficient_stock' => $insufficientStock]);
            return $response;
        }

        // Ticket existence check
        try {
            $TicketsModel = Vtiger_Record_Model::getInstanceById($ticketID, 'Tickets');
        } catch (Exception $e) {
            $response->setError(1002, 'Ticket Not Found.');
            return $response;
        }

        // All validations passed, create SPR records
        $i = 1;
        $StockTransferProducts = [];

        foreach ($spareparts as $product) {
            $spareModel = Vtiger_Record_Model::getCleanInstance('SparePartsReplacement');
            $spareModel->set('assigned_user_id', $current_user->id);
            $spareModel->set('spr_name', 'Spare Part No.' . $i);
            $spareModel->set('spr_qty', $product['qty']);
            $spareModel->set('spr_engineer_id', $userRecordId);
            $spareModel->set('spr_product_id', $product['spare_id']);
            $spareModel->set('spr_ticket_id', $ticketID);
            $spareModel->save();

            $recordId = $spareModel->getId();
            if (!$recordId) {
                $response->setError(1009, "Failed to create spare part entry");
                return $response;
            }

            $StockTransferProducts[] = ['recordid' => $recordId];
            $i++;
        }

        $responseData = ['records' => $StockTransferProducts];
        $response->setApiSucessMessage('Successfully created Stock Transfer Request');
        $response->setResult($responseData);
        return $response;
    }
}

?>
