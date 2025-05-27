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
            @forelse ($debts as $index => $debt)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $debt->customer->name }}</td>
                    <td>Rp{{ number_format($debt->amount, 0, ',', '.') }}</td>
                    <td>{{ $debt->note ?? '-' }}</td>
                    <td>{{ $debt->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('payments.create', ['debt_id' => $debt->id]) }}" class="btn btn-sm btn-warning">Bayar Hutang</a>
                    </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data hutang.</td>
                    </tr>
                @endforelse
        </tbody>
        </table>
    </div>
</div>
@endsection