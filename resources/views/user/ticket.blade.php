@extends('layouts.app')
@section('title', 'E-Tiket Anda')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Header Tiket -->
                <div class="bg-dark text-white p-4 text-center">
                    <h4 class="mb-0">E-TICKET EVENT</h4>
                    <small class="text-secondary">{{ $ticket->ticket_code }}</small>
                </div>

                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">{{ $ticket->event->title }}</h5>
                    
                    <!-- QR Code Render -->
                    <div class="my-4">
                        {!! QrCode::size(200)->generate($ticket->ticket_code) !!}
                    </div>

                    <div class="row text-start mt-4 border-top pt-3">
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Tanggal</small>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d M Y') }}</span>
                        </div>
                        <div class="col-6 mb-3">
                            <small class="text-muted d-block">Waktu</small>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('H:i') }} WIB</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Lokasi</small>
                            <span class="fw-medium">{{ $ticket->event->location }}</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light p-3 text-center border-0">
                    <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4">
                        <i class="bi bi-printer me-2"></i> Cetak Tiket
                    </button>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('user.dashboard') }}" class="text-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>
@endsection