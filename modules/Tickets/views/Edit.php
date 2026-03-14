<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class Tickets_Edit_View extends Vtiger_Edit_View {

    public function process(Vtiger_Request $request) {
        $moduleName = $request->getModule();
        $recordId = $request->get('sourceRecord');

        if ($recordId) {
            $helpdeskRecord = CRMEntity::getInstance('HelpDesk');
            $helpdeskRecord->retrieve_entity_info($recordId, 'HelpDesk');
            
            // Set values from the Helpdesk record
            $boid = $helpdeskRecord->column_fields['boid']; 
            $sapcode = $helpdeskRecord->column_fields['sapcode'];
			$custcode = $helpdeskRecord->column_fields['custcode']; 
            $depocode = $helpdeskRecord->column_fields['depocode'];
			$zone = $helpdeskRecord->column_fields['zone']; 
            $serviceengineer = $helpdeskRecord->column_fields['serviceengineer'];
			$typeofmc = $helpdeskRecord->column_fields['typeofmc']; 
            $model = $helpdeskRecord->column_fields['model'];
			$serialno = $helpdeskRecord->column_fields['serialno']; 
            $doi = $helpdeskRecord->column_fields['doi'];
			$vendor_id = $helpdeskRecord->column_fields['vendor_id']; 
            $service_location = $helpdeskRecord->column_fields['service_location'];
			$connectivity = $helpdeskRecord->column_fields['connectivity']; 
            $device_details = $helpdeskRecord->column_fields['device_details'];
			$gyro_type = $helpdeskRecord->column_fields['gyro_type']; 
            $gyro_model = $helpdeskRecord->column_fields['gyro_model'];
			$gyro_serialno = $helpdeskRecord->column_fields['gyro_serialno']; 
            $ups_details = $helpdeskRecord->column_fields['ups_details'];
			$ups_serialno = $helpdeskRecord->column_fields['ups_serialno']; 
            $warranty_month = $helpdeskRecord->column_fields['warranty_month'];
			$warranty_start_date = $helpdeskRecord->column_fields['warranty_start_date']; 
            $warranty_end_date = $helpdeskRecord->column_fields['warranty_end_date'];
			$engineer_id = $helpdeskRecord->column_fields['engineer_id'];
			$contact_mobileno = $helpdeskRecord->column_fields['contact_mobileno'];
			$address = $helpdeskRecord->column_fields['address']; 
            $city = $helpdeskRecord->column_fields['city'];
			$state = $helpdeskRecord->column_fields['state'];
			$pincode = $helpdeskRecord->column_fields['pincode'];
			$parent_id = $helpdeskRecord->column_fields['parent_id'];
			$location_type = $helpdeskRecord->column_fields['location_type'];

            // Create a new Ticket instance
            $ticket =  CRMEntity::getInstance('Tickets');
            $ticket->column_fields['boid'] = $boid;
            $ticket->column_fields['sapcode'] = $sapcode; 
			$ticket->column_fields['custcode'] = $custcode;
            $ticket->column_fields['depocode'] = $depocode; 
			$ticket->column_fields['zone'] = $zone;
            $ticket->column_fields['serviceengineer'] = $serviceengineer; 
			$ticket->column_fields['typeofmc'] = $typeofmc;
            $ticket->column_fields['model'] = $model; 
			$ticket->column_fields['serialno'] = $serialno;
            $ticket->column_fields['doi'] = $doi; 
			$ticket->column_fields['vendor_id'] = $vendor_id;
            $ticket->column_fields['service_location'] = $service_location; 
			$ticket->column_fields['connectivity'] = $connectivity;
            $ticket->column_fields['device_details'] = $device_details; 
			$ticket->column_fields['gyro_type'] = $gyro_type;
            $ticket->column_fields['gyro_model'] = $gyro_model; 
			$ticket->column_fields['gyro_serialno'] = $gyro_serialno;
            $ticket->column_fields['ups_details'] = $ups_details; 
			$ticket->column_fields['ups_serialno'] = $ups_serialno;
            $ticket->column_fields['warranty_month'] = $warranty_month;
			$ticket->column_fields['warranty_start_date'] = $warranty_start_date;
            $ticket->column_fields['warranty_end_date'] = $warranty_end_date;
			$ticket->column_fields['engineer_id'] = $engineer_id;
			$ticket->column_fields['contact_mobileno'] = $contact_mobileno;
			$ticket->column_fields['address'] = $address;
            $ticket->column_fields['city'] = $city;
			$ticket->column_fields['state'] = $state;
			$ticket->column_fields['pincode'] = $pincode;
			$ticket->column_fields['parent_id'] = $parent_id;
			$ticket->column_fields['location_type'] = $location_type; 

            // Ensure the request can carry these values
            $request->set('boid', $ticket->column_fields['boid']);
            $request->set('sapcode', $ticket->column_fields['sapcode']);
			$request->set('custcode', $ticket->column_fields['custcode']);
            $request->set('depocode', $ticket->column_fields['depocode']);
			$request->set('zone', $ticket->column_fields['zone']);
            $request->set('servicecoordintor', $ticket->column_fields['serviceengineer']);
			$request->set('typeofmc', $ticket->column_fields['typeofmc']);
            $request->set('model', $ticket->column_fields['model']);
			$request->set('serialno', $ticket->column_fields['serialno']);
            $request->set('doi', $ticket->column_fields['doi']);
			$request->set('vendor_id', $ticket->column_fields['vendor_id']);
            $request->set('service_location', $ticket->column_fields['service_location']);
			$request->set('gyro_type', $ticket->column_fields['gyro_type']);
            $request->set('gyro_model', $ticket->column_fields['gyro_model']);
			$request->set('gyro_serialno', $ticket->column_fields['gyro_serialno']);
            $request->set('ups_details', $ticket->column_fields['ups_details']);
			$request->set('ups_serialno', $ticket->column_fields['ups_serialno']);
            $request->set('warranty_month', $ticket->column_fields['warranty_month']);
			$request->set('warranty_start_date', $ticket->column_fields['warranty_start_date']);
            $request->set('warranty_end_date', $ticket->column_fields['warranty_end_date']);
			$request->set('engineer_id', $ticket->column_fields['engineer_id']);
			$request->set('contact_mobileno', $ticket->column_fields['contact_mobileno']);
			$request->set('address', $ticket->column_fields['address']);
            $request->set('city', $ticket->column_fields['city']);
			$request->set('state', $ticket->column_fields['state']);
			$request->set('pincode', $ticket->column_fields['pincode']);
			$request->set('account_id', $ticket->column_fields['parent_id']);
			$request->set('location_type', $ticket->column_fields['location_type']);
	
        }

        parent::process($request);
    }
}
