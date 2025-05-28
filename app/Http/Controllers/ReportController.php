<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Jika request 'from' dan 'to' tidak ada, pakai tanggal awal dan akhir bulan ini
        // $from = $request->from ?? now()->startOfMonth()->toDateString();
        // $to = $request->to ?? now()->endOfMonth()->toDateString();
        $from = $request->input('from') ?: now()->startOfMonth()->toDateString();
        $to = $request->input('to') ?: now()->endOfMonth()->toDateString();

        // Ambil data hutang antara tanggal dari - sampai, menggunakan kolom 'debt_date'
        $debts = Debt::whereBetween('debt_date', [$from, $to])->get();

        // Ambil data pembayaran antara tanggal dari - sampai, menggunakan kolom 'payment_date'
        $payments = Payment::whereBetween('payment_date', [$from, $to])->get();

        // Hitung total hutang dan total pembayaran
        $totalDebts = $debts->sum('amount');
        $totalPayments = $payments->sum('amount');
        $sisaHutang = $totalDebts - $totalPayments;

        // Kirim data ke view laporan
        return view('reports.index', compact('debts', 'payments', 'totalDebts', 'totalPayments', 'sisaHutang', 'from', 'to'));
    }
}
