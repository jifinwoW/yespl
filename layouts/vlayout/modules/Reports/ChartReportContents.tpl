{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<input type='hidden' name='charttype' value="{$CHART_TYPE}" />
{assign var=SAFE_DATA value=Vtiger_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATA))}

<br>
<div class="dashboardWidgetContent">
    <input type="hidden" class="yAxisFieldType" value="{$YAXIS_FIELD_TYPE}" />
    <div class='border1px filterConditionContainer' style="padding:20px; background:#fff; border-radius:6px;">
        <div style="min-height:400px; width:100%; position:relative;">
            <canvas id="chartjs_report_detail" data-chart="{$SAFE_DATA}" data-type="{$CHART_TYPE}" style="width:100%; min-height:380px;"></canvas>
        </div>
        <script type="text/javascript">
        (function() {
            {literal}
            function renderDetailChart() {
                var canvas = document.getElementById('chartjs_report_detail');
                if (!canvas) return;

                if (typeof window.Chart === 'undefined') {
                    setTimeout(renderDetailChart, 80);
                    return;
                }

                var rawData = {};
                try {
                    var rawAttr = canvas.getAttribute('data-chart') || '{}';
                    rawData = JSON.parse(rawAttr);
                    if (typeof rawData === 'string') {
                        rawData = JSON.parse(rawData);
                    }
                } catch(e) {
                    return;
                }
                var labels = rawData.labels || [];
                var rawValues = rawData.values || [];
                var links = rawData.links || [];
                var chartTypeStr = canvas.getAttribute('data-type') || 'pieChart';

                var values = [];
                for (var v = 0; v < rawValues.length; v++) {
                    if (Array.isArray(rawValues[v])) {
                        values.push(parseFloat(rawValues[v][0]) || 0);
                    } else {
                        values.push(parseFloat(rawValues[v]) || 0);
                    }
                }

                if (!labels.length || !values.length) return;

                var existing = window.Chart.getChart(canvas);
                if (existing) {
                    existing.destroy();
                }

                var type = 'bar';
                var indexAxis = 'x';

                if (chartTypeStr === 'pieChart' || chartTypeStr === 'pie') {
                    type = 'doughnut';
                } else if (chartTypeStr === 'horizontalbarChart' || chartTypeStr === 'horizontalBar') {
                    type = 'bar';
                    indexAxis = 'y';
                } else if (chartTypeStr === 'verticalbarChart' || chartTypeStr === 'verticalBar') {
                    type = 'bar';
                    indexAxis = 'x';
                } else if (chartTypeStr === 'lineChart' || chartTypeStr === 'line') {
                    type = 'line';
                    indexAxis = 'x';
                }

                var isPieOrDoughnut = (type === 'doughnut' || type === 'pie');
                var isLine = (type === 'line');

                var palette = [
                    '#6366f1','#22d3ee','#f59e0b','#10b981',
                    '#ef4444','#8b5cf6','#f43f5e','#0ea5e9',
                    '#84cc16','#fb923c','#a855f7','#06b6d4'
                ];

                var datasetConfig = {
                    label: rawData.graph_label || 'Report Data',
                    data: values,
                    backgroundColor: isPieOrDoughnut ? palette.slice(0, values.length) : (isLine ? 'rgba(99, 102, 241, 0.15)' : 'rgba(99,102,241,0.85)'),
                    borderColor: isPieOrDoughnut ? '#ffffff' : '#6366f1',
                    borderWidth: isLine ? 3 : (isPieOrDoughnut ? 2 : 1),
                    borderRadius: (type === 'bar') ? 5 : 0,
                    fill: isLine ? true : false,
                    tension: isLine ? 0.35 : 0
                };

                if (isLine) {
                    datasetConfig.pointRadius = 6;
                    datasetConfig.pointHoverRadius = 9;
                    datasetConfig.pointBackgroundColor = '#6366f1';
                    datasetConfig.pointBorderColor = '#ffffff';
                    datasetConfig.pointBorderWidth = 2;
                }

                var optionsConfig = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: isPieOrDoughnut,
                            position: 'bottom',
                            labels: { padding: 14, font: { size: 12 }, usePointStyle: true }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var val = context.raw !== undefined ? context.raw : context.formattedValue;
                                    if (isPieOrDoughnut) {
                                        var total = context.dataset.data.reduce(function(a, b) { return a + b; }, 0);
                                        var num = parseFloat(val) || 0;
                                        var pct = total > 0 ? Math.round(num / total * 100) : 0;
                                        return ' ' + val + ' (' + pct + '%)';
                                    }
                                    return ' ' + val;
                                }
                            }
                        }
                    }
                };

                if (!isPieOrDoughnut) {
                    optionsConfig.indexAxis = indexAxis;
                    optionsConfig.scales = {
                        x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } }
                    };
                    if (indexAxis === 'y') {
                        optionsConfig.scales.y.grid = { display: false };
                    }
                }

                var chart = new window.Chart(canvas.getContext('2d'), {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [datasetConfig]
                    },
                    options: optionsConfig
                });

                canvas.style.cursor = 'pointer';
                canvas.onclick = function(evt) {
                    var pts = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
                    if (pts.length && links[pts[0].index]) {
                        window.location.href = links[pts[0].index];
                    }
                };
            }

            setTimeout(renderDetailChart, 50);
            {/literal}
        })();
        </script>

        <br>
        {if !isset($CLICK_THROUGH) || $CLICK_THROUGH neq 'true'}
            <div class='row-fluid alert-info'>
                <span class='span alert-info' style="padding:10px;text-align:center">
                    <i class="icon-info-sign"></i>
                    {vtranslate('LBL_CLICK_THROUGH_NOT_AVAILABLE', $MODULE)}
                </span>
            </div>
            <br>
        {/if}
        {if $REPORT_MODEL->isInventoryModuleSelected()}
            <div class="alert alert-info">
                {assign var=BASE_CURRENCY_INFO value=Vtiger_Util_Helper::getUserCurrencyInfo()}
                <i class="icon-info-sign" style="margin-top: 1px;"></i>&nbsp;&nbsp;
                {vtranslate('LBL_CALCULATION_CONVERSION_MESSAGE', $MODULE)} - {$BASE_CURRENCY_INFO['currency_name']} ({$BASE_CURRENCY_INFO['currency_code']})
            </div>
        {/if}
    </div>
</div>
<br>
