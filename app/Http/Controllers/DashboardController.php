<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua customer dengan hutang dan pembayaran
        $customers = Customer::with('debts.payments')->get();

        // Hitung total hutang, total pembayaran dan sisa hutang
        $totalDebt = $customers->flatMap->debts->sum('amount');
        $totalPaid = $customers->flatMap->debts->flatMap->payments->sum('amount');
        $remainingDebt = $totalDebt - $totalPaid;

        // Total customer
        $customerCount = $customers->count();

        // Ambil 5 hutang terbaru
        $recentDebts = Debt::with('customer')->latest()->take(5)->get()->map(function ($debt) {
            return (object)[
                'type' => 'debt',
                'amount' => $debt->amount,
                'note' => $debt->note,
                'date' => $debt->created_at,
                'customer_name' => $debt->customer->name,
            ];
        });

        // Ambil 5 pembayaran terbaru
        $recentPayments = Payment::with('debt.customer')->latest()->take(5)->get()->map(function ($payment) {
            return (object)[
                'type' => 'payment',
                'amount' => $payment->amount,
                'note' => $payment->note,
                'date' => $payment->payment_date,
                'customer_name' => $payment->debt->customer->name,
            ];
        });

        // Gabungkan dan urutkan aktivitas terbaru
        $recentActivities = $recentDebts->merge($recentPayments)->sortByDesc('date')->take(10);

        return view('dashboard.index', compact(
            'totalDebt',
            'totalPaid',
            'remainingDebt',
            'customerCount',
            'recentActivities',
            'customers' 
        ));
    }
}
