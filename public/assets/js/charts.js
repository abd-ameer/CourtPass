/**
 * CourtPass Dashboard Visualizations (Chart.js wrappers)
 * Colors: Blue #09203f & Green #87bb4c
 */

const CourtPassCharts = {
  // 1. Owner Court Utilisation Heatmap / Bar Chart
  initUtilisationChart: function(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx || typeof Chart === 'undefined') return;

    return new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00', '22:00'],
        datasets: [
          {
            label: 'Customer Bookings (Hrs)',
            data: [2, 4, 3, 2, 5, 8, 12, 14, 9],
            backgroundColor: '#87bb4c',
            borderRadius: 4
          },
          {
            label: 'Coaching Sessions (Hrs)',
            data: [0, 1, 0, 0, 2, 4, 3, 2, 0],
            backgroundColor: '#09203f',
            borderRadius: 4
          },
          {
            label: 'Blocked / Maintenance',
            data: [1, 0, 2, 1, 0, 0, 0, 0, 0],
            backgroundColor: '#d97706',
            borderRadius: 4
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' }
        },
        scales: {
          x: { stacked: true, grid: { display: false } },
          y: { stacked: true, grid: { color: '#f1f5f9' }, title: { display: true, text: 'Hours Utilised' } }
        }
      }
    });
  },

  // 2. Owner Revenue Breakdown Chart (Online vs Cash-on-Arrival)
};
