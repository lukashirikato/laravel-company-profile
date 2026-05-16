<!-- resources/views/admin/customers/activate-login.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Konfirmasi Aktivasi Login</h2>

    <p class="mb-4">Apakah Anda yakin ingin memberikan akses login kepada <strong>{{ $customer->name }}</strong>?</p>

    <form action="{{ route('customers.activateLogin', $customer->id) }}" method="POST">
        @csrf
        <button type="submit" class="bg-accent text-white px-4 py-2 rounded hover:bg-springs-ivy">
            Ya, Aktifkan Login
        </button>
        <a href="{{ route('customers.index') }}" class="ml-4 text-dark/70 hover:underline">Kembali</a>
    </form>
</div>
@endsection
