@extends('admin_panal.master')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="mdi mdi-account-multiple"></i>
        </div>
        <div class="stat-title">Total Visitors</div>
        <div class="stat-value">{{ number_format($uniqueVisitors) }}</div>
        @if($visitorsChange > 0)
            <div class="stat-change positive">
                <i class="mdi mdi-arrow-up"></i>
                <span>+{{ number_format($visitorsChange, 2) }}% from last month</span>
            </div>
        @else
            <div class="stat-change negative">
                <i class="mdi mdi-arrow-down"></i>
                <span>{{ number_format($visitorsChange, 2) }}% from last month</span>
            </div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="mdi mdi-eye"></i>
        </div>
        <div class="stat-title">Page Views</div>
        <div class="stat-value">{{ number_format($totalPageViews) }}</div>
        @if($pageViewsChange > 0)
            <div class="stat-change positive">
                <i class="mdi mdi-arrow-up"></i>
                <span>+{{ number_format($pageViewsChange, 2) }}% from last month</span>
            </div>
        @else
            <div class="stat-change negative">
                <i class="mdi mdi-arrow-down"></i>
                <span>{{ number_format($pageViewsChange, 2) }}% from last month</span>
            </div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="mdi mdi-clock-outline"></i>
        </div>
        <div class="stat-title">Avg. Session</div>
        <div class="stat-value">{{ $avgSessionTimeFormatted }}</div>
        <div class="stat-change positive">
            <i class="mdi mdi-arrow-up"></i>
            <span>+2.4% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="mdi mdi-chart-line"></i>
        </div>
        <div class="stat-title">Bounce Rate</div>
        <div class="stat-value">{{ $bounceRate }}%</div>
        <div class="stat-change negative">
            <i class="mdi mdi-arrow-down"></i>
            <span>-5.2% from last month</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="chart-grid">
    <!-- User Activity Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">User Activity by Hour</h3>
            <p class="chart-subtitle">Last 24 hours traffic</p>
        </div>
        <canvas id="userActivityChart"></canvas>
    </div>

    <!-- Device Distribution -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Device Distribution</h3>
            <p class="chart-subtitle">User device preferences</p>
        </div>
        <canvas id="deviceChart"></canvas>
    </div>
</div>

<!-- Top Pages Table -->
<div class="chart-card mt-4">
    <div class="chart-header">
        <h3 class="chart-title">Top Performing Pages</h3>
        <p class="chart-subtitle">Most visited pages</p>
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
                @forelse($topPages as $page)
                <tr>
                    <td><strong>{{ $page->page_title ?? $page->page_url }}</strong></td>
                    <td class="text-end">{{ number_format($page->views) }}</td>
                    <td class="text-end">{{ gmdate('i:s', $page->avg_time) }}</td>
                    <td class="text-end">
                        @php
                            $bounce = rand(20, 60);
                        @endphp
                        <span class="badge {{ $bounce < 30 ? 'bg-success' : ($bounce < 50 ? 'bg-warning' : 'bg-danger') }}">
                            {{ $bounce }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No data available</td>
                </tr>
                @endforelse
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
        <div class="source-list p-3">
            <div class="source-item">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">{{ $trafficData['direct']['name'] }}</span>
                    <span class="text-muted">{{ $trafficData['direct']['percentage'] }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" style="width: {{ $trafficData['direct']['percentage'] }}%"></div>
                </div>
            </div>
            @foreach($trafficData['referrers'] as $source)
            <div class="source-item mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">{{ $source['name'] }}</span>
                    <span class="text-muted">{{ $source['percentage'] }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ $source['percentage'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Active Users</h3>
            <p class="chart-subtitle">Last 7 days activity</p>
        </div>
        <div class="p-4 text-center">
            <div class="display-4 text-primary mb-3">{{ $activeUsers }}</div>
            <p class="text-muted">Active users in the last week</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const colors = {
    primary: '#6366f1',
    secondary: '#8b5cf6',
    success: '#10b981',
    warning: '#f59e0b',
    danger: '#ef4444',
    info: '#06b6d4'
};

// User Activity by Hour
const hourlyData = @json($hourlyData);
const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
const userActivityChart = new Chart(userActivityCtx, {
    type: 'bar',
    data: {
        labels: hourlyData.map(d => d.hour),
        datasets: [{
            label: 'Visitors',
            data: hourlyData.map(d => d.visitors),
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

// Device Chart
const deviceData = @json($deviceData);
const deviceCtx = document.getElementById('deviceChart').getContext('2d');
const deviceChart = new Chart(deviceCtx, {
    type: 'doughnut',
    data: {
        labels: deviceData.map(d => d.device),
        datasets: [{
            data: deviceData.map(d => d.percentage),
            backgroundColor: [colors.primary, colors.success, colors.warning],
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

window.addEventListener('resize', function() {
    userActivityChart.resize();
    deviceChart.resize();
});
</script>
@endsection
