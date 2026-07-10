@extends('layouts.app')
@section('title', $event->title)

@section('content')
<div class="container py-5">
    
    <!-- Tombol Kembali -->
    <a href="{{ route('home') }}" class="text-decoration-none text-secondary mb-4 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
    </a>

    <div class="row g-5">
        <!-- Kolom Kiri: Poster Event -->
        <div class="col-lg-7">
            <img src="{{ asset($event->image) }}" class="img-fluid rounded-4 shadow-sm w-100 object-fit-cover" alt="{{ $event->title }}" style="max-height: 500px;">
            
            <div class="mt-5">
                <h4 class="fw-bold mb-3">Deskripsi Event</h4>
                <p class="text-secondary lh-lg" style="white-space: pre-line;">{{ $event->description }}</p>
            </div>
        </div>

        <!-- Kolom Kanan: Detail & Form Pembelian -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                <div class="card-body p-4 p-xl-5">
                    
                    <span class="badge bg-light text-dark mb-3 px-3 py-2 border rounded-pill">{{ $event->category->name ?? 'Umum' }}</span>
                    <h2 class="fw-bold mb-4">{{ $event->title }}</h2>

                    <div class="d-flex align-items-center mb-3 text-secondary">
                        <i class="bi bi-calendar-event fs-5 me-3 text-dark"></i> 
                        <div>
                            <div class="fw-medium text-dark">Tanggal & Waktu</div>
                            {{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y - H:i') }}
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4 text-secondary">
                        <i class="bi bi-geo-alt fs-5 me-3 text-dark"></i> 
                        <div>
                            <div class="fw-medium text-dark">Lokasi</div>
                            {{ $event->location }}
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-secondary">Harga Tiket</span>
                        <span class="fs-3 fw-bold text-dark">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-secondary">Sisa Stok</span>
                        <span class="fw-medium {{ $event->stock < 10 ? 'text-danger' : 'text-success' }}">{{ $event->stock }} tiket</span>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger py-2">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li><small>{{ $error }}</small></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($event->stock > 0)
                        <!-- Form Tambah ke Keranjang -->
                        <form action="{{ route('user.cart.store', $event->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="qty" class="form-label fw-medium">Jumlah Tiket</label>
                                <select name="qty" id="qty" class="form-select form-select-lg shadow-none">
                                    @for($i = 1; $i <= min($event->stock, 5); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <small class="text-muted d-block mt-1">Maksimal pembelian 5 tiket per transaksi.</small>
                            </div>

                            @auth
                                @if(Auth::user()->role === 'user')
                                    <button type="submit" class="btn btn-dark btn-lg w-100 rounded-3 fw-bold py-3">Beli Tiket</button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-lg w-100 rounded-3 py-3" disabled>Admin Tidak Dapat Membeli</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-dark btn-lg w-100 rounded-3 fw-bold py-3">Login untuk Membeli</a>
                            @endauth
                        </form>
                    @else
                        <button type="button" class="btn btn-danger btn-lg w-100 rounded-3 py-3 fw-bold" disabled>Tiket Habis Terjual</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection