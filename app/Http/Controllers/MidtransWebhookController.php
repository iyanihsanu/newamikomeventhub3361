<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Ambil payload dari Midtrans
        $payload = $request->all();

        // (Opsional) Catat log untuk keperluan debugging
        Log::info('Midtrans Webhook:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 2. Verifikasi Signature Key untuk Keamanan
        // Rumus SHA512: order_id + status_code + gross_amount + server_key
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $calculatedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($calculatedSignature !== $signatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // 3. Cari Transaksi di Database
        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Jika transaksi sudah pernah diproses (misal Midtrans kirim notif 2 kali), hentikan proses
        if (in_array($transaction->status, ['paid', 'expired', 'failed'])) {
            return response()->json(['message' => 'Transaction already processed'], 200);
        }

        // 4. Proses Perubahan Status & Stok menggunakan Database Transaction
        DB::beginTransaction();
        try {
            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                // Pembayaran Berhasil
                $transaction->update(['status' => 'paid']);
                
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                // Pembayaran Gagal / Kedaluwarsa
                $transaction->update(['status' => 'expired']);

                // Mengembalikan stok tiket (Restock)
                $tickets = Ticket::where('transaction_id', $transaction->id)->get();
                
                // Kelompokkan tiket berdasarkan event (berjaga-jaga jika 1 transaksi ada multi-event)
                $groupedTickets = $tickets->groupBy('event_id');
                
                foreach ($groupedTickets as $eventId => $eventTickets) {
                    $qty = $eventTickets->count();
                    // Kembalikan stok ke tabel event
                    Event::where('id', $eventId)->increment('stock', $qty);
                }

                // Hapus data tiket yang transaksinya gagal agar tidak menjadi sampah data
                Ticket::where('transaction_id', $transaction->id)->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Webhook success']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}