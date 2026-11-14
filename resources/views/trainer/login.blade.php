@extends('trainer.layout')

@section('title', 'Login Pelatih')

@section('content')
    <div class="login-wrap">
        <div class="login-card">
            <div class="login-brand">
                <div class="brand-mark" aria-hidden="true">FTM</div>
                <div>
                    <small>FTM Fitness Society</small>
                    <strong>Portal Pelatih</strong>
                </div>
            </div>

            <h1>Masuk</h1>
            <p class="muted" style="margin:0; font-size:14px;">Lihat jadwal mengajar dan daftar member Anda.</p>

            @if (isset($errors) && $errors->any())
                <div class="alert" role="alert" style="margin-top:18px;">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('trainer.login.submit') }}">
                @csrf
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>

                <label for="password">Password</label>
                <div class="input-wrap">
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    <button type="button" class="toggle-pass" data-toggle-password aria-controls="password" aria-pressed="false">Lihat</button>
                </div>

                <label class="check">
                    <input type="checkbox" name="remember" value="1">
                    Ingat saya
                </label>

                <button type="submit" class="btn" style="width:100%; margin-top:22px;">Masuk</button>
            </form>

            <div class="divider"></div>
            <p class="muted" style="font-size:12.5px; margin:0;">
                Akun pelatih dibuat oleh admin FTM Society. Lupa password? Hubungi admin.
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var btn = document.querySelector('[data-toggle-password]');
            var input = document.getElementById('password');
            if (!btn || !input) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.textContent = show ? 'Sembunyi' : 'Lihat';
                btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            });
        })();
    </script>
@endpush