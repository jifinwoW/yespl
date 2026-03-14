<?php

class Mobile_WS_CreateSTRequest extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;

        $response = new Mobile_API_Response();
        $current_user = $this->getActiveUser();

        $ticketID = $request->get('ticket_id');
        $requestDate = $request->get('req_date');
        $spareparts = json_decode($request->get('spareparts'), true);

        if (empty($ticketID) || empty($requestDate) || empty($spareparts) || !is_array($spareparts)) {
            $response->setError(1000, "Missing or invalid required parameters");
            return $response;
        }

        try {
            $TicketsModel = Vtiger_Record_Model::getInstanceById($ticketID, 'Tickets');
        } catch (Exception $e) {
            $response->setError(1002, 'Ticket Not Found.');
            return $response;
        }


        $contact_id = $TicketsModel->get('contact_id');
        if (empty($contact_id)) {
            $response->setError(1002, "No contact linked with the ticket");
            return $response;
        }

        $UserEmail = $current_user->email1;
        $UserMobile = $current_user->phone_mobile;

        if (empty($UserEmail) || empty($UserMobile)) {
            $response->setError(1003, "User email or mobile is missing");
            return $response;
        }

        $query = "SELECT engineerid FROM vtiger_engineer WHERE mobile_no = ? AND email = ? LIMIT 1";
        $result = $adb->pquery($query, [$UserMobile, $UserEmail]);

        if (!$result || $adb->num_rows($result) === 0) {
            $response->setError(1004, "Engineer not found for the current user");
            return $response;
        }

        $row = $adb->fetch_array($result);
        $engineerID = $row['engineerid'];

        $EngineerModel = Vtiger_Record_Model::getInstanceById($engineerID, 'Engineer');
        if (!$EngineerModel || !$EngineerModel->getId()) {
            $response->setError(1005, "Engineer record not found");
            return $response;
        }

        $ServiceCoordinatorID = $EngineerModel->get('servicecordintor_id');
        if (empty($ServiceCoordinatorID)) {
            $response->setError(1006, "Service coordinator not assigned to engineer");
            return $response;
        }

        $stockTransferModel = Vtiger_Record_Model::getCleanInstance('StockTransfer');
        $stockTransferModel->set('assigned_user_id', $current_user->id);
        $stockTransferModel->set('title', 'Spare Requested by ' . $current_user->user_name);
        $stockTransferModel->set('sparereqdate', $requestDate);
        $stockTransferModel->set('servicecordinator_id', $ServiceCoordinatorID);
        $stockTransferModel->set('parent_id', $ticketID);
        $stockTransferModel->set('contact_id', $contact_id);
        $stockTransferModel->set('st_status', 'Pending');
        $stockTransferModel->save();

        $StockTransferRecordId = $stockTransferModel->getId();
        if (empty($StockTransferRecordId)) {
            $response->setError(1007, "Failed to create stock transfer");
            return $response;
        }

        foreach ($spareparts as $spare) {
            if (empty($spare['spare_id']) || empty($spare['qty'])) {
                $response->setError(1008, "Each spare must have a valid ID and quantity");
                return $response;
            }

            $productModel = Vtiger_Record_Model::getInstanceById($spare['spare_id'], 'Products');
            if (!$productModel || !$productModel->getId() || !$productModel->get('productname')) {
                $response->setError(404, "Product with ID {$spare['spare_id']} not found");
                return $response;
            }
        }

        $StockTransferProducts = [];
        $i = 1;

        foreach ($spareparts as $product) {
            $spareModel = Vtiger_Record_Model::getCleanInstance('StockTransferProducts');
            $spareModel->set('assigned_user_id', $current_user->id);
            $spareModel->set('st_name', 'Transfer No.' . $i);
            $spareModel->set('st_qty', $product['qty']);
            $spareModel->set('stp_status', 'Pending');
            $spareModel->set('st_product_id', $product['spare_id']);
            $spareModel->set('st_tra_req_id', $StockTransferRecordId);
            $spareModel->save();

            $recordId = $spareModel->getId();
            if (!$recordId) {
                $response->setError(1009, "Failed to create spare part entry");
                return $response;
            }

            $StockTransferProducts[] = ['recordid' => $recordId];
            $i++;
        }

        $responseData = [
            'st_req_id' => $StockTransferRecordId,
            'spare_parts_list' => $StockTransferProducts
        ];

        $response->setApiSucessMessage('Successfully created Stock Transfer Request');
        $response->setResult($responseData);
        return $response;
    }
}
