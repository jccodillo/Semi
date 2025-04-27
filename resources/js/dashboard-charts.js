document.addEventListener('DOMContentLoaded', function() {
    // Stock Level Trends Chart
    const stockTrendsCtx = document.getElementById('stockTrendsChart');
    if (stockTrendsCtx) {
        new Chart(stockTrendsCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: window.stockTrendsData.labels,
                datasets: [{
                    label: 'Stock Levels',
                    data: window.stockTrendsData.values,
                    borderColor: '#4CAF50',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    // Category Distribution Chart
    const categoryDistCtx = document.getElementById('categoryDistributionChart').getContext('2d');
    new Chart(categoryDistCtx, {
        type: 'doughnut',
        data: {
            labels: window.categoryData.labels,
            datasets: [{
                data: window.categoryData.values,
                backgroundColor: ['#4CAF50', '#2196F3', '#FFC107', '#FF5722', '#9C27B0']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Inventory Value Trends Chart
    const valueTrendsCtx = document.getElementById('valueTrendsChart').getContext('2d');
    new Chart(valueTrendsCtx, {
        type: 'bar',
        data: {
            labels: window.valueTrendsData.labels,
            datasets: [{
                label: 'Inventory Value',
                data: window.valueTrendsData.values,
                backgroundColor: '#4CAF50'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
