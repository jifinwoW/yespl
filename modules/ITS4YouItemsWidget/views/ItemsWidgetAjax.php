<?php
/* * *******************************************************************************
 * The content of this file is subject to the ITS4You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */

class ITS4YouItemsWidget_ItemsWidgetAjax_View extends Vtiger_IndexAjax_View
{

    function __construct()
    {
        parent::__construct();
        $this->exposeMethod('show');
    }

    public function validateRequest(Vtiger_Request $request)
    {
        $request->validateReadAccess();
    }

    function preProcess(Vtiger_Request $request, $display = true): bool
    {
        return true;
    }

    function postProcess(Vtiger_Request $request): bool
    {
        return true;
    }

    function process(Vtiger_Request $request)
    {
        $mode = $request->get('mode');
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);

            return;
        }
    }

    public function checkPermission(Vtiger_Request $request): bool
    {
        $moduleName = $request->get('source_module');

        $recordPermission = Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $request->get('record'));

        if (!$recordPermission) {
            throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
        }

        return true;
    }

    public function isInventoryModule(Vtiger_Request $request): bool
    {
        return is_subclass_of($request->get('source_module') . '_Module_Model', 'Inventory_Module_Model');
    }

    /**
     * Function to show the recently modified or active records for the given module
     *
     * @throws AppException
     */
    function show(Vtiger_Request $request)
    {
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();

        $this->checkPermission($request);

        if (!$this->isInventoryModule($request)) {
            return false;
        }

        $recordModel = Vtiger_Record_Model::getInstanceById($request->get('record'));
        $products = $recordModel->getProducts();

        $viewer->assign('PRODUCTS', $products);
        $viewer->assign('MODULE_NAME', $request->get('source_module'));

        echo $viewer->view('ItemsWidget.tpl', $moduleName, true);
    }
}