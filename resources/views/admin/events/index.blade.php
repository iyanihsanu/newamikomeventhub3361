@extends('layouts.admin')
@section('title', 'Kelola Event')
@section('header', 'Data Event')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="card-title fw-bold">Daftar Event Tersedia</h5>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Event</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Poster</th>
                        <th>Nama Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <img src="{{ asset($event->image) }}" alt="Poster" width="60" class="rounded">
                        </td>
                        <td class="fw-medium">{{ $event->title }}</td>
                        <td>{{ $event->category->name ?? 'Tanpa Kategori' }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y, H:i') }}</td>
                        <td>Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $event->stock > 10 ? 'bg-success' : 'bg-danger' }}">
                                {{ $event->stock }} Tiket
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="#" class="btn btn-sm btn-warning text-white"><i class="bi bi-pencil"></i></a>
                            <form action="#" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus event ini?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection