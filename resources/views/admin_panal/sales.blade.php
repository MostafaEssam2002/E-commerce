@extends('admin_panal.master')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="mdi mdi-cash-multiple"></i>
        </div>
        <div class="stat-title">Total Sales</div>
        <div class="stat-value">${{ number_format($totalSales, 2) }}</div>
        @if($salesChange > 0)
            <div class="stat-change positive">
                <i class="mdi mdi-arrow-up"></i>
                <span>+{{ number_format($salesChange, 2) }}% from last month</span>
            </div>
        @else
            <div class="stat-change negative">
                <i class="mdi mdi-arrow-down"></i>
                <span>{{ number_format($salesChange, 2) }}% from last month</span>
            </div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="mdi mdi-cart-check"></i>
        </div>
        <div class="stat-title">Completed Orders</div>
        <div class="stat-value">{{ number_format($completedOrders) }}</div>
        @if($ordersChange > 0)
            <div class="stat-change positive">
                <i class="mdi mdi-arrow-up"></i>
                <span>+{{ number_format($ordersChange, 2) }}% from last month</span>
            </div>
        @else
            <div class="stat-change negative">
                <i class="mdi mdi-arrow-down"></i>
                <span>{{ number_format($ordersChange, 2) }}% from last month</span>
            </div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="mdi mdi-currency-usd"></i>
        </div>
        <div class="stat-title">Average Order Value</div>
        <div class="stat-value">${{ number_format($avgOrderValue, 2) }}</div>
        <div class="stat-change positive">
            <i class="mdi mdi-arrow-up"></i>
            <span>+3.2% from last month</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="mdi mdi-clock-alert"></i>
        </div>
        <div class="stat-title">Pending Orders</div>
        <div class="stat-value">{{ number_format($pendingOrders) }}</div>
        <div class="stat-change negative">
            <i class="mdi mdi-arrow-down"></i>
            <span>-1.8% from last month</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="chart-grid">
    <!-- Sales Trend Chart -->
    <div class="chart-card" style="grid-column: span 2;">
        <div class="chart-header">
            <h3 class="chart-title">Sales Trend</h3>
            <p class="chart-subtitle">Daily sales for the last 30 days</p>
        </div>
        <canvas id="salesTrendChart"></canvas>
    </div>
</div>

<div class="chart-grid mt-4">
    <!-- Top Products Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Top Selling Products</h3>
            <p class="chart-subtitle">By revenue</p>
        </div>
        <canvas id="topProductsChart"></canvas>
    </div>

    <!-- Sales by Category -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Sales by Category</h3>
            <p class="chart-subtitle">Distribution of sales</p>
        </div>
        <canvas id="salesByCategoryChart"></canvas>
    </div>
</div>

<!-- Recent Orders Table with Sorting -->
<div class="chart-card mt-4">
    <div class="chart-header">
        <h3 class="chart-title">Recent Orders</h3>
        <p class="chart-subtitle">Latest transactions</p>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="ordersTable">
            <thead>
                <tr>
                    <th class="sortable" data-column="id">
                        Order ID
                        <i class="mdi mdi-chevron-up sort-icon"></i>
                    </th>
                    <th class="sortable" data-column="customer">
                        Customer
                        <i class="mdi mdi-chevron-up sort-icon"></i>
                    </th>
                    <th>Product</th>
                    <th class="sortable" data-column="date">
                        Date
                        <i class="mdi mdi-chevron-up sort-icon"></i>
                    </th>
                    <th class="text-end sortable" data-column="amount">
                        Amount
                        <i class="mdi mdi-chevron-up sort-icon"></i>
                    </th>
                    <th class="sortable" data-column="status">
                        Status
                        <i class="mdi mdi-chevron-up sort-icon"></i>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td data-value="{{ $order->id }}"><strong>#{{ $order->id }}</strong></td>
                    <td data-value="{{ $order->user->name ?? 'Guest' }}">{{ $order->user->name ?? 'Guest' }}</td>
                    <td>{{ $order->orderDetails->first()->product->name ?? 'Multiple Items' }}</td>
                    <td data-value="{{ $order->created_at->timestamp }}">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="text-end" data-value="{{ $order->total_amount }}">${{ number_format($order->total_amount, 2) }}</td>
                    <td data-value="{{ $order->status }}">
                        @if($order->status == 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($order->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($order->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-info">Processing</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No orders available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Top Customers -->
<div class="chart-grid mt-4">
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Top Customers</h3>
            <p class="chart-subtitle">By total purchases</p>
        </div>
        <div class="p-3">
            @forelse($topCustomers as $customer)
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="user-avatar me-3" style="width:40px; height:40px; overflow:hidden; border-radius:50%;">
                        <img src="{{ asset($customer->avatar ?? 'assets/images/default-avatar.png') }}"
                             alt="{{ $customer->name }}"
                             style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div>
                        <div class="fw-bold">{{ $customer->name }}</div>
                        <small class="text-muted">{{ $customer->orders_count }} orders</small>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary">${{ number_format($customer->total_amount, 2) }}</div>
                </div>
            </div>
            @empty
            <p class="text-center text-muted">No customer data available</p>
            @endforelse
        </div>
    </div>

    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Sales Performance</h3>
            <p class="chart-subtitle">Monthly comparison</p>
        </div>
        <canvas id="performanceChart"></canvas>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Table Sorting Styles */
.sortable {
    cursor: pointer;
    user-select: none;
    position: relative;
    padding-right: 25px !important;
    transition: all 0.3s ease;
}

.sortable:hover {
    background-color: rgba(99, 102, 241, 0.1);
}

.sort-icon {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    color: #ccc;
    transition: all 0.3s ease;
}

.sortable.asc .sort-icon {
    color: #6366f1;
    transform: translateY(-50%) rotate(0deg);
}

.sortable.desc .sort-icon {
    color: #6366f1;
    transform: translateY(-50%) rotate(180deg);
}

/* Table Animation */
tbody tr {
    transition: all 0.3s ease;
}

tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.05);
    transform: translateX(5px);
}
</style>
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

// Sales Trend Chart
const salesTrendData = @json($dailySales);
const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
const salesTrendChart = new Chart(salesTrendCtx, {
    type: 'line',
    data: {
        labels: salesTrendData.map(d => d.date),
        datasets: [{
            label: 'Daily Sales',
            data: salesTrendData.map(d => d.amount),
            borderColor: colors.primary,
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: colors.primary,
            pointBorderColor: '#fff',
            pointBorderWidth: 2
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
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return 'Sales: $' + context.parsed.y.toLocaleString();
                    }
                }
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
                grid: { display: false }
            }
        }
    }
});

// Top Products Chart
const topProductsData = @json($topProducts);
const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
const topProductsChart = new Chart(topProductsCtx, {
    type: 'bar',
    data: {
        labels: topProductsData.map(p => p.name),
        datasets: [{
            label: 'Revenue',
            data: topProductsData.map(p => p.revenue),
            backgroundColor: (context) => {
                const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, colors.success);
                gradient.addColorStop(1, colors.primary);
                return gradient;
            },
            borderRadius: 8,
            hoverBackgroundColor: colors.success
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                cornerRadius: 8,
                callbacks: {
                    label: function(context) {
                        return 'Revenue: $' + context.parsed.x.toLocaleString();
                    }
                }
            }
        },
        scales: {
            x: {
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
            y: {
                grid: { display: false }
            }
        }
    }
});

// Sales by Category Chart
const categoryData = @json($categoryData);
const categoryCtx = document.getElementById('salesByCategoryChart').getContext('2d');
const categoryChart = new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryData.map(c => c.name),
        datasets: [{
            data: categoryData.map(c => c.value),
            backgroundColor: [
                colors.primary,
                colors.success,
                colors.warning,
                colors.danger,
                colors.info
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
                        return context.label + ': $' + context.parsed.toLocaleString();
                    }
                }
            }
        },
        cutout: '65%'
    }
});

// Performance Chart
const performanceData = @json($monthlyPerformance);
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(performanceCtx, {
    type: 'bar',
    data: {
        labels: performanceData.map(m => m.month),
        datasets: [{
            label: 'Sales',
            data: performanceData.map(m => m.sales),
            backgroundColor: colors.primary,
            borderRadius: 6
        }, {
            label: 'Target',
            data: performanceData.map(m => m.target),
            backgroundColor: colors.warning,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15
                }
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
                grid: { display: false }
            }
        }
    }
});

// ============ Table Sorting Functionality ============
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('ordersTable');
    const headers = table.querySelectorAll('.sortable');

    headers.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.column;
            const currentSort = this.classList.contains('asc') ? 'asc' :
                              this.classList.contains('desc') ? 'desc' : 'none';

            let newSort = 'asc';
            if (currentSort === 'asc') newSort = 'desc';
            else if (currentSort === 'desc') newSort = 'asc';

            // إزالة كل classes الترتيب من Headers
            headers.forEach(h => h.classList.remove('asc', 'desc'));

            // إضافة class الترتيب الجديد
            this.classList.add(newSort);

            // ترتيب الصفوف
            sortTable(column, newSort);
        });
    });

    function sortTable(column, direction) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // إزالة صف "No data"
        const noDataRow = rows.find(row => row.cells.length === 1);
        if (noDataRow) return;

        rows.sort((a, b) => {
            let aValue, bValue;

            switch(column) {
                case 'id':
                    aValue = parseInt(a.cells[0].dataset.value);
                    bValue = parseInt(b.cells[0].dataset.value);
                    break;
                case 'customer':
                    aValue = a.cells[1].dataset.value.toLowerCase();
                    bValue = b.cells[1].dataset.value.toLowerCase();
                    break;
                case 'date':
                    aValue = parseInt(a.cells[3].dataset.value);
                    bValue = parseInt(b.cells[3].dataset.value);
                    break;
                case 'amount':
                    aValue = parseFloat(a.cells[4].dataset.value);
                    bValue = parseFloat(b.cells[4].dataset.value);
                    break;
                case 'status':
                    aValue = a.cells[5].dataset.value.toLowerCase();
                    bValue = b.cells[5].dataset.value.toLowerCase();
                    break;
                default:
                    return 0;
            }

            if (aValue < bValue) return direction === 'asc' ? -1 : 1;
            if (aValue > bValue) return direction === 'asc' ? 1 : -1;
            return 0;
        });

        // مسح الـ tbody وإعادة إضافة الصفوف المرتبة
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    }
});

// Resize charts on window resize
window.addEventListener('resize', function() {
    salesTrendChart.resize();
    topProductsChart.resize();
    categoryChart.resize();
    performanceChart.resize();
});
</script>
@endsection
