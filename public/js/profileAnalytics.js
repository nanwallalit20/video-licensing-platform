let watchTimeChart, monthlyTrendsChart, countryChart;
var graphData = function() {

    // Destroy existing charts if they exist
    if (watchTimeChart) watchTimeChart.destroy();
    if (monthlyTrendsChart) monthlyTrendsChart.destroy();
    if (countryChart) countryChart.destroy();

    // Watch Time Chart
    var watchTimeCtx = document.getElementById("watch-time-chart").getContext("2d");

    var gradientStroke3 = watchTimeCtx.createLinearGradient(0, 230, 0, 50);
    gradientStroke3.addColorStop(1, 'rgba(45,206,137,0.2)');
    gradientStroke3.addColorStop(0.2, 'rgba(45,206,137,0.0)');
    gradientStroke3.addColorStop(0, 'rgba(45,206,137,0)');

    watchTimeChart = new Chart(watchTimeCtx, {
        type: "bar",
        data: {
            labels: monthLabels,
            datasets: [{
                label: "Watch Time (Hours)",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#ffffff",
                data: watchTimeData,
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        suggestedMin: 0,
                        beginAtZero: true,
                        padding: 15,
                        font: {
                            size: 14,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#fff"
                    },
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        display: true,
                        color: "#fff",
                        padding: 15,
                        font: {
                            size: 14,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    },
                },
            },
        },
    });

    // Monthly Trends Chart (Views and Completion Rate)
    var monthlyCtx = document.getElementById("monthly-trends-chart").getContext("2d");

    var gradientStroke1 = monthlyCtx.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)');

    monthlyTrendsChart = new Chart(monthlyCtx, {
        type: "line",
        data: {
            labels: monthLabels,
            datasets: [{
                label: "Views",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#0ca7ca",
                borderWidth: 3,
                backgroundColor: gradientStroke1,
                fill: true,
                data: totalViews,
                yAxisID: 'y',
            },
            {
                label: "Completion Rate (%)",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#3A416F",
                borderWidth: 3,
                backgroundColor: 'transparent',
                borderDash: [5, 5],
                fill: false,
                data: completionRateData,
                yAxisID: 'y1',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#b2b9bf',
                        font: {
                            family: "Open Sans"
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#0ca7ca',
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#3A416F',
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: true,
                        color: '#2a2f51',
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });

    // Country Analytics Chart
    var countryCtx = document.getElementById("country-chart").getContext("2d");

    countryChart = new Chart(countryCtx, {
        type: 'bar',
        data: {
            labels: countryLabels,
            datasets: [
                {
                    label: 'Total Views',
                    data: countryViews,
                    backgroundColor: 'rgba(255, 255, 255, 0.8)',
                    order: 2,
                    yAxisID: 'y'
                },
                {
                    label: 'Completion Rate (%)',
                    data: countryCompletionRates,
                    type: 'line',
                    borderColor: '#0ca7ca',
                    borderWidth: 2,
                    pointBackgroundColor: '#0ca7ca',
                    pointBorderColor: '#0ca7ca',
                    tension: 0.4,
                    order: 1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#fff',
                        font: {
                            family: "Open Sans"
                        }
                    }
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5],
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        beginAtZero: true,
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#fff"
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        beginAtZero: true,
                        padding: 10,
                        max: 100,
                        font: {
                            size: 12,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#0ca7ca",
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        display: true,
                        color: "#fff",
                        padding: 10,
                        font: {
                            size: 12,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        }
                    }
                }
            }
        }
    });
}

window.onload = graphData;
