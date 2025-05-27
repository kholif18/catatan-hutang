<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Customer;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $debts = Debt::with('customer')->latest()->get();
        // return view('debts.index', compact('debts'));

        $customers = Customer::with('debts.payments')->get()->map(function ($customer) {
            $totalDebt = $customer->debts->sum('amount');
            $totalPaid = $customer->debts->flatMap->payments->sum('amount');

            $customer->total_debt = $totalDebt - $totalPaid;

            return $customer;
        });
        return view('debts.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('debts.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
        ]);

        // BUAT entri hutang baru, bukan update yang lama
        Debt::create([
            'customer_id' => $validated['customer_id'],
            'amount' => $validated['amount'],
            'note' => $validated['note'],
        ]);

        // Cari entri debt berdasarkan customer_id, atau buat baru jika belum ada
        // $debt = Debt::firstOrCreate(
        //     ['customer_id' => $request->customer_id],
        //     ['amount' => 0]
        // );

        // Tambahkan jumlah hutang
        // $debt->amount += $request->amount;
        // $debt->save();

        return redirect()->route('debts.index')->with('success', 'Hutang berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Debt $debt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Debt $debt)
    {
        return view('debts.edit', compact('debt'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Debt $debt)
    {
        $request->validate([
        'payment_amount' => 'required|numeric|min:1|max:' . $debt->amount,
        ]);

        return redirect()->route('payments.create', ['debt_id' => $debt->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Debt $debt)
    {
        //
    }
}
