@extends('partial.master')

@section('title')
    Dashboard
@endsection

@section('breadcrumb')
{{-- kosongkan breadcrumb di dashboard --}}
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card bg-primary text-white mb-3">
        <div class="card-header">Total Hutang</div>
        <div class="card-body">
            <h2 class="card-title text-white">Rp {{ number_format($totalDebt, 0, ',', '.') }}</h2>
        </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-success text-white mb-3">
        <div class="card-header">Total Pembayaran</div>
        <div class="card-body">
            <h2 class="card-title text-white">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h2>
        </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-danger text-white mb-3">
        <div class="card-header">Sisa Hutang</div>
        <div class="card-body">
            <h2 class="card-title text-white">Rp {{ number_format($remainingDebt, 0, ',', '.') }}</h2>
        </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card bg-info text-white mb-3">
        <div class="card-header">Jumlah Pelanggan</div>
        <div class="card-body">
            <h2 class="card-title text-white">{{ $customerCount }}</h2>
        </div>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-header">Daftar Pelanggan dengan Hutang Aktif</div>
    <div class="card-body">
        <!-- Daftar Pelanggan dengan Hutang Aktif -->
        <div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Hutang</th>
                        <th>Total Pembayaran</th>
                        <th>Sisa Hutang</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Filter pelanggan yang punya sisa hutang > 0
                        $customersWithDebt = $customers->filter(function($customer) {
                            $totalDebt = $customer->debts->sum('amount');
                            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
                            $remaining = $totalDebt - $totalPaid;
                            return $remaining > 0;
                        });
                    @endphp

                    @forelse ($customersWithDebt as $customer)
                        @php
                            $totalDebt = $customer->debts->sum('amount');
                            $totalPaid = $customer->debts->flatMap->payments->sum('amount');
                            $remaining = $totalDebt - $totalPaid;

                            $lastDebtDate = $customer->debts->max('created_at');
                            $lastPaymentDate = $customer->debts->flatMap->payments->max('payment_date');
                            $lastTransactionDate = $lastDebtDate && $lastPaymentDate
                                ? ($lastDebtDate > $lastPaymentDate ? $lastDebtDate : $lastPaymentDate)
                                : ($lastDebtDate ?? $lastPaymentDate);
                        @endphp
                        <tr>
                            <td>{{ $lastTransactionDate ? $lastTransactionDate->format('d M Y H:i') : '-' }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>Rp {{ number_format($totalDebt, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($remaining, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('payments.create', ['debt_id' => $customer->debts->last()?->id]) }}" class="btn btn-sm btn-warning">Bayar Hutang</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada pelanggan dengan hutang aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Riwayat Transaksi Terbaru</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Tipe</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentActivities as $activity)
                    <tr>
                        <td>
                            @if($activity->type === 'debt')
                                <span class="badge bg-danger">Hutang</span>
                            @else
                                <span class="badge bg-success">Pembayaran</span>
                            @endif
                        </td>
                        <td>{{ $activity->customer_name }}</td>
                        <td>Rp {{ number_format($activity->amount, 0, ',', '.') }}</td>
                        <td>{{ $activity->note ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity->date)->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection


