@extends('partial.master')

@section('title')
    Detail Debt
@endsection

@section('breadcrumb')
    @parent
        <li class="breadcrumb-item">
        <a href="{{ route('debts.index') }}">Hutang</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Detail Debt
        </a>
    </li>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="row">
            <div class="col-8">
                <h5 class="card-header">Bayar Hutang - {{ $debt->customer->name }}</h5>
            </div>
            <div class="col-4">
                <h4 class="card-header text-info text-end">
                    Rp {{ number_format($debt->customer->total_debt, 0, ',', '.') }} 
                </h4>
            </div>    
        </div>

        <div class="table-responsive text-nowrap mt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th>Transaksi</th>
                        <th>Pembayaran</th>
                        <th>Hutang</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $index => $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y H:i') }}</td>
                            <td>
                                @if ($item->type === 'payment')
                                    Rp{{ number_format($item->amount, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                @if ($item->type === 'debt')
                                    Rp{{ number_format($item->amount, 0, ',', '.') }}
                                @endif
                            </td>
                            <td>{{ $item->note ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
