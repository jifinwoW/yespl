<?php

Class Engineer_Edit_View extends Vtiger_Edit_View {
	public function getHeaderCss(Vtiger_Request $request) {
        $headerCssInstances = parent::getHeaderCss($request);

        $cssFileNames = array(
            "~layouts/" . Vtiger_Viewer::getDefaultLayoutName() . "/modules/Users/build/css/intlTelInput.css",
        );
        $cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
        $headerCssInstances = array_merge($headerCssInstances, $cssInstances);

        return $headerCssInstances;
    }

    public function getHeaderScripts(Vtiger_Request $request) {
    $headerScriptInstances = parent::getHeaderScripts($request);

    $jsFileNames = array(
        'modules.Engineer.resources.Edit',
    );

    $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);

    return array_merge($headerScriptInstances, $jsScriptInstances);
}
}
