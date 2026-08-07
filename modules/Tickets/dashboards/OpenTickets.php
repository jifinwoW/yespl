<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Tickets_OpenTickets_Dashboard extends Vtiger_IndexAjax_View {
    
	function getSearchParams($name) {
		$listSearchParams = array(
			array(
				array(
					'tickets_status',
					'e',
					'Hold,Pending'
				),
				array(
					'assigned_user_id',
					'c',
					$name
				)
			)
		);

		return '&search_params=' . json_encode($listSearchParams);
	}

	public function process(Vtiger_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$linkId = $request->get('linkid');

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$data = $moduleModel->getOpenTickets();
        $listViewUrl = 'index.php?module=' . $moduleName . '&view=List';
        for($i = 0;$i<php7_count($data);$i++){
            $data[$i]["links"] = $listViewUrl.$this->getSearchParams($data[$i]["name"])."&nolistcache=1&searchtype=BasicSearch&query=true";
        }

		$widget = Vtiger_Widget_Model::getInstance($linkId, $currentUser->getId());

		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('DATA', $data);
        
		//Include special script and css needed for this widget
		$viewer->assign('CURRENTUSER', $currentUser);

		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/DashBoardWidgetContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/OpenTickets.tpl', $moduleName);
		}
	}
}
