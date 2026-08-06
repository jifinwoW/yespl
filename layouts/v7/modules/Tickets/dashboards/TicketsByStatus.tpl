{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is: vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}

<div class="dashboardWidgetHeader">
	{include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME SETTING_EXIST=true}
</div>

<div class="dashboardWidgetContent" style="width: 100%; position: relative;">
{if php7_count($DATA) gt 0}
	{assign var=WIDGET_UNIQ value=$WIDGET->get('id')|default:$WIDGET->get('linkid')|default:105}
	{assign var=CHART_DATA value=Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}
	<div class="vtChartContainer" style="padding: 6px 10px; width: 100%; height: 210px; position: relative; box-sizing: border-box; overflow: hidden;">
		<canvas id="chartjs_tickets_custom_{$WIDGET_UNIQ}" data-chart="{$CHART_DATA}" style="width: 100%; height: 100%;"></canvas>
	</div>
	<script type="text/javascript">
	(function() {
		var targetId = 'chartjs_tickets_custom_{$WIDGET_UNIQ}';
		{literal}
		function renderChart() {
			var canvas = document.getElementById(targetId);
			if (!canvas) return;

			if (typeof window.Chart === 'undefined') {
				setTimeout(renderChart, 80);
				return;
			}

			var rawData = [];
			try {
				rawData = JSON.parse(canvas.getAttribute('data-chart') || '[]');
			} catch(e) {
				return;
			}
			if (!rawData || !rawData.length) return;

			var labels = [], counts = [], urls = [];
			for (var i = 0; i < rawData.length; i++) {
				counts.push(parseInt(rawData[i][0], 10));
				labels.push(rawData[i][1]);
				urls.push(rawData[i]['links'] || '');
			}

			var existing = window.Chart.getChart(canvas);
			if (existing) {
				existing.destroy();
			}

			var palette = ['#6366f1','#22d3ee','#f59e0b','#10b981','#ef4444','#8b5cf6','#f43f5e','#0ea5e9'];
			var chart = new window.Chart(canvas.getContext('2d'), {
				type: 'doughnut',
				data: {
					labels: labels,
					datasets: [{
						data: counts,
						backgroundColor: palette.slice(0, counts.length),
						borderColor: '#ffffff',
						borderWidth: 2,
						hoverOffset: 6
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					cutout: '60%',
					layout: { padding: { bottom: 8, top: 4 } },
					plugins: {
						legend: {
							position: 'bottom',
							labels: {
								padding: 10,
								font: { size: 11 },
								usePointStyle: true
							}
						},
						tooltip: {
							callbacks: {
								label: function(context) {
									var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
									var val = context.parsed || 0;
									var pct = total > 0 ? Math.round(val / total * 100) : 0;
									return ' ' + val + ' (' + pct + '%)';
								}
							}
						}
					}
				}
			});

			canvas.style.cursor = 'pointer';
			canvas.onclick = function(evt) {
				var pts = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
				if (pts.length && urls[pts[0].index]) {
					window.location.href = urls[pts[0].index];
				}
			};

			function resizeChart() {
				if (!canvas || !chart) return;
				var widgetTile = canvas.closest('.dashboardWidget');
				if (widgetTile) {
					var header = widgetTile.querySelector('.dashboardWidgetHeader');
					var footer = widgetTile.querySelector('.dashBoardWidgetFooter');
					var tileHeight = widgetTile.clientHeight || widgetTile.offsetHeight || 300;
					var headerHeight = header ? (header.clientHeight || header.offsetHeight || 40) : 40;
					var footerHeight = footer ? (footer.clientHeight || footer.offsetHeight || 35) : 35;

					var availHeight = tileHeight - headerHeight - footerHeight - 12;
					if (availHeight > 120) {
						canvas.parentElement.style.height = availHeight + 'px';
						var parentContent = canvas.closest('.dashboardWidgetContent');
						if (parentContent) {
							parentContent.style.height = (tileHeight - headerHeight - footerHeight) + 'px';
							parentContent.style.maxHeight = (tileHeight - headerHeight - footerHeight) + 'px';
							parentContent.style.overflow = 'hidden';
						}
					}
				}
				chart.resize();
			}

			resizeChart();

			if (window.ResizeObserver) {
				var widgetTile = canvas.closest('.dashboardWidget');
				var ro = new ResizeObserver(function() {
					resizeChart();
				});
				if (widgetTile) {
					ro.observe(widgetTile);
				} else if (canvas.parentElement) {
					ro.observe(canvas.parentElement);
				}
			}

			window.addEventListener('resize', resizeChart);
			if (typeof jQuery !== 'undefined') {
				var widgetEl = jQuery(canvas).closest('.dashboardWidget');
				var evts = 'Vtiger.DashboardWidget.PostResize Vtiger.Widget.PostResize Vtiger.Widget.Resize Vtiger.Dashboard.PostLoad Vtiger.Dashboard.PostRefresh';
				if (widgetEl.length) {
					widgetEl.on(evts, resizeChart);
				}
				jQuery(document).on(evts, resizeChart);
			}
		}

		setTimeout(renderChart, 50);
		{/literal}
	})();
	</script>
{else}
	<span class="noDataMsg">{vtranslate('LBL_EQ_ZERO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}</span>
{/if}
</div>

<div class="widgeticons dashBoardWidgetFooter">
    <div class="filterContainer">
        <div class="row">
            <div class="col-sm-12">
                <span class="col-lg-4">
                    <span>
                        <strong>{vtranslate('Created Time', $MODULE_NAME)}&nbsp;{vtranslate('LBL_BETWEEN', $MODULE_NAME)}</strong>
                    </span>
                </span>
                <div class="col-lg-7">
                    <div class="input-daterange input-group dateRange widgetFilter" id="datepicker" name="createdtime">
                        <input type="text" class="input-sm form-control" name="start" style="height:30px;"/>
                        <span class="input-group-addon">to</span>
                        <input type="text" class="input-sm form-control" name="end" style="height:30px;"/>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <span class="col-lg-4">
                    <span>
                        <strong>{vtranslate('Assigned To', $MODULE_NAME)}</strong>
                    </span>
                </span>
                <span class="col-lg-7">
                        {assign var=CURRENT_USER_ID value=$CURRENTUSER->getId()}
                        <select class="select2 col-sm-12 widgetFilter reloadOnChange" name="owner">
                            <option value="" selected="selected">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
                            <option value="{$CURRENT_USER_ID}">{vtranslate('LBL_MINE')}</option>
                            {assign var=ALL_ACTIVEUSER_LIST value=$CURRENTUSER->getAccessibleUsers()}
                            {if php7_count($ALL_ACTIVEUSER_LIST) gt 1}
                                <optgroup label="{vtranslate('LBL_USERS')}">
                                    {foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEUSER_LIST}
                                        {if $OWNER_ID neq $CURRENT_USER_ID}
                                            <option value="{$OWNER_ID}">{$OWNER_NAME}</option>
                                        {/if}
                                    {/foreach}
                                </optgroup>
                            {/if}
                            {assign var=ALL_ACTIVEGROUP_LIST value=$CURRENTUSER->getAccessibleGroups()}
                            {if !empty($ALL_ACTIVEGROUP_LIST)}
                                <optgroup label="{vtranslate('LBL_GROUPS')}">
                                    {foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEGROUP_LIST}
                                        <option value="{$OWNER_ID}">{$OWNER_NAME}</option>
                                    {/foreach}
                                </optgroup>
                            {/if}
                        </select>
                </span>
            </div>
        </div>
    </div>
    <div class="footerIcons pull-right">
        {include file="dashboards/DashboardFooterIcons.tpl"|@vtemplate_path:$MODULE_NAME SETTING_EXIST=true}
    </div>
</div>
