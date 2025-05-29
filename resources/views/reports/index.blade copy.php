@extends('partial.master')

@section('title')
    Reports
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Reports
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <h5 class="card-header">Laporan Hutang dan Pembayaran</h5>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.index') }}" class="row mb-4">
                <div class="col-md-3">
                    <label>Dari Tanggal:</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal:</label>
                    <input type="date" name="to" value="{{ $to }}" max="{{ date('Y-m-d') }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary">Filter</button>
                </div>
            </form>

            <div class="mb-3">
                <strong>Total Hutang:</strong> Rp {{ number_format($totalDebts, 0, ',', '.') }}<br>
                <strong>Total Pembayaran:</strong> Rp {{ number_format($totalPayments, 0, ',', '.') }}<br>
                <strong>Sisa Hutang:</strong> Rp {{ number_format($sisaHutang, 0, ',', '.') }}
            </div>

            <hr>

            <h5>Detail Hutang</h5>
            <ul>
                @foreach($debts as $debt)
                    <li>{{ $debt->customer->name }} - {{ $debt->date }} - Rp {{ number_format($debt->amount, 0, ',', '.') }}</li>
                @endforeach
            </ul>

            <h5>Detail Pembayaran</h5>
            <ul>
                @foreach($payments as $payment)
                    <li>{{ $payment->debt->customer->name ?? 'Tanpa Nama' }} - {{ $payment->payment_date }} - Rp {{ number_format($payment->amount, 0, ',', '.') }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection