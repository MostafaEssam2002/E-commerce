<!DOCTYPE html>
<html lang="en">
    {{-- 24 --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Modern Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/dashboard.css')}}">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3><i class="mdi mdi-view-dashboard"></i> Dashboard</h3>
            </div>
            <div class="sidebar-menu">
                <a href="/" class="menu-item active">
                    <i class="mdi mdi-home"></i>
                    <span>Home</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="mdi mdi-chart-line"></i>
                    <span>Analytics</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="mdi mdi-account-multiple"></i>
                    <span>Users</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="mdi mdi-shopping"></i>
                    <span>Sales</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="mdi mdi-file-document"></i>
                    <span>Reports</span>
                </a>
                <a href="#" class="menu-item">
                    <i class="mdi mdi-cog"></i>
                    <span>Settings</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navbar -->
            <nav class="top-navbar">
                <div class="welcome-text">
                    Welcome, <span style="color: var(--primary-color);">{{Auth::user()->name}}</span>
                </div>
                <div class="user-info">
                    <div class="notification-icon">
                        <i class="mdi mdi-bell"></i>
                        <span class="notification-badge">5</span>
                    </div>
                    <div class="user-avatar" id="useravater" style="width:40px; height:40px; overflow:hidden; border-radius:50%;">
                        <img src="{{ asset(Auth::user()->avatar) }}" alt="User Avatar" style="width:100%; height:100%; object-fit:cover;">
                    </div>

                </div>
            </nav>

            <!-- Content Area -->
            <div class="content-area">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    @yield('scripts')
</body>
</html>
