@extends('partial.master')

@section('title')
    Customers
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Customers
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="row">
        <div class="col-9">
            <h5 class="card-header">Daftar Pelanggan</h5>
        </div>
        <div class="col-3 text-center">
            <a href="{{ route('customers.create') }}" class="btn btn-primary mt-3">Tambah Pelanggan</a>
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
            <th>Nama</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->address }}</td>
                <td>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data pelanggan.</td>
                </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</div>
@endsection