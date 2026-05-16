@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar User Belum ACC</h2>
    @if(session('success'))
        <div class="text-accent mb-2">{{ session('success') }}</div>
    @endif
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                        @csrf
                        <button type="submit" class="bg-accent text-white px-3 py-1 rounded">ACC</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="3">Tidak ada user menunggu ACC.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection