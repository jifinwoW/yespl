<?php

class Mobile_WS_GetSPRequestByTicket extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;
        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $ticketId = $request->get('ticket_id');
        if (empty($ticketId)) {
            $response->setError(1400, 'Missing parameter: ticket_id');
            return $response;
        }

        // Step 1: get StockTransfer IDs for this Ticket
        $query = "SELECT st.stocktransferid
                  FROM vtiger_stocktransfer st
                  INNER JOIN vtiger_crmentity ce ON ce.crmid = st.stocktransferid
                  WHERE ce.deleted = 0 AND st.parent_id = ?";
        $result = $adb->pquery($query, [$ticketId]);

        $data = [];

        while ($row = $adb->fetch_array($result)) {
            $stocktransferId = $row['stocktransferid'];

            // Step 2: load record via model
            $recordModel = Vtiger_Record_Model::getInstanceById($stocktransferId, 'StockTransfer');
            $contactId = $recordModel->get('contact_id');
            $customerName = '';
            if ($contactId) {
                $contactRecord = Vtiger_Record_Model::getInstanceById($contactId, 'Contacts');
                $customerName = $contactRecord->get('firstname') . ' ' . $contactRecord->get('lastname');
            }

            $recordData = [
                'id'            => $stocktransferId,
                'title'         => $recordModel->get('title'),
                'requested_date' => $recordModel->get('sparereqdate'),
                'status'        => $recordModel->get('st_status'),
                'customer_name' => $customerName,
                'created_time'  => $recordModel->get('createdtime'),
                'modified_time' => $recordModel->get('modifiedtime'),
                'products'      => []
            ];

            // Step 3: related products
            $relationModel = Vtiger_RelationListView_Model::getInstance($recordModel, 'StockTransferProducts');
            $pagingModel = new Vtiger_Paging_Model();
            $relatedProducts = $relationModel->getEntries($pagingModel);

            foreach ($relatedProducts as $prodModel) {
    $prodRecord = Vtiger_Record_Model::getInstanceById($prodModel->getId(), 'StockTransferProducts');

    $productId   = $prodRecord->get('st_product_id');
    $productName = '';
    if ($productId) {
        $productRecord = Vtiger_Record_Model::getInstanceById($productId, 'Products');
        $productName   = $productRecord->get('productname');
    }

    $recordData['products'][] = [
        'id'           => $prodRecord->getId(),
        'productid'    => $productId,
        'product_name' => $productName,
        'quantity'     => $prodRecord->get('st_qty'),
        'status'       => $prodRecord->get('stp_status')
    ];
}


            $data[] = $recordData;
        }

        $response->setResult(['spare_part_requests' => $data]);
        return $response;
    }
}
