@extends('admin_panal.master')

@section('content')
<div class="page-header mb-4">
    <h2>Reports</h2>
    <p class="text-muted">Overview of sales, orders, and customer performance.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="mdi mdi-cash-multiple"></i>
        </div>
        <div class="stat-title">Total Sales</div>
        <div class="stat-value">${{ number_format($totalSales, 2) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="mdi mdi-cart-check"></i>
        </div>
        <div class="stat-title">Completed Orders</div>
        <div class="stat-value">{{ number_format($completedOrders) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="mdi mdi-currency-usd"></i>
        </div>
        <div class="stat-title">Average Order Value</div>
        <div class="stat-value">${{ number_format($avgOrderValue, 2) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <i class="mdi mdi-clock-alert"></i>
        </div>
        <div class="stat-title">Pending Orders</div>
        <div class="stat-value">{{ number_format($pendingOrders) }}</div>
    </div>
</div>

<div class="chart-grid mt-4">
    <div class="chart-card" style="grid-column: span 2;">
        <div class="chart-header">
            <h3 class="chart-title">Top Products</h3>
            <p class="chart-subtitle">Highest revenue products</p>
        </div>
        <div class="p-3">
            @forelse($topProducts as $product)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <div class="fw-bold">{{ $product->name }}</div>
                        <small class="text-muted">Sold {{ number_format($product->total_sold) }} items</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-primary">${{ number_format($product->revenue, 2) }}</div>
                    </div>
                </div>
            @empty
                <p class="text-center text-muted">No product sales data available</p>
            @endforelse
        </div>
    </div>

    <div class="chart-card" style="grid-column: span 2;">
        <div class="chart-header">
            <h3 class="chart-title">Top Customers</h3>
            <p class="chart-subtitle">Best customers by spending</p>
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
                <p class="text-center text-muted">No customer report data available</p>
            @endforelse
        </div>
    </div>
</div>

<div class="chart-card mt-4">
    <div class="chart-header">
        <h3 class="chart-title">Recent Orders</h3>
        <p class="chart-subtitle">Last 10 order transactions</p>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
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
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No recent orders available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
