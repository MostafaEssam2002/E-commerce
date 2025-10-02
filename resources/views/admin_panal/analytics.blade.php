@extends('admin_panal.master')
@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="mdi mdi-account-multiple"></i>
        </div>
        <div class="stat-title">Total Visitors</div>
        <div class="stat-value">45,231</div>
        <div class="stat-change positive">
            <i class="mdi mdi-arrow-up"></i>
            <span>+12.5% from last month</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success">
            <i class="mdi mdi-eye"></i>
        </div>
        <div class="stat-title">Page Views</div>
        <div class="stat-value">128,456</div>
        <div class="stat-change positive">
            <i class="mdi mdi-arrow-up"></i>
            <span>+8.2% from last month</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="mdi mdi-clock-outline"></i>
        </div>
        <div class="stat-title">Avg. Session</div>
        <div class="stat-value">3m 24s</div>
        <div class="stat-change negative">
            <i class="mdi mdi-arrow-down"></i>
            <span>-2.4% from last month</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="mdi mdi-cursor-default-click"></i>
        </div>
        <div class="stat-title">Click Rate</div>
        <div class="stat-value">4.8%</div>
        <div class="stat-change positive">
            <i class="mdi mdi-arrow-up"></i>
            <span>+15.3% from last month</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="chart-grid">
    <!-- Product Categories Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Product Categories</h3>
            <p class="chart-subtitle">Distribution by category</p>
        </div>
        <canvas id="productCategoriesChart"></canvas>
    </div>

    <!-- User Activity Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">User Activity by Hour</h3>
            <p class="chart-subtitle">Daily traffic pattern</p>
        </div>
        <canvas id="userActivityChart"></canvas>
    </div>
</div>

<!-- Page Performance Table -->
<div class="chart-card mt-4">
    <div class="chart-header">
        <h3 class="chart-title">Top Performing Pages</h3>
        <p class="chart-subtitle">Page views and engagement metrics</p>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Page</th>
                    <th class="text-end">Views</th>
                    <th class="text-end">Avg Time</th>
                    <th class="text-end">Bounce Rate</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Home</strong></td>
                    <td class="text-end">15,420</td>
                    <td class="text-end">2:45</td>
                    <td class="text-end">
                        <span class="badge bg-success">35%</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Products</strong></td>
                    <td class="text-end">12,350</td>
                    <td class="text-end">3:20</td>
                    <td class="text-end">
                        <span class="badge bg-success">28%</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>About</strong></td>
                    <td class="text-end">8,920</td>
                    <td class="text-end">1:50</td>
                    <td class="text-end">
                        <span class="badge bg-warning">45%</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Contact</strong></td>
                    <td class="text-end">6,780</td>
                    <td class="text-end">2:10</td>
                    <td class="text-end">
                        <span class="badge bg-danger">52%</span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Blog</strong></td>
                    <td class="text-end">9,540</td>
                    <td class="text-end">4:30</td>
                    <td class="text-end">
                        <span class="badge bg-success">22%</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Traffic Sources -->
<div class="chart-grid mt-4">
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Traffic Sources</h3>
            <p class="chart-subtitle">Where visitors come from</p>
        </div>
        <div class="source-list">
            <div class="source-item">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Organic Search</span>
                    <span class="text-muted">42%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" style="width: 42%"></div>
                </div>
            </div>
            <div class="source-item mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Direct</span>
                    <span class="text-muted">28%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 28%"></div>
                </div>
            </div>
            <div class="source-item mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Social Media</span>
                    <span class="text-muted">18%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-info" style="width: 18%"></div>
                </div>
            </div>
            <div class="source-item mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Referral</span>
                    <span class="text-muted">12%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-warning" style="width: 12%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Device Usage</h3>
            <p class="chart-subtitle">User device preferences</p>
        </div>
        <canvas id="deviceChart"></canvas>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Colors setup
const colors = {
    primary: '#6366f1',
    secondary: '#8b5cf6',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#06b6d4'
};

// Product Categories Chart
const productCategoriesCtx = document.getElementById('productCategoriesChart').getContext('2d');
const productCategoriesChart = new Chart(productCategoriesCtx, {
    type: 'doughnut',
    data: {
        labels: ['Electronics', 'Clothing', 'Food', 'Books', 'Others'],
        datasets: [{
            data: [35, 25, 20, 12, 8],
            backgroundColor: [
                colors.primary,
                colors.secondary,
                colors.danger,
                colors.success,
                colors.warning
            ],
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    font: { size: 12 }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + '%';
                    }
                }
            }
        },
        cutout: '65%'
    }
});

// User Activity by Hour Chart
const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
const userActivityChart = new Chart(userActivityCtx, {
    type: 'bar',
    data: {
        labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
        datasets: [{
            label: 'Visitors',
            data: [120, 80, 150, 380, 520, 480, 620, 450],
            backgroundColor: (context) => {
                const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, colors.primary);
                gradient.addColorStop(1, colors.secondary);
                return gradient;
            },
            borderRadius: 8,
            hoverBackgroundColor: colors.secondary
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Device Usage Chart
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
const deviceChart = new Chart(deviceCtx, {
    type: 'pie',
    data: {
        labels: ['Desktop', 'Mobile', 'Tablet'],
        datasets: [{
            data: [45, 42, 13],
            backgroundColor: [
                colors.primary,
                colors.success,
                colors.warning
            ],
            borderWidth: 0,
            hoverOffset: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    font: { size: 12 }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + '%';
                    }
                }
            }
        }
    }
});

// Resize charts on window resize
window.addEventListener('resize', function() {
    productCategoriesChart.resize();
    userActivityChart.resize();
    deviceChart.resize();
});
</script>
@endsection
