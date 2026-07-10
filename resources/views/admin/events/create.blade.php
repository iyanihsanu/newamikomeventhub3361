@extends('layouts.admin')
@section('title', 'Tambah Event Baru')
@section('header', 'Tambah Event Baru')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        
        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label fw-medium">Nama Event <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" placeholder="Contoh: Konser Musik Indie 2026">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label fw-medium">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label fw-medium">Deskripsi Event <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Jelaskan detail event, lineup, atau syarat dan ketentuan...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Kolom Kanan / Bawah -->
                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label fw-medium">Lokasi / Venue <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Stadion Utama Gelora Bung Karno">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="event_date" class="form-label fw-medium">Tanggal & Waktu Event <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date') }}">
                    @error('event_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label fw-medium">Harga Tiket (Rp) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" placeholder="0">
                    <small class="text-muted">Isi 0 jika event gratis.</small>
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="stock" class="form-label fw-medium">Kapasitas / Stok Tiket <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" placeholder="Contoh: 1000">
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="image" class="form-label fw-medium">Poster Event <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg, image/png, image/jpg">
                    <small class="text-muted">Format: JPG/PNG, Maks: 2MB.</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.events.index') }}" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Event</button>
            </div>
        </form>

    </div>
</div>
@endsection