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
	<table width="100%" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td class="span5">
				<div class="dashboardTitle" title="{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}"><b>&nbsp;&nbsp;{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</b></div>
			</td>
			<td class="span2">
				<div>
					<select class="widgetFilter" id="owner" name="owner" style='width:70px;margin-bottom:0px'>
						<option value="{$CURRENTUSER->getId()}" >{vtranslate('LBL_MINE')}</option>
						<option value="all">{vtranslate('LBL_ALL')}</option>
                        {assign var=ALL_ACTIVEUSER_LIST value=$CURRENTUSER->getAccessibleUsers()}
                        {if php7_count($ALL_ACTIVEUSER_LIST) gt 1}
                            <optgroup label="{vtranslate('LBL_USERS')}">
                                {foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEUSER_LIST}
                                    {if $OWNER_ID neq {$CURRENTUSER->getId()}}
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
				</div>
			</td>
			<td class="refresh span1" align="right">
				<span style="position:relative;"></span>
			</td>
			<td class="widgeticons span4" align="right">
				{include file="dashboards/DashboardHeaderIcons.tpl"|@vtemplate_path:$MODULE_NAME SETTING_EXIST=true}
			</td>
		</tr>
	</tbody>
	</table>
	<div class="row-fluid filterContainer hide" style="position:absolute;z-index:100001">
		<div class="row-fluid">
			<span class="span5">
				<span class="pull-right">
					{vtranslate('Expected Close Date', $MODULE_NAME)} &nbsp; {vtranslate('LBL_BETWEEN', $MODULE_NAME)}
				</span>
			</span>
			<span class="span4">
				<input type="text" name="expectedclosedate" class="dateRange widgetFilter" />
			</span>
		</div>
	</div>
</div>

<div class="dashboardWidgetContent">
{if php7_count($DATA) gt 0}
	{assign var=WIDGET_UNIQ value=$WIDGET->get('id')|default:$WIDGET->get('linkid')|default:103}
	{assign var=CHART_DATA value=Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}
	<div style="padding:10px; width:100%; position:relative; height:200px;">
		<canvas id="chartjs_stage_{$WIDGET_UNIQ}" data-chart="{$CHART_DATA}" style="width:100%; height:180px;"></canvas>
	</div>
	<script type="text/javascript">
	(function() {
		var targetId = 'chartjs_stage_{$WIDGET_UNIQ}';
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

			var labels = [], counts = [], linkVals = [];
			for (var i = 0; i < rawData.length; i++) {
				labels.push(rawData[i][0] || 'Unknown');
				counts.push(parseInt(rawData[i][1], 10) || 0);
				linkVals.push(rawData[i]['link'] || '');
			}

			var existing = window.Chart.getChart(canvas);
			if (existing) {
				existing.destroy();
			}

			canvas.height = Math.max(180, labels.length * 35);
			var ctx = canvas.getContext('2d');
			var chart = new window.Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: 'Deals',
						data: counts,
						backgroundColor: 'rgba(99,102,241,0.85)',
						borderColor: '#6366f1',
						borderWidth: 1,
						borderRadius: 4,
						borderSkipped: false,
						hoverBackgroundColor: 'rgba(99,102,241,1)'
					}]
				},
				options: {
					indexAxis: 'y',
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: { display: false },
						tooltip: {
							callbacks: {
								label: function(context) {
									return ' ' + (context.parsed.x || 0) + ' deals';
								}
							}
						}
					},
					scales: {
						x: {
							beginAtZero: true,
							ticks: { precision: 0 },
							grid: { color: 'rgba(0,0,0,0.05)' }
						},
						y: {
							ticks: { font: { size: 11 } },
							grid: { display: false }
						}
					}
				}
			});

			canvas.style.cursor = 'pointer';
			canvas.onclick = function(evt) {
				var pts = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
				if (pts.length && linkVals[pts[0].index]) {
					var stage = linkVals[pts[0].index];
					window.location.href = 'index.php?module=Potentials&view=List&search_params=' + encodeURIComponent(JSON.stringify([[['sales_stage', 'e', stage]]]));
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
