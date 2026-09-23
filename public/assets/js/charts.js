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
  initRevenueChart: function(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx || typeof Chart === 'undefined') return;

    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep (MTD)'],
        datasets: [
          {
            label: 'Online Payments (PayHere)',
            data: [180000, 245000, 310000, 395000, 342000],
            borderColor: '#87bb4c',
            backgroundColor: 'rgba(135, 187, 76, 0.1)',
            fill: true,
            tension: 0.35
          },
          {
            label: 'Cash on Arrival',
            data: [45000, 60000, 85000, 78000, 62000],
            borderColor: '#09203f',
            backgroundColor: 'transparent',
            borderDash: [5, 5],
            tension: 0.35
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' },
          tooltip: {
            callbacks: {
              label: function(context) {
                return context.dataset.label + ': LKR ' + context.parsed.y.toLocaleString();
              }
            }
          }
        },
        scales: {
          y: {
            ticks: {
              callback: function(value) {
                return 'LKR ' + (value / 1000) + 'k';
              }
            },
            grid: { color: '#f1f5f9' }
          },
          x: { grid: { display: false } }
        }
      }
    });
  },

  // 3. Coach Monthly Earnings
  initCoachEarningsChart: function(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx || typeof Chart === 'undefined') return;

    return new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['May', 'Jun', 'Jul', 'Aug', 'Sep'],
        datasets: [{
          label: 'Net Coaching Earnings (LKR)',
          data: [42000, 56000, 78000, 92000, 87500],
          backgroundColor: '#09203f',
          borderRadius: 4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (ctx) => 'Earnings: LKR ' + ctx.parsed.y.toLocaleString()
            }
          }
        },
        scales: {
          y: {
            ticks: {
              callback: (v) => 'LKR ' + (v / 1000) + 'k'
            },
            grid: { color: '#f1f5f9' }
          },
          x: { grid: { display: false } }
        }
      }
    });
  },

  // 4. Admin Platform Growth
  initAdminGrowthChart: function(canvasId) {
    const ctx = document.getElementById(canvasId);
    if (!ctx || typeof Chart === 'undefined') return;

    return new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
        datasets: [{
          label: 'Total Bookings',
          data: [142, 198, 260, 318],
          borderColor: '#87bb4c',
          backgroundColor: 'rgba(135, 187, 76, 0.08)',
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { grid: { color: '#f1f5f9' } },
          x: { grid: { display: false } }
        }
      }
    });
  }
};
