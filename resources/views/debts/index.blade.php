@extends('partial.master')

@section('title')
    Hutang
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Hutang
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="row">
        <div class="col-9">
            <h5 class="card-header">Daftar Hutang Pelanggan</h5>
        </div>
        <div class="col-3 text-center">
            <a href="{{ route('debts.create') }}" class="btn btn-primary mt-3">Catat Hutang Baru</a>
        </div>
    </div>
    {{-- Alert dari session --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible" role="alert">
            {{ session('warning') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible" role="alert">
            {{ session('info') }} 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Jumlah</th>
                <th>Catatan</th>
                <th>Tanggal</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                    @forelse ($customers as $index => $customer)
                        @php
                            $lastDebt = $customer->debts->last();
                            if ($customer->debts->isEmpty()) continue;
                            $lastDebt = $customer->debts->last();
                        @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>Rp{{ number_format($customer->total_debt, 0, ',', '.') }}</td>
                        <td>{{ $lastDebt?->note ?? '-' }}</td>
                        <td>{{ $lastDebt?->created_at?->format('d M Y H:i') ?? '-' }}</td>
                        <td>
                            @if ($customer->total_debt > 0)
                                <a href="{{ route('payments.create', ['debt_id' => $customer->debts->last()?->id]) }}" class="btn btn-sm btn-warning">Bayar Hutang</a>
                            @else
                                <a href="{{ route('payments.detail', ['debt_id' => $customer->debts->last()?->id]) }}" class="btn btn-sm btn-info">View Detail</a>
                            @endif
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data transaksi.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection