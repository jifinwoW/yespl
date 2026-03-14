<?php

class Mobile_WS_GetSpareParts extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;
        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $products = [];

        $query = "SELECT productid, productname FROM vtiger_products
                  INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_products.productid
                  WHERE vtiger_crmentity.deleted = 0";

        $result = $adb->pquery($query, []);

        while ($row = $adb->fetch_array($result)) {
            $products[] = [
                'id' => $row['productid'],
                'name' => $row['productname']
            ];
        }

        $response->setApiSucessMessage('Successfully Fetched Data');
        $response->setResult($products);
        return $response;
    }
}
