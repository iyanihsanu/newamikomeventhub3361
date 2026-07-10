@extends('layouts.app')
@section('title', 'Dashboard Saya')

@section('content')
    <div class="container py-5">
        <h3 class="fw-bold mb-4">Dashboard Tiket Saya</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first('pesan') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- Kolom Kiri: Keranjang Belanja -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i> Keranjang Belanja</h5>

                        @if ($carts->count() > 0)
                            @foreach ($carts as $cart)
                                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($cart->event->image) }}" class="rounded-3 object-fit-cover me-3"
                                            style="width: 80px; height: 80px;" alt="{{ $cart->event->title }}">
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $cart->event->title }}</h6>
                                            <span class="text-secondary small">{{ $cart->qty }} Tiket x Rp
                                                {{ number_format($cart->event->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="fw-bold fs-5">
                                        Rp {{ number_format($cart->event->price * $cart->qty, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fs-5 text-secondary">Total Tagihan:</span>
                                <span class="fs-3 fw-bold">Rp {{ number_format($totalCartPrice, 0, ',', '.') }}</span>
                            </div>

                            <form action="{{ route('user.checkout') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="btn btn-dark btn-lg w-100 rounded-pill fw-bold">Lanjutkan ke
                                    Pembayaran</button>
                            </form>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-bag-x fs-1 d-block mb-2"></i>
                                Keranjang Anda masih kosong.<br>
                                <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill mt-3">Cari Event</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Status Transaksi & Tombol Midtrans -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 bg-light">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i> Transaksi Terakhir</h5>

                        @forelse($transactions as $trx)
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-muted">{{ $trx->order_id }}</span>
                                        <span
                                            class="badge {{ $trx->status == 'paid' ? 'bg-success' : ($trx->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ strtoupper($trx->status) }}
                                        </span>
                                    </div>
                                    <div class="fw-bold fs-5 mb-3">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </div>

                                    <!-- Jika status pending dan ada snap token, tampilkan tombol Bayar Sekarang -->
                                    @if ($trx->status === 'pending' && $trx->snap_token)
                                        <button class="btn btn-primary w-100 fw-bold pay-button"
                                            data-token="{{ $trx->snap_token }}">Bayar Sekarang</button>
                                    @endif

                                    @if ($trx->status === 'paid')
                                        <!-- Kita arahkan ke halaman list tiket atau langsung ke detail jika hanya ada 1 -->
                                        <a href="{{ route('user.ticket.show', $trx->tickets->first()->id) }}"
                                            class="btn btn-success w-100 fw-bold">Lihat E-Tiket</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada riwayat transaksi.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mengambil Client Key Midtrans dari file env -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua tombol dengan class pay-button
            const payButtons = document.querySelectorAll('.pay-button');

            payButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const snapToken = this.getAttribute('data-token');

                    // Memicu jendela popup Midtrans
                    window.snap.pay(snapToken, {
                        onSuccess: function(result) {
                            alert("Pembayaran berhasil!");
                            window.location.reload();
                        },
                        onPending: function(result) {
                            alert("Menunggu pembayaran Anda!");
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal!");
                            window.location.reload();
                        },
                        onClose: function() {
                            alert(
                                'Anda menutup jendela pembayaran tanpa menyelesaikan transaksi.');
                        }
                    });
                });
            });

            // Trigger otomatis jika user baru saja sukses checkout
            @if (session('snap_token'))
                window.snap.pay('{{ session('
                            snap_token ') }}');
            @endif
        });
    </script>
@endsection
