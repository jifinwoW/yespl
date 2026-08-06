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

{strip}
{if !empty($DATA)}
    {assign var=SAFE_DATA value=Vtiger_Util_Helper::toSafeHTML($DATA)}
    <div style="padding: 6px 12px 14px 12px; width: 100%; position: relative; height: 195px; box-sizing: border-box;">
        <canvas id="chartjs_widget_content_{$WIDGET_ID}" data-chart="{$SAFE_DATA}" data-type="{$CHART_TYPE}" style="width: 100%; height: 175px;"></canvas>
    </div>
    <script type="text/javascript">
    (function() {
        var canvasId = 'chartjs_widget_content_{$WIDGET_ID}';
        {literal}
        function renderWidgetChart() {
            var canvas = document.getElementById(canvasId);
            if (!canvas) return;

            if (typeof window.Chart === 'undefined') {
                setTimeout(renderWidgetChart, 80);
                return;
            }

            var rawData = null;
            try {
                var rawAttr = canvas.getAttribute('data-chart') || '{}';
                rawData = JSON.parse(rawAttr);
                if (typeof rawData === 'string') {
                    rawData = JSON.parse(rawData);
                }
            } catch(e) {
                return;
            }
            if (!rawData) return;

            var labels = [], values = [], links = [];
            var isBar = false;

            if (Array.isArray(rawData)) {
                for (var i = 0; i < rawData.length; i++) {
                    var item = rawData[i];
                    if (typeof item[0] === 'number' || !isNaN(parseInt(item[0], 10))) {
                        values.push(parseInt(item[0], 10) || 0);
                        labels.push(item[1] || 'Unknown');
                    } else {
                        labels.push(item[0] || 'Unknown');
                        values.push(parseInt(item[1], 10) || 0);
                        isBar = true;
                    }
                    links.push(item['links'] || item['link'] || '');
                }
            } else if (typeof rawData === 'object') {
                labels = rawData.labels || [];
                var rawValues = rawData.values || [];
                links = rawData.links || [];
                for (var v = 0; v < rawValues.length; v++) {
                    if (Array.isArray(rawValues[v])) {
                        values.push(parseFloat(rawValues[v][0]) || 0);
                    } else {
                        values.push(parseFloat(rawValues[v]) || 0);
                    }
                }
            }

            if (!labels.length || !values.length) return;

            var existing = window.Chart.getChart(canvas);
            if (existing) {
                existing.destroy();
            }

            var chartTypeStr = canvas.getAttribute('data-type') || '';
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
            } else {
                type = isBar ? 'bar' : 'doughnut';
                if (isBar) indexAxis = 'y';
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
                borderRadius: (type === 'bar') ? 4 : 0,
                fill: isLine ? true : false,
                tension: isLine ? 0.35 : 0
            };

            if (isLine) {
                datasetConfig.pointRadius = 5;
                datasetConfig.pointHoverRadius = 8;
                datasetConfig.pointBackgroundColor = '#6366f1';
                datasetConfig.pointBorderColor = '#ffffff';
                datasetConfig.pointBorderWidth = 2;
            }

            var optionsConfig = {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { bottom: 10, top: 4, left: 4, right: 8 }
                },
                plugins: {
                    legend: {
                        display: isPieOrDoughnut,
                        position: 'bottom',
                        labels: { padding: 8, font: { size: 10 }, usePointStyle: true }
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
                    x: {
                        beginAtZero: true,
                        ticks: { font: { size: 10 }, maxRotation: 30 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { font: { size: 10 } },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                };
                if (indexAxis === 'y') {
                    optionsConfig.scales.y.grid = { display: false };
                }
            } else {
                optionsConfig.cutout = '60%';
            }

            var chart = new window.Chart(canvas.getContext('2d'), {
                type: type,
                data: { labels: labels, datasets: [datasetConfig] },
                options: optionsConfig
            });

            canvas.style.cursor = 'pointer';
            canvas.onclick = function(evt) {
                var pts = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
                if (pts.length && urls[pts[0].index]) {
                    window.location.href = links[pts[0].index];
                }
            };
        }

        setTimeout(renderWidgetChart, 50);
        {/literal}
    })();
    </script>
{else}
    <span class="noDataMsg">
        {vtranslate('LBL_NO')} {vtranslate($MODULE_NAME, $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
    </span>
{/if}
{/strip}
