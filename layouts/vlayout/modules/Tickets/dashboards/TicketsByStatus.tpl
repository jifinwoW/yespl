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
	<div class="row-fluid filterContainer hide" style="position:absolute;z-index:100001; background:#fff; padding:10px; border:1px solid #ccc; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
		<div class="row-fluid">
			<span class="span5"><span class="pull-right">{vtranslate('Created Time', $MODULE_NAME)}&nbsp;{vtranslate('LBL_BETWEEN', $MODULE_NAME)}</span></span>
			<span class="span4"><input type="text" name="createdtime" class="dateRange widgetFilter" /></span>
		</div>
		<div class="row-fluid">
			<span class="span5"><span class="pull-right">{vtranslate('Assigned To', $MODULE_NAME)}</span></span>
			<span class="span4">
				{assign var=CURRENT_USER_ID value=$CURRENTUSER->getId()}
				<select class="widgetFilter" name="owner">
					<option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
					{foreach key=USER_ID item=USER_NAME from=$ACCESSIBLE_USERS}
					<option value="{$USER_ID}">{if $USER_ID eq $CURRENTUSER->getId()}{vtranslate('LBL_MINE',$MODULE_NAME)}{else}{$USER_NAME}{/if}</option>
					{/foreach}
				</select>
			</span>
		</div>
	</div>
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
    <div class="footerIcons pull-right">
        {include file="dashboards/DashboardFooterIcons.tpl"|@vtemplate_path:$MODULE_NAME SETTING_EXIST=true}
    </div>
</div>
