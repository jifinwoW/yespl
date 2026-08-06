<?php
require_once 'modules/PickList/DependentPickListUtils.php';

class Mobile_WS_GetDependentPickListValues extends Mobile_WS_Controller {
    function process(Mobile_API_Request $request) {
        $response = new Mobile_API_Response();
        $fieldname = $request->get('fieldname');
        $fieldvalue = $request->get('fieldvalue');
        $dependentfieldname = $request->get('dependentfieldname');
        $module = $request->get('module');
        
        $picklistvaluesmap = Vtiger_DependencyPicklist::getDependentValuesBySourceValue($module, $fieldname, $fieldvalue, $dependentfieldname);
        $pickList = [];
        foreach ($picklistvaluesmap as $targetValue) {
            array_push($pickList, array($dependentfieldname => decode_html($targetValue)));
        }
        $fieldListPicklist[$dependentfieldname] = $pickList;
        $response->setApiSucessMessage('Successfully Fetched Data');
        $response->setResult($fieldListPicklist);
        return $response;
    }
}
