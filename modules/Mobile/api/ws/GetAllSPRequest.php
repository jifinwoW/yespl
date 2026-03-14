<?php

class Mobile_WS_GetAllSPRequest extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;
        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $username = $current_user->column_fields['user_name'];

        $scquery = "SELECT servicecordinatorid FROM vtiger_servicecordinator WHERE sm_code = ? LIMIT 1";
        $SCresult = $adb->pquery($scquery, [$username]);

        if ($adb->num_rows($SCresult) == 0) {
            $response->setError(404, 'Service Coordinator not found');
            return $response;
        }

        while ($scrow = $adb->fetch_array($SCresult)) {
            $scid = $scrow['servicecordinatorid'];
        }

        $query = "SELECT st.stocktransferid, st.parent_id
                  FROM vtiger_stocktransfer st
                  INNER JOIN vtiger_crmentity ce ON ce.crmid = st.stocktransferid
                  WHERE ce.deleted = 0 AND st.servicecordinator_id = ?";
        $result = $adb->pquery($query, [$scid]);

        $data = [];

        while ($row = $adb->fetch_array($result)) {
            $stocktransferId = $row['stocktransferid'];
            $ticketId        = $row['parent_id'];

            // StockTransfer record
            $recordModel = Vtiger_Record_Model::getInstanceById($stocktransferId, 'StockTransfer');

            // Get Engineer ID from Ticket
            $engineerName = '';
            if ($ticketId) {
                $ticketRecord = Vtiger_Record_Model::getInstanceById($ticketId, 'Tickets'); // Tickets module
                $engineerId   = $ticketRecord->get('engineer_id'); // field in Tickets
                if ($engineerId) {
                    $engRecord    = Vtiger_Record_Model::getInstanceById($engineerId, 'Engineer');
                    $engineerName = $engRecord->get('engineer_name');
                }
            }

            // Base data
            $recordData = [
                'id'              => $stocktransferId,
                'ticket_id'       => $ticketId,
                'engineer'        => [
                    'eng_id'      => $engineerId,
                    'eng_name'    => $engineerName,
                ],
                'products'        => []
            ];

            // Related products
            $relationModel   = Vtiger_RelationListView_Model::getInstance($recordModel, 'StockTransferProducts');
            $pagingModel     = new Vtiger_Paging_Model();
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
