<?php
/*+***********************************************************************************
 * Custom Widget: StatTile
 * A single KPI stat tile widget — clean professional card design.
 * Each tile type is a separate DASHBOARDWIDGET entry in vtiger_links so users
 * can add/remove each tile independently from the "Add Widget" dialog.
 *
 * Tile types:
 *   tiletype=engineers        → Total Engineers
 *   tiletype=coordinators     → Total Service Coordinators
 *   tiletype=amcs             → Total AMCs (ServiceContracts)
 *   tiletype=open_tickets     → Open Tickets
 *   tiletype=total_tickets    → Total Tickets
 *************************************************************************************/

class Vtiger_StatTile_Dashboard extends Vtiger_IndexAjax_View {

    /**
     * Tile definitions:
     *   label       - card title (top-left)
     *   subtitle    - bottom trend/info text
     *   icon        - FontAwesome class for bottom-left icon
     *   accentClass - CSS accent variant (stattile-card--accent for dark bg, empty for white)
     *   module      - vtiger module name (for permissions check)
     *   link        - URL when tile is clicked
     *   sql         - COUNT query
     *   params      - pquery params array
     */
    protected function getTileDefinitions() {
        return array(
            'engineers' => array(
                'label'       => 'Total Engineers',
                'subtitle'    => 'All active engineers',
                'icon'        => 'fa-user-circle',
                'module'      => 'Engineer',
                'link'        => 'index.php?module=Engineer&view=List',
                'sql'         => 'SELECT COUNT(*) as cnt FROM vtiger_engineer e
                                  INNER JOIN vtiger_crmentity ce ON ce.crmid = e.engineerid AND ce.deleted = 0',
                'params'      => array(),
            ),
            'coordinators' => array(
                'label'       => 'Service Coordinators',
                'subtitle'    => 'All service coordinators',
                'icon'        => 'fa-id-badge',
                'module'      => 'ServiceCordinator',
                'link'        => 'index.php?module=ServiceCordinator&view=List',
                'sql'         => 'SELECT COUNT(*) as cnt FROM vtiger_servicecordinator sc
                                  INNER JOIN vtiger_crmentity ce ON ce.crmid = sc.servicecordinatorid AND ce.deleted = 0',
                'params'      => array(),
            ),
            'amcs' => array(
                'label'       => 'Total AMCs',
                'subtitle'    => 'Service contracts active',
                'icon'        => 'fa-file-text',
                'module'      => 'ServiceContracts',
                'link'        => 'index.php?module=ServiceContracts&view=List',
                'sql'         => 'SELECT COUNT(*) as cnt FROM vtiger_servicecontracts c
                                  INNER JOIN vtiger_crmentity ce ON ce.crmid = c.servicecontractsid AND ce.deleted = 0',
                'params'      => array(),
            ),
            'open_tickets' => array(
                'label'       => 'Open Tickets',
                'subtitle'    => 'Pending resolution',
                'icon'        => 'fa-ticket',
                'module'      => 'HelpDesk',
                'link'        => 'index.php?module=HelpDesk&view=List',
                'sql'         => "SELECT COUNT(*) as cnt FROM vtiger_helpdesk h
                                  INNER JOIN vtiger_crmentity ce ON ce.crmid = h.ticketid AND ce.deleted = 0
                                  WHERE h.ticketstatus NOT IN ('Closed', 'Resolved')",
                'params'      => array(),
            ),
            'total_tickets' => array(
                'label'       => 'Total Tickets',
                'subtitle'    => 'All support tickets',
                'icon'        => 'fa-tags',
                'module'      => 'HelpDesk',
                'link'        => 'index.php?module=HelpDesk&view=List',
                'sql'         => 'SELECT COUNT(*) as cnt FROM vtiger_helpdesk h
                                  INNER JOIN vtiger_crmentity ce ON ce.crmid = h.ticketid AND ce.deleted = 0',
                'params'      => array(),
            ),
        );
    }

    public function process(Vtiger_Request $request) {
        $currentUser = Users_Record_Model::getCurrentUserModel();
        $viewer      = $this->getViewer($request);
        $moduleName  = $request->getModule();
        $linkId      = $request->get('linkid');

        $widget   = Vtiger_Widget_Model::getInstance($linkId, $currentUser->getId());
        $tileType = $request->get('tiletype');
        $tileData = $this->getTileData($tileType);

        $viewer->assign('WIDGET',      $widget);
        $viewer->assign('MODULE_NAME', $moduleName);
        $viewer->assign('TILE',        $tileData);

        $content = $request->get('content');
        if (!empty($content)) {
            $viewer->view('dashboards/StatTileContents.tpl', $moduleName);
        } else {
            $viewer->view('dashboards/StatTile.tpl', $moduleName);
        }
    }

    protected function getTileData($tileType) {
        $defs = $this->getTileDefinitions();

        if (empty($tileType) || !isset($defs[$tileType])) {
            return array(
                'label'       => 'Stat Tile',
                'subtitle'    => 'No data',
                'icon'        => 'fa-bar-chart',
                'accentClass' => '',
                'link'        => 'index.php?module=Home&view=DashBoard',
                'count'       => 0,
            );
        }

        $def    = $defs[$tileType];
        $db     = PearDatabase::getInstance();
        $result = $db->pquery($def['sql'], $def['params']);
        $row    = $db->fetch_array($result);

        $def['count'] = isset($row['cnt']) ? (int)$row['cnt'] : 0;
        return $def;
    }
}
