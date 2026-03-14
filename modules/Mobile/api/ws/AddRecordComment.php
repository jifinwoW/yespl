<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once dirname(__FILE__) . '/SaveRecord.php';

class Mobile_WS_AddRecordComment extends Mobile_WS_SaveRecord {
    
    function process(Mobile_API_Request $request) {
        $values = Zend_Json::decode($request->get('values', '', false));
        
        $user = $this->getActiveUser();
        
        $targetModule = 'ModComments';
        
        // Validate that the ticket ID is provided
        if (empty($values['ticket_id'])) {
            return $this->errorResponse("ticket_id is required.");
        }

        // Set the related ticket ID
        $ticketId = $values['ticket_id'];
        $values['related_to'] = sprintf('%sx%s', Mobile_WS_Utils::getEntityModuleWSId('Tickets'), $ticketId);
        
        $response = false;
        if (vtlib_isModuleActive($targetModule)) {
            $request->set('module', $targetModule);
            $values['assigned_user_id'] = sprintf('%sx%s', Mobile_WS_Utils::getEntityModuleWSId('Users'), $user->id);
            $request->set('values', Vtiger_Functions::jsonEncode($values));
            $response = parent::process($request);
        }
        
        return $response;
    }

    private function errorResponse($message) {
        $response = new Mobile_API_Response();
        $response->setError(400, $message);
        return $response;
    }
}
