<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        // 1. Validasi data pemesan
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. Cek stok tiket
        if ($event->stock <= 0) {
            return back()->with(
                'error',
                'Mohon maaf, tiket untuk acara ini sudah habis.'
            );
        }

        // 3. Generate kode transaksi
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        $totalPrice = $event->price + 5000;

        // 4. Simpan transaksi ke database
        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => $orderId,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $totalPrice,
            'status'         => 'Pending',
        ]);

        // =========================
        // INTEGRASI SNAP MIDTRANS
        // =========================

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Sandbox
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Data transaksi yang dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
        ];

        try {
            // Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Simpan token ke database
            $transaction->update([
                'snap_token' => $snapToken,
            ]);

            // Redirect ke halaman pembayaran
            return redirect()->route(
                'checkout.payment',
                $transaction->order_id
            );
        } catch (\Exception $e) {
            return back()->with(
                'error',
                'Gagal memproses pembayaran jaringan: ' . $e->getMessage()
            );
        }
    }

    public function payment($order_id)
    {
        // Mengambil daftar kategori untuk menu footer
        $categories = Category::all();

        // Mengambil transaksi beserta event terkait
        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view(
            'checkout.payment',
            compact('transaction', 'categories')
        );
    }
    public function success($order_id)
{
    // Mengambil daftar kategori untuk menu footer
    $categories = Category::all();

    // Mengambil transaksi berdasarkan order ID
    $transaction = Transaction::where('order_id', $order_id)
        ->firstOrFail();

    // Validasi status pembayaran asli dari Midtrans
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;

    try {
        $midtransStatus = \Midtrans\Transaction::status($order_id);

        // Ubah status menjadi success jika pembayaran telah lunas
        if (in_array(
            $midtransStatus->transaction_status,
            ['capture', 'settlement']
        )) {
            $transaction->update([
                'status' => 'success',
            ]);
        }
    } catch (\Exception $e) {
        return redirect()
            ->route('home')
            ->with(
                'error',
                'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.'
            );
    }

    return view(
        'checkout.success',
        compact('transaction', 'categories')
    );
}
}