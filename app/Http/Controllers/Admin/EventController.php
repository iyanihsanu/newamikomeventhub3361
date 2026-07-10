<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        // Mengambil data event beserta relasi kategorinya
        $events = Event::with('category')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        // Membutuhkan data kategori untuk dropdown form
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title'       => 'required|string|max:255',
            'description' => 'required',
            'location'    => 'required|string',
            'event_date'  => 'required|date',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:1',
            'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();

        // Logika upload gambar poster event
        if ($request->hasFile('image')) {
            $imageName = time() . '-' . Str::slug($request->title) . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/events'), $imageName);
            $data['image'] = 'uploads/events/' . $imageName;
        }

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }
    
    // ... method edit, update, destroy dapat Anda lengkapi dengan pola serupa
}