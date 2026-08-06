{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}

<div class="dashboardWidgetHeader">
	{include file="dashboards/WidgetHeader.tpl"|@vtemplate_path:$MODULE_NAME SETTING_EXIST=true}
	<div class="row-fluid filterContainer hide" style="position:absolute;z-index:100001">
		<div class="row-fluid">
			<span class="span5">
				<span class="pull-right">
					{vtranslate('Created Time', $MODULE_NAME)} &nbsp; {vtranslate('LBL_BETWEEN', $MODULE_NAME)}
				</span>
			</span>
			<span class="span4">
				<input type="text" name="createdtime" class="dateRange widgetFilter" />
			</span>	
		</div>
		<div class="row-fluid">		
			<span class="span5">
				<span class="pull-right">
					{vtranslate('Assigned To', $MODULE_NAME)}
				</span>
			</span>
			<span class="span4">
				{assign var=CURRENT_USER_ID value=$CURRENTUSER->getId()}
				<select class="widgetFilter" name="smownerid">
					<option value="">{vtranslate('LBL_ALL', $MODULE_NAME)}</option>
					{foreach key=USER_ID item=USER_NAME from=$ACCESSIBLE_USERS}
					<option value="{$USER_ID}">
						{if $USER_ID eq $CURRENTUSER->getId()}
							{vtranslate('LBL_MINE',$MODULE_NAME)}
						{else}
							{$USER_NAME}
						{/if}
					</option>
					{/foreach}
				</select>
			</span>
		</div>
	</div>
</div>

<div class="dashboardWidgetContent">
{if php7_count($DATA) gt 0}
	{assign var=WIDGET_UNIQ value=$WIDGET->get('id')|default:$WIDGET->get('linkid')|default:102}
	{assign var=CHART_DATA value=Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}
	<div style="padding:10px; width:100%; position:relative; height:200px;">
		<canvas id="chartjs_leads_{$WIDGET_UNIQ}" data-chart="{$CHART_DATA}" style="width:100%; height:180px;"></canvas>
	</div>
	<script type="text/javascript">
	(function() {
		var targetId = 'chartjs_leads_{$WIDGET_UNIQ}';
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
				counts.push(parseInt(rawData[i][0], 10) || 0);
				labels.push(rawData[i][1] || 'Unknown');
				urls.push(rawData[i]['links'] || '');
			}

			var existing = window.Chart.getChart(canvas);
			if (existing) {
				existing.destroy();
			}

			var palette = ['#6366f1','#f59e0b','#10b981','#22d3ee','#ef4444','#8b5cf6','#f43f5e','#0ea5e9','#84cc16','#fb923c'];
			var ctx = canvas.getContext('2d');
			var chart = new window.Chart(ctx, {
				type: 'pie',
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
		}

		setTimeout(renderChart, 50);
		{/literal}
	})();
	</script>
{else}
	<span class="noDataMsg">{vtranslate('LBL_EQ_ZERO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}</span>
{/if}
</div>
