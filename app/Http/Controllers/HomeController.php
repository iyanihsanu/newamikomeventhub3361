<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query, hanya ambil event yang stoknya masih ada
        $query = Event::with('category')->where('stock', '>', 0);

        // Filter Pencarian (Nama Event)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter Lokasi
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Ambil data, urutkan berdasarkan tanggal event terdekat
        $events = $query->orderBy('event_date', 'asc')->paginate(9);
        $categories = Category::all();

        return view('home', compact('events', 'categories'));

    }
    // Tambahkan metode ini di bawah metode index()
    public function show($id)
    {
        // Ambil data event atau tampilkan 404 jika tidak ditemukan
        $event = Event::with('category')->findOrFail($id);
        
        return view('event.show', compact('event'));
    }
}

