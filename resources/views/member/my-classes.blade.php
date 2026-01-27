@extends('layouts.member') {{-- sesuaikan layout kamu --}}

@section('content')
<div class="container">
    <h2 class="mb-4">My Classes</h2>

    @if($myClasses->isEmpty())
        <div class="alert alert-info">
            Kamu belum memiliki jadwal kelas.
        </div>
    @else
        <div class="row">
            @foreach($myClasses as $item)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $item->schedule->classModel->name }}
                            </h5>

                            <p class="mb-1">
                                <strong>Hari:</strong>
                                {{ $item->schedule->day }}
                            </p>

                            <p class="mb-1">
                                <strong>Jam:</strong>
                                {{ \Carbon\Carbon::parse($item->schedule->class_time)->format('H:i') }}
                            </p>

                            <span class="badge bg-success mt-2">
                                Confirmed
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
