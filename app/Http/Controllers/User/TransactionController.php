<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class TransactionController extends Controller
{
    public function showTicket($id)
    {
        // Pastikan tiket milik user yang sedang login
        $ticket = Ticket::with(['transaction', 'event'])
            ->whereHas('transaction', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        return view('user.ticket', compact('ticket'));
    }
    // Menampilkan Dashboard User (Keranjang & Riwayat)
    public function dashboard()
    {
        $carts = Cart::with('event')->where('user_id', Auth::id())->get();
        
        // Hitung total harga di keranjang
        $totalCartPrice = $carts->sum(function($cart) {
            return $cart->qty * $cart->event->price;
        });

        // Ambil riwayat transaksi yang sudah dibuat
        $transactions = Transaction::where('user_id', Auth::id())->latest()->get();

        return view('user.dashboard', compact('carts', 'totalCartPrice', 'transactions'));
    }

    // Proses Checkout & Dapatkan Snap Token Midtrans
    public function checkout(Request $request)
    {
        $carts = Cart::with('event')->where('user_id', Auth::id())->get();

        if ($carts->isEmpty()) {
            return back()->withErrors(['pesan' => 'Keranjang belanja Anda kosong.']);
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;

            // 1. Validasi Stok & Hitung Total
            foreach ($carts as $cart) {
                if ($cart->event->stock < $cart->qty) {
                    throw new Exception("Stok tiket untuk event {$cart->event->title} tidak mencukupi.");
                }
                $totalAmount += ($cart->event->price * $cart->qty);
            }

            // 2. Buat ID Order Unik
            $orderId = 'TRX-' . time() . '-' . Auth::id();

            // 3. Simpan ke Tabel Transactions (Status: pending)
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'order_id' => $orderId,
                'total_amount' => $totalAmount,
                'status' => 'pending'
            ]);

            // 4. Kurangi Stok Event Secara Langsung
            foreach ($carts as $cart) {
                $cart->event->decrement('stock', $cart->qty);
                
                // Pindahkan data Cart ke Ticket agar terikat ke transaksi ini.
                // Status tiket diatur ke 'valid', namun baru bisa digunakan jika status transaksi 'paid'.
                for ($i = 0; $i < $cart->qty; $i++) {
                    Ticket::create([
                        'transaction_id' => $transaction->id,
                        'event_id' => $cart->event_id,
                        'ticket_code' => strtoupper(uniqid('TIX-')), 
                    ]);
                }
            }

            // 5. Hapus isi Cart setelah dipindahkan ke transaksi
            Cart::where('user_id', Auth::id())->delete();

            // 6. Setup Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
            ];
            
            // 7. Dapatkan Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            DB::commit();

            // Redirect kembali ke dashboard dengan membawa snap token
            return redirect()->route('user.dashboard')->with('snap_token', $snapToken);

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['pesan' => 'Gagal memproses checkout: ' . $e->getMessage()]);
        }
    }
}