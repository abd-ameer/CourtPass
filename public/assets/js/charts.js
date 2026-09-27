/**
 * CourtPass Chart.js wrapper (owner utilisation heatmap only).
 * Colors: Blue #09203f & Green #87bb4c
 */

const CourtPassCharts = {
  // Stacked bar chart of used court hours per hour of the day.
  // data: { labels: [...], bookings: [...], coaching: [...], blocks: [...] }
  initUtilisationChart: function(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx || typeof Chart === 'undefined' || !data) return;

    return new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: [
          { label: 'Customer Bookings (Hrs)', data: data.bookings, backgroundColor: '#87bb4c', borderRadius: 4 },
          { label: 'Coaching Sessions (Hrs)', data: data.coaching, backgroundColor: '#09203f', borderRadius: 4 },
          { label: 'Owner Blocks (Hrs)', data: data.blocks, backgroundColor: '#d97706', borderRadius: 4 }
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
          y: { stacked: true, beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' }, title: { display: true, text: 'Hours Used' } }
        }
      }
    });
  }
};
