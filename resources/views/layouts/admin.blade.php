<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin EventHub</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #2E0249; /* Dark Purple */
            min-height: 100vh;
            width: 250px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: #d1c4e9;
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            border-radius: 6px;
            margin: 4px 12px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #57367B;
            color: #ffffff;
        }
        .main-content {
            flex: 1;
            background-color: #f4f6f9;
        }
    </style>
</head>
<body class="d-flex">

    <!-- Sidebar -->
    <aside class="sidebar py-3">
        <h4 class="text-white text-center fw-bold mb-4">EventHub Admin</h4>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
            <a href="{{ route('admin.events.index') }}"><i class="bi bi-calendar-event me-2"></i> Kelola Event</a>
            <a href="#"><i class="bi bi-tags me-2"></i> Kategori</a>
            <a href="#"><i class="bi bi-people me-2"></i> Pengguna</a>
            <a href="#"><i class="bi bi-receipt me-2"></i> Laporan Transaksi</a>
        </nav>
        
        <div class="position-absolute bottom-0 w-100 mb-3 px-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <header class="bg-white shadow-sm p-3 mb-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">@yield('header', 'Dashboard')</h5>
            <div class="dropdown">
                <span class="text-dark fw-medium">Halo, {{ Auth::user()->name }}</span>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="container-fluid px-4">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>