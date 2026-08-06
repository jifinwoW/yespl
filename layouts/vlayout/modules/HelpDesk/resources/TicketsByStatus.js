/* Chart.js - Tickets by Status Doughnut Widget */
(function () {
    var container = document.querySelector('.vtChartTicketsByStatus:not([data-chartjs-init])');
    if (!container) return;
    container.setAttribute('data-chartjs-init', '1');

    var canvas  = container.querySelector('canvas');
    var rawData = JSON.parse(container.getAttribute('data-chart') || '[]');
    if (!canvas || !rawData.length) return;

    var labels = [], counts = [], urls = [];
    for (var i = 0; i < rawData.length; i++) {
        counts.push(parseInt(rawData[i][0], 10));
        labels.push(rawData[i][1]);
        urls.push(rawData[i]['links'] || '');
    }

    var palette = ['#6366f1','#22d3ee','#f59e0b','#10b981','#ef4444','#8b5cf6','#f43f5e','#0ea5e9'];
    var ctx = canvas.getContext('2d');
    var chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: palette.slice(0, counts.length),
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            cutout: '62%',
            animation: { animateRotate: true, duration: 800 },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 14, font: { size: 12 }, usePointStyle: true, pointStyleWidth: 10 }
                },
                tooltip: {
                    callbacks: {
                        label: function (c) {
                            var t = c.dataset.data.reduce(function (a, b) { return a + b; }, 0);
                            var pct = t > 0 ? Math.round(c.parsed / t * 100) : 0;
                            return ' ' + c.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    canvas.style.cursor = 'pointer';
    canvas.addEventListener('click', function (evt) {
        var pts = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);
        if (pts.length && urls[pts[0].index]) {
            window.location.href = urls[pts[0].index];
        }
    });
}());
