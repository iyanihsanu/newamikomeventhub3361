<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - EventHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #fcfcfc; font-family: 'Segoe UI', system-ui, sans-serif; }
        .navbar { background-color: #ffffff; border-bottom: 1px solid #eaeaea; }
        .event-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border: 1px solid #f0f0f0; }
        .event-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .hero-section { background-color: #f8f9fa; border-bottom: 1px solid #eaeaea; }
    </style>
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">EventHub.</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
                    
                    @guest
                        <li class="nav-item ms-lg-3"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item ms-1"><a class="btn btn-dark rounded-pill px-4" href="{{ route('register') }}">Daftar</a></li>
                    @else
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item ms-lg-3"><a class="btn btn-outline-dark rounded-pill px-4" href="{{ route('admin.dashboard') }}">Dasbor Admin</a></li>
                        @else
                            <li class="nav-item ms-lg-3"><a class="btn btn-outline-dark rounded-pill px-4" href="{{ route('user.dashboard') }}">Tiket Saya</a></li>
                        @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="min-vh-100">
        @yield('content')
    </main>

    <footer class="bg-white py-4 border-top mt-5 text-center text-muted">
        <div class="container">
            <small>&copy; {{ date('Y') }} EventHub. Hak cipta dilindungi.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>