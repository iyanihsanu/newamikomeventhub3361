<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\MidtransWebhookController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{id}', [HomeController::class, 'show'])->name('event.show');
Route::get('/ticket/{id}', [TransactionController::class, 'showTicket'])->name('ticket.show');

// Midtrans Webhook
Route::post('/webhook/midtrans', [MidtransWebhookController::class, 'handle']);

// Auth Routes (Hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout (Harus login terlebih dahulu)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Area khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return "Ini adalah Halaman Admin. Layout sidebar gelap akan diterapkan di sini.";
    })->name('admin.dashboard');
});

// Area khusus User (Pembeli)
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Tampilkan Dashboard & Cart
    Route::get('/dashboard', [TransactionController::class, 'dashboard'])->name('dashboard');
    
    // Rute Submit Keranjang dari Halaman Detail (Yang kita buat di langkah sebelumnya)
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.store');

    // Rute Proses Checkout (Ke Midtrans)
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
});

// Area khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.admin'); // Sementara memanggil layout agar bisa dilihat
    })->name('dashboard');
    
    // Rute CRUD Event
    Route::resource('events', EventController::class);
});