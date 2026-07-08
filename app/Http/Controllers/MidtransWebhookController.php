<?php

namespace App\Http\Controllers;

use App\Mail\EventTicketMail;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json([
                'message' => 'Invalid payload'
            ], 400);
        }

        // Mencari transaksi berdasarkan Order ID
        $transaction = Transaction::with('event')
            ->where('order_id', $orderId)
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        // Cegah pemrosesan berulang
        if (in_array($transaction->status, ['success', 'settlement'])) {
            return response()->json([
                'message' => 'Already processed'
            ]);
        }

        // Mapping status dari Midtrans
        $transaction->status = match ($transactionStatus) {
            'capture'    => $fraudStatus === 'challenge'
                ? 'challenge'
                : 'success',

            'settlement' => 'settlement',
            'pending'    => 'pending',
            'cancel',
            'deny',
            'expire'     => 'failed',

            default      => $transaction->status,
        };

        // Jika pembayaran berhasil
        if (in_array($transaction->status, ['success', 'settlement'])) {
            $this->processSuccess($transaction);
        }

        $transaction->save();

        return response()->json([
            'message' => 'OK'
        ]);
    }

    /**
     * Menjalankan proses setelah pembayaran berhasil.
     */
    private function processSuccess(Transaction $transaction): void
    {
        $event = $transaction->event;

        // Jika event tersedia dan stok masih ada
        if ($event && $event->stock > 0) {

            // Kurangi stok tiket
            $event->stock -= 1;
            $event->save();

            // Kirim email E-Ticket
            try {
                Mail::to($transaction->customer_email)
                    ->send(new EventTicketMail($transaction));
            } catch (\Exception $e) {
                Log::error(
                    'Gagal mengirim email E-Ticket: ' . $e->getMessage()
                );
            }

        } else {

            // Jika stok habis setelah pembayaran berhasil
            Log::warning(
                'Stock habis setelah pembayaran berhasil. Order ID: ' .
                $transaction->order_id
            );
        }
    }
}