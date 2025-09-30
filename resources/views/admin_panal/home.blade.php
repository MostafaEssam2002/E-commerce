@extends('admin_panal.master')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="mdi mdi-currency-usd"></i>
        </div>
        <div class="stat-title">Total Revenue</div>
        <div class="stat-value">${{$total_revenue}}</div>
        <div class="stat-change positive">
            @if ($revenue_percentage > 0)
                <i class="mdi mdi-arrow-up"></i>
                    <span>
                        +{{number_format($revenue_percentage,2)}}
                        % from last month
                    </span>
            @elseif ($revenue_percentage < 0)
                    <i class="mdi mdi-arrow-down"></i>
                        <span>
                            {{number_format($revenue_percentage,2)}}
                            % from last month
                        </span>
            @endif
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="mdi mdi-account-multiple"></i>
        </div>
        <div class="stat-title">Number of Users</div>
        <div class="stat-value">{{$total_users}}</div>
        @if ($user_percentage > 0)
            <div class="stat-change positive">
                <i class="mdi mdi-arrow-up"></i>
                <span>
                    +{{number_format($user_percentage,2)}}
                    % from last month
                </span>
            </div>
            @elseif ($user_percentage < 0)
                <div class="stat-change negative">
                    <i class="mdi mdi-arrow-down"></i>
                        <span>
                            {{number_format($user_percentage,2)}}
                            % from last month
                        </span>
                </div>
            @endif
        {{-- </div> --}}
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="mdi mdi-shopping"></i>
        </div>
        <div class="stat-title">Number of Orders</div>
        <div class="stat-value">{{$total_orders}}</div>
        @if ($order_percentage > 0)
            <div class="stat-change positive">
                <i class="mdi mdi-arrow-up"></i>
                <span>
                    +{{number_format($order_percentage,2)}}
                    % from last month
                </span>
            </div>
            @elseif ($order_percentage < 0)
                <div class="stat-change negative">
                    <i class="mdi mdi-arrow-down"></i>
                        <span>
                            {{number_format($order_percentage,2)}}
                            % from last month
                        </span>
                </div>
            @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="mdi mdi-chart-line"></i>
        </div>
        <div class="stat-title">Conversion Rate</div>
        <div class="stat-value">{{number_format($Conversion_Rate,2)}}%</div>
        @if ($Conversion_Rate_percentage > 0)
            <div class="stat-change positive">
                <i class="mdi mdi-arrow-up"></i>
                <span>
                    +{{number_format($Conversion_Rate_percentage,2)}}
                    % from last month
                </span>
            </div>
            @elseif ($Conversion_Rate_percentage < 0)
                <div class="stat-change negative">
                    <i class="mdi mdi-arrow-down"></i>
                        <span>
                            {{number_format($Conversion_Rate_percentage,2)}}
                            % from last month
                        </span>
                </div>
        @endif
    </div>
</div>

<!-- Charts Section -->
<div class="chart-grid">
    <!-- Sales Chart -->
    <div class="chart-card" style="grid-column: span 2;">
        <div class="chart-header">
            <h3 class="chart-title">Sales Overview</h3>
            <p class="chart-subtitle">Monthly sales statistics for this year</p>
        </div>
        <canvas id="salesChart"></canvas>
    </div>

    <!-- Revenue Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Revenue Distribution</h3>
            <p class="chart-subtitle">By product type</p>
        </div>
        <canvas id="revenueChart"></canvas>
    </div>

    <!-- User Growth Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">User Growth</h3>
            <p class="chart-subtitle">Last 6 months</p>
        </div>
        <canvas id="userGrowthChart"></canvas>
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

    // Sales Chart - Multi Line
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September'],
            datasets: [{
                label: 'Sales',
                data: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000],
                borderColor: colors.primary,
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }, {
                label: 'Profit',
                data: [8000, 14000, 11000, 18000, 16000, 22000, 20000, 26000, 24000],
                borderColor: colors.success,
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: colors.success,
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 13,
                            family: "'Segoe UI', sans-serif"
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Revenue Chart - Doughnut
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            labels: ['Electronics', 'Clothing', 'Food', 'Books', 'Other'],
            datasets: [{
                data: [35, 25, 20, 12, 8],
                backgroundColor: [
                    colors.primary,
                    colors.success,
                    colors.warning,
                    colors.info,
                    colors.secondary
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
                        font: {
                            size: 12
                        }
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

    // User Growth Chart - Bar
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    const userGrowthChart = new Chart(userGrowthCtx, {
        type: 'bar',
        data: {
            labels: ['March', 'April', 'May', 'June', 'July', 'August'],
            datasets: [{
                label: 'New Users',
                data: [420, 580, 650, 720, 890, 950],
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
                legend: {
                    display: false
                },
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
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Resize charts on window resize
    window.addEventListener('resize', function() {
        salesChart.resize();
        revenueChart.resize();
        userGrowthChart.resize();
    });
</script>
@endsection
