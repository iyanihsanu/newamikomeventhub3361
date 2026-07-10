@extends('layouts.app')
@section('title', 'Temukan Event Terbaik')

@section('content')
<!-- Hero & Filter Section -->
<section class="hero-section py-5 mb-5">
    <div class="container text-center py-4">
        <h1 class="display-5 fw-bold mb-3">Temukan Pengalaman Baru</h1>
        <p class="text-secondary mb-5 fs-5">Pesan tiket konser, seminar, dan olahraga dengan mudah.</p>

        <!-- Filter Form -->
        <div class="card shadow-sm border-0 rounded-4 p-2 mx-auto" style="max-width: 900px;">
            <form action="{{ route('home') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-lg border-0 shadow-none bg-light" placeholder="Cari nama event..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select form-select-lg border-0 shadow-none bg-light">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="location" class="form-control form-control-lg border-0 shadow-none bg-light" placeholder="Lokasi..." value="{{ request('location') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-dark btn-lg rounded-3">Cari</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Event List Section -->
<section class="container">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h3 class="fw-bold mb-0">Event Mendatang</h3>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @forelse($events as $event)
            <div class="col">
                <div class="card h-100 event-card rounded-4 overflow-hidden border-0 bg-white">
                    <img src="{{ asset($event->image) }}" class="card-img-top object-fit-cover" alt="{{ $event->title }}" style="height: 220px;">
                    <div class="card-body p-4">
                        <span class="badge bg-light text-dark mb-2 px-2 py-1 border">{{ $event->category->name ?? 'Umum' }}</span>
                        <h5 class="card-title fw-bold mb-3">{{ $event->title }}</h5>
                        
                        <div class="d-flex align-items-center mb-2 text-secondary small">
                            <i class="bi bi-calendar3 me-2"></i> 
                            {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y • H:i') }}
                        </div>
                        <div class="d-flex align-items-center mb-3 text-secondary small">
                            <i class="bi bi-geo-alt me-2"></i> {{ $event->location }}
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                            <div>
                                <span class="d-block small text-muted">Mulai dari</span>
                                <span class="fw-bold text-dark fs-5">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                            </div>
                            <!-- Rute ini akan kita buat di tahap selanjutnya -->
                           <a href="{{ route('event.show', $event->id) }}" class="btn btn-outline-dark rounded-pill px-4">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted fs-5">Tidak ada event yang ditemukan.</div>
                <a href="{{ route('home') }}" class="btn btn-link mt-2">Reset Filter</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $events->links() }}
    </div>
</section>
@endsection