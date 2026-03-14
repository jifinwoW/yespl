<?php

class Mobile_WS_GetZoneValues extends Mobile_WS_Controller
{
    public function process(Mobile_API_Request $request)
    {
        global $adb;
        $current_user = $this->getActiveUser();
        $response = new Mobile_API_Response();

        $fieldName = 'zone';
        $fieldResult = Vtiger_Util_Helper::getPickListValues($fieldName);
        $options = [];
        foreach ($fieldResult as $id => $label) {
            $options[] = [
                'id' => $id,
                'label' => $label
            ];
        }

        $response->setResult($options);

        return $response;
    }
}
