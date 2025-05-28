@extends('partial.master')

@section('title')
    Settings
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">
        <a href="{{ url()->current() }}">
            Settings
        </a>
    </li>
@endsection

@section('content')
    <div class="card">
        <h5 class="card-header">Settings</h5>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="app_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="app_name" name="app_name" value="{{ old('app_name', $setting->app_name ?? '') }}" aria-describedby="defaultFormControlHelp"/>
                </div>
                <div class="mb-3">
                    <label class="form-label">Logo Saat Ini</label><br>
                    @if(!empty($setting->logo))
                        <img src="{{ asset('storage/logo/' . $setting->logo) }}" alt="Logo" width="120">
                    @else
                        <img src="{{ asset('logo.png') }}" alt="Logo Default" width="120">
                    @endif
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Upload Logo</label>
                    <div class="input-group">
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*" />
                        <label class="input-group-text" for="logo">Upload</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>   
        </div>
    </div>
@endsection