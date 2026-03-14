<?php
/*********************************************************************************
 * The content of this file is subject to the ITS4YouItemsWidget license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 ********************************************************************************/

class ITS4YouItemsWidget
{
    public $db, $log;

    // Cache to speed up describe information store
    public $moduleName = 'ITS4YouItemsWidget';

    /**
     * [module, type, label, url, icon, sequence, handlerInfo]
     * @return array
     */
    public $registerCustomLinks = array(
        ['ITS4YouItemsWidget', 'HEADERSCRIPT', 'ITS4YouItemsWidgetJs', 'layouts/$LAYOUT$/modules/ITS4YouItemsWidget/resources/ItemsWidget.js']
    );

    /**
     * name, handler, frequency, module, sequence, description
     */

    public function __construct()
    {
        global $log;

        $this->db = PearDatabase::getInstance();
        $this->log = $log;
    }

    /**
     * @throws Exception
     */
    public function vtlib_handler($moduleName, $eventType)
    {
        switch ($eventType) {
            case 'module.preuninstall':
            case 'module.disabled':
            case 'module.preupdate':
                $this->deleteCustomLinks();
                break;
            case 'module.postinstall':
            case 'module.enabled':
            case 'module.postupdate':
                $this->addCustomLinks();
                break;
        }
    }

    /**
     * @throws Exception
     */
    public function deleteCustomLinks()
    {
        $this->updateCustomLinks();
    }

    /**
     * @param bool $register
     */
    public function updateCustomLinks($register = true)
    {
        foreach ($this->registerCustomLinks as $customLink) {
            list($moduleName, $type, $label, $url, $icon, $sequence, $handler) = array_pad($customLink, 7, null);
            $module = Vtiger_Module::getInstance($moduleName);
            $url = str_replace('$LAYOUT$', Vtiger_Viewer::getDefaultLayoutName(), $url);

            if ($module) {
                $module->deleteLink($type, $label);

                if ($register) {
                    $module->addLink($type, $label, $url, $icon, $sequence, $handler);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    public function addCustomLinks()
    {
        $this->updateCustomLinks();
    }
}