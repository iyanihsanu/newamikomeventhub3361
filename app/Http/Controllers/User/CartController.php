<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request, $eventId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $event = Event::findOrFail($eventId);

        // Validasi 1: Apakah input melebihi sisa stok di database?
        if ($request->qty > $event->stock) {
            return back()->withErrors(['qty' => 'Kuantitas melebihi stok tiket yang tersedia.']);
        }

        // Cek apakah event ini sudah ada di keranjang user
        $cart = Cart::where('user_id', Auth::id())->where('event_id', $eventId)->first();

        if ($cart) {
            // Validasi 2: Apakah akumulasi di keranjang + input baru melebihi stok?
            $newQty = $cart->qty + $request->qty;
            if ($newQty > $event->stock) {
                return back()->withErrors(['qty' => 'Total tiket di keranjang Anda melebihi stok tersedia.']);
            }
            
            $cart->update(['qty' => $newQty]);
        } else {
            // Buat record keranjang baru
            Cart::create([
                'user_id' => Auth::id(),
                'event_id' => $eventId,
                'qty' => $request->qty
            ]);
        }

        // Arahkan ke dashboard user setelah berhasil menambah keranjang
        return redirect()->route('user.dashboard')->with('success', 'Tiket berhasil ditambahkan ke keranjang!');
    }
}