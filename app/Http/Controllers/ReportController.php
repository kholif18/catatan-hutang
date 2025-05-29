<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Report;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


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
        $payments = Payment::with('customer')
                    ->whereBetween('payment_date', [$from, $to])
                    ->get();

        // Hitung total hutang dan total pembayaran
        $totalDebts = $debts->sum('amount');
        $totalPayments = $payments->sum('amount');
        $sisaHutang = $totalDebts - $totalPayments;

        // Kirim data ke view laporan
        return view('reports.index', compact('debts', 'payments', 'totalDebts', 'totalPayments', 'sisaHutang', 'from', 'to'));
    }
    
    public function export(Request $request)
    {
        $format = $request->input('format');
        $from = $request->input('from');
        $to = $request->input('to');

    if ($format == 'pdf') {
        $debts = Debt::whereBetween('debt_date', [$from, $to])->get();
        $payments = Payment::whereBetween('payment_date', [$from, $to])->get();

        $totalDebts = $debts->sum('amount');
        $totalPayments = $payments->sum('amount');

        $pdf = PDF::loadView('reports.export_pdf', compact(
            'from', 'to', 'debts', 'payments', 'totalDebts', 'totalPayments'
        ));

        return $pdf->stream("laporan_{$from}_sd_{$to}.pdf");
    }

    if ($format == 'excel') {
        return Excel::download(new ReportExport($from, $to), "laporan_{$from}_sd_{$to}.xlsx");
    }

    return redirect()->back()->with('error', 'Format tidak dikenali.');
    }
}
