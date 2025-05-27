@extends('partial.master')

@section('title')
    Pay Debt
@endsection

@section('breadcrumb')
    @parent
        <li class="breadcrumb-item">
        <a href="{{ route('debts.index') }}">Bayar Hutang</a>
    </li>
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Pay Debt
        </a>
    </li>
@endsection

@section('content')
    {{-- Menampilkan error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="card mb-4">
        <h5 class="card-header">Bayar Hutang - {{ $debt->customer->name }}</h5>
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="debt_id" value="{{ $debt->id }}">
                
                <div class="mb-3">
                    <label for="amount" class="form-label">Jumlah Bayar</label>
                    <input type="number" name="amount" class="form-control" required min="1">
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Catatan</label>
                    <textarea name="note" class="form-control" rows="3"></textarea>
                </div>
                <input type="date" name="payment_date" class="form-control mb-3" readonly required value="{{ date('Y-m-d') }}">
                <button type="submit" class="btn btn-primary">Bayar</button>
            </form>   
        </div>
    </div>
@endsection
