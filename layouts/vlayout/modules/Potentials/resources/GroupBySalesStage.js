/* Chart.js - Potentials by Sales Stage Horizontal Bar Widget */
(function () {
    var container = document.querySelector('.vtChartGroupBySalesStage:not([data-chartjs-init])');
    if (!container) return;
    container.setAttribute('data-chartjs-init', '1');

    var canvas  = container.querySelector('canvas');
    var rawData = JSON.parse(container.getAttribute('data-chart') || '[]');
    if (!canvas || !rawData.length) return;

    var labels = [], counts = [], linkVals = [];
    for (var i = 0; i < rawData.length; i++) {
        labels.push(rawData[i][0]);
        counts.push(parseInt(rawData[i][1], 10));
        linkVals.push(rawData[i]['link'] || '');
    }

    canvas.height = Math.max(180, labels.length * 36);
    var ctx = canvas.getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Deals',
                data: counts,
                backgroundColor: 'rgba(99,102,241,0.82)',
                borderColor: '#6366f1',
                borderWidth: 1,
                borderRadius: 5,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(99,102,241,1)'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            animation: { duration: 700 },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (c) { return ' ' + c.parsed.x + ' deals'; }
                    }
                }
            },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 }, grid: { color: 'rgba(0,0,0,0.05)' } },
                y: { ticks: { font: { size: 12 } }, grid: { display: false } }
            }
        }
    });

    canvas.style.cursor = 'pointer';
    canvas.addEventListener('click', function (evt) {
        var pts = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
        if (pts.length && linkVals[pts[0].index]) {
            var stage = linkVals[pts[0].index];
            var url = 'index.php?module=Potentials&view=List&search_params=' + encodeURIComponent(JSON.stringify([[['sales_stage', 'e', stage]]]));
            window.location.href = url;
        }
    });
}());
