<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Mencari ID transaksi di database lokal
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Cegah proses berulang jika status sudah lunas/sukses
        if (in_array($transaction->status, ['settlement', 'success'])) {
            return response()->json(['message' => 'Already processed']);
        }

        // Logika Penerjemahan Status Midtrans API menggunakan PHP 8 match
        $transaction->status = match ($transactionStatus) {
            'capture'    => ($fraudStatus === 'challenge') ? 'challenge' : 'success',
            'settlement' => 'settlement',
            'pending'    => 'pending',
            'cancel', 'deny', 'expire' => 'failed',
            default      => $transaction->status,
        };

        // Jalankan instruksi jika transaksi berhasil
        if (in_array($transaction->status, ['success', 'settlement'])) {
            $this->processSuccess($transaction);
        }

        $transaction->save();

        return response()->json(['message' => 'OK']);
    }

    private function processSuccess(Transaction $transaction): void
    {
        // Instruksi lanjutan saat transaksi lunas (pemotongan tiket) akan dibahas pada Modul 13
    }
}