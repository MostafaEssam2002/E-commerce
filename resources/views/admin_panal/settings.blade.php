@extends('admin_panal.master')

@section('content')
<div class="page-header mb-4">
    <h2>Settings</h2>
    <p class="text-muted">Manage your admin panel preferences and store configuration.</p>
</div>

@if(session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<div class="row gy-4">
    <div class="col-lg-6">
        <div class="chart-card p-4">
            <h3 class="chart-title mb-3">Account Settings</h3>
            <p class="text-muted mb-4">Update your profile details and account preferences.</p>
            <form>
                <div class="mb-3">
                    <label class="form-label">Admin Name</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" value="********" readonly>
                </div>
                <button type="button" class="btn btn-primary">Edit Profile</button>
            </form>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="chart-card p-4">
            <h3 class="chart-title mb-3">Store Settings</h3>
            <p class="text-muted mb-4">Control your store status and notification options.</p>
            <div class="mb-3">
                <label class="form-label d-block">Store Status</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="storeStatus" checked>
                    <label class="form-check-label" for="storeStatus">Open for orders</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label d-block">Maintenance Mode</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="maintenanceMode">
                    <label class="form-check-label" for="maintenanceMode">Enable maintenance mode</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Notification Email</label>
                <input type="email" class="form-control" value="admin@example.com">
            </div>
            <button type="button" class="btn btn-secondary">Save Settings</button>
        </div>
    </div>
</div>

<div class="chart-card p-4 mt-4">
    <h3 class="chart-title mb-3">Reporting Month</h3>
    <p class="text-muted mb-4">Choose a month to apply to admin statistics and reports.</p>
    <form action="{{ route('settings.update_month') }}" method="POST">
        @csrf
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Select Month</label>
                <select name="selected_month" class="form-select">
                    @foreach([1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'] as $value => $label)
                        <option value="{{ $value }}" {{ isset($selectedMonth) && $selectedMonth == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Year</label>
                <input type="text" class="form-control" value="{{ $selectedYear ?? now()->year }}" readonly>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Save Reporting Month</button>
            </div>
        </div>
    </form>
</div>

<div class="chart-card p-4 mt-4">
    <h3 class="chart-title mb-3">Quick Preferences</h3>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 border rounded bg-white">
                <h6>Currency</h6>
                <p class="text-muted">USD</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded bg-white">
                <h6>Theme</h6>
                <p class="text-muted">Light mode</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 border rounded bg-white">
                <h6>Notifications</h6>
                <p class="text-muted">Enabled</p>
            </div>
        </div>
    </div>
</div>
@endsection
