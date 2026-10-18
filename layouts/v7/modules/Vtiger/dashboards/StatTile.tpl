{************************************************************************************
 * StatTile.tpl — KPI stat tile widget
 * Uses STANDARD vtiger widget structure so drag + remove + refresh all work
 * Same structure as KeyMetrics.tpl, History.tpl etc.
 *************************************************************************************}
<div class="dashboardWidgetHeader">
    {include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME}
</div>

<div class="dashboardWidgetContent stattile-content">
    {include file="dashboards/StatTileContents.tpl"|@vtemplate_path:$MODULE_NAME}
</div>

<div class="widgeticons dashBoardWidgetFooter">
    <div class="footerIcons pull-right">
        {include file="dashboards/DashboardFooterIcons.tpl"|@vtemplate_path:$MODULE_NAME}
    </div>
</div>
