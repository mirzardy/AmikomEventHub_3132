<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        // Ambil data transaksi terbaru beserta event terkait
        // dan tampilkan 20 data per halaman
        $transactions = Transaction::with('event')
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }
}
