@extends('trainer.layout')

@section('title', 'Profil Pelatih Belum Terhubung')

@section('content')
    <main class="shell">
        <section class="hero">
            <div class="eyebrow">Portal Pelatih</div>
            <h1>Profil pelatih belum terhubung</h1>
            <p class="muted lead" style="margin:0;">Akun Anda sudah ber-role Pelatih, tetapi belum terhubung ke data pelatih.</p>
        </section>

        <div class="empty" style="max-width:640px;">
            <span class="emoji" aria-hidden="true">🔗</span>
            <strong>Butuh satu langkah lagi</strong>
            Hubungi admin FTM Society untuk menghubungkan akun ini melalui menu
            <strong style="display:inline; color:var(--cherry);">Management → Pelatih → Hubungkan Akun</strong>.
        </div>
    </main>
@endsection