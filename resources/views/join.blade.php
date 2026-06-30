<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="{{ asset('icons/favicon.jpg') }}" />
  <title>Daftar | FTM Society</title>

  <script src="https://cdn.tailwindcss.com/3.4.16"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#EE4E8B", secondary: "#7A2B4A", accent: "#1A7A5E",
            "light-pink": "#F4C9DF", cream: "#FCF9F2", dark: "#1C1C1C",
            "springs-ivy": "#1D5A4B", "grounded-green": "#C5D79B",
            "power-pink": "#EE4E8B", "burnt-cherry": "#7A2B4A",
            "soft-petals": "#F4C9DF", "patina-green": "#1A7A5E",
            "layl": "#1C1C1C", "rising": "#FCF9F2"
          },
          fontFamily: {
            nord: ['Nord', 'Poppins', 'sans-serif'],
            instrument: ['"Instrument Serif"', 'Georgia', 'serif'],
            poppins: ['Poppins', 'sans-serif']
          }
        }
      }
    };
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
  <link rel="preload" href="{{ asset('fonts/Nord-Black.woff2') }}" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="{{ asset('fonts/Nord-Bold.woff2') }}"  as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="{{ asset('fonts/Nord-Book.woff2') }}"  as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">

  <style>
    html {
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
    }
    body {
      font-family: 'Poppins', system-ui, sans-serif;
      font-size: 16px;
      line-height: 1.65;
      font-weight: 500;
      color: #1C1C1C;
      scroll-behavior: smooth;
      overflow-x: hidden;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background: #FCF9F2;
    }
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Nord', 'Poppins', sans-serif;
      letter-spacing: -0.015em;
    }

    /* ── FTM Brand Ornaments ── */
    .brand-flower {
      display: inline-block;
      width: 40px; height: 40px;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%231C1C1E' d='M50 8c-13 0-20 10-20 20 0 6 3 11 7 14-6 3-10 8-10 15 0 10 9 18 23 18s23-8 23-18c0-7-4-12-10-15 4-3 7-8 7-14 0-10-7-20-20-20z'/%3E%3C/svg%3E");
      background-size: contain; background-repeat: no-repeat; background-position: center;
      opacity: 0.85;
    }
    .brand-flower-ivory { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23F5EFE6' d='M50 8c-13 0-20 10-20 20 0 6 3 11 7 14-6 3-10 8-10 15 0 10 9 18 23 18s23-8 23-18c0-7-4-12-10-15 4-3 7-8 7-14 0-10-7-20-20-20z'/%3E%3C/svg%3E"); }
    .brand-asterisk {
      display: inline-block; width: 28px; height: 28px;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23E8618C' d='M45 5h10v90h-10zM5 45h90v10H5zM14.64 21.71l7.07-7.07 63.64 63.64-7.07 7.07zM85.36 14.64l7.07 7.07-63.64 63.64-7.07-7.07z'/%3E%3C/svg%3E");
      background-size: contain; background-repeat: no-repeat; background-position: center;
    }
    .brand-asterisk-ivory { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23F5EFE6' d='M45 5h10v90h-10zM5 45h90v10H5zM14.64 21.71l7.07-7.07 63.64 63.64-7.07 7.07zM85.36 14.64l7.07 7.07-63.64 63.64-7.07-7.07z'/%3E%3C/svg%3E"); }
    .brand-cmark {
      display: inline-block; width: 32px; height: 32px;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%236B2D4E' d='M10 50C10 28 28 10 50 10v18c-12 0-22 10-22 22s10 22 22 22v18C28 90 10 72 10 50zm80 0C90 28 72 10 50 10v18c12 0 22 10 22 22s-10 22-22 22v18c22 0 40-18 40-40z'/%3E%3C/svg%3E");
      background-size: contain; background-repeat: no-repeat; background-position: center;
    }
    @keyframes brandFloat {
      0%, 100% { transform: translateY(0) rotate(0deg); }
      50%      { transform: translateY(-10px) rotate(8deg); }
    }
    .brand-float { animation: brandFloat 5s ease-in-out infinite; }
    .brand-divider {
      display: flex; align-items: center; justify-content: center; gap: 12px;
    }
    .brand-divider::before, .brand-divider::after {
      content: ""; height: 1px; width: 80px;
      background: linear-gradient(90deg, transparent, #EE4E8B, transparent);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn { animation: fadeIn 0.3s ease; }

    /* ── Step Content ── */
    .join-step-content { animation: joinFadeIn 0.4s ease-out; }
    @keyframes joinFadeIn {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes joinShake {
      0%, 100% { transform: translateX(0); }
      20%      { transform: translateX(-6px); }
      40%      { transform: translateX(6px); }
      60%      { transform: translateX(-4px); }
      80%      { transform: translateX(4px); }
    }

    /* Input focus */
    .join-field input:focus,
    .join-field textarea:focus,
    .join-field select:focus {
      border-color: #EE4E8B !important;
      box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.08) !important;
    }

    /* Step indicator */
    .join-step-dot.active { background: #EE4E8B; border-color: #EE4E8B; color: #FFF; box-shadow: 0 4px 12px rgba(238,78,139,0.3); }
    .join-step-dot.completed { background: #7A2B4A; border-color: #7A2B4A; color: #FFF; }
    .join-step-dot.completed .step-num { display: none; }
    .join-step-dot.completed i.ri-check-line { display: block !important; }
    .join-step-dot.active .step-num { display: block; }
    .join-step-dot.active i.ri-check-line { display: none !important; }
    .join-step-label.active { color: #EE4E8B; }
    .join-step-label.completed { color: #7A2B4A; }

    /* Benefit highlight */
    .join-benefit.active .join-benefit-icon {
      background: rgba(238, 78, 139, 0.2);
      border-color: rgba(238, 78, 139, 0.4);
      box-shadow: 0 0 20px rgba(238, 78, 139, 0.1);
    }

    /* Custom select arrow */
    .join-select-arrow {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23EE4E8B'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
      background-position: right 1rem center;
      background-size: 1.25rem;
      background-repeat: no-repeat;
    }
  </style>
</head>
<body>

  {{-- ── Simple Top Bar ── --}}
  <div class="bg-cream border-b border-light-pink/20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-all duration-300 group">
          <span class="brand-flower brand-flower-ivory" style="width: 22px; height: 22px;"></span>
          <span class="text-xl md:text-2xl tracking-tight flex items-baseline gap-1.5">
            <span class="font-nord font-black text-[#EE4E8B]">FTM</span>
            <span class="font-instrument italic text-[#7A2B4A] text-2xl md:text-3xl">Society</span>
          </span>
        </a>
        <a href="{{ route('login') }}" class="font-poppins text-sm font-semibold text-dark/50 hover:text-primary transition-colors">
          Sudah punya akun?
        </a>
      </div>
    </div>
  </div>

  {{-- ── MAIN REGISTRATION SECTION ── --}}
  <section id="join" class="py-10 md:py-16 bg-cream min-h-[calc(100vh-64px)] flex items-center">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 w-full">
      <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-white flex flex-col lg:flex-row max-w-6xl mx-auto">

        {{-- LEFT PANEL — Brand Story + Benefits --}}
        <div class="relative lg:w-[45%] bg-gradient-to-br from-burnt-cherry via-[#5E1F3C] to-layl p-10 md:p-14 lg:p-16 flex flex-col justify-center overflow-hidden">

          <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="brand-flower brand-flower-ivory absolute -top-6 -right-6 opacity-[0.12]" style="width: 160px; height: 160px;"></div>
            <div class="brand-asterisk brand-asterisk-ivory absolute bottom-12 left-8 opacity-[0.10] brand-float" style="width: 48px; height: 48px;"></div>
            <div class="brand-cmark absolute top-1/3 left-[55%] opacity-[0.06] brand-float" style="width: 70px; height: 70px; animation-delay: 1.5s; filter: invert(1);"></div>
            <div class="brand-flower brand-flower-ivory absolute bottom-20 right-8 opacity-[0.08] brand-float" style="width: 60px; height: 60px; animation-delay: 2.5s;"></div>
          </div>

          <div class="relative z-10 space-y-8">
            <div class="space-y-2">
              <p class="font-instrument italic text-2xl md:text-3xl text-light-pink/80">Bismillah,</p>
              <h2 class="font-nord text-3xl md:text-4xl lg:text-5xl font-black text-white leading-[1.1] tracking-tight">
                Mulai Perjalanan<br/>
                <span class="font-instrument italic font-normal text-transparent bg-clip-text bg-gradient-to-r from-light-pink via-primary to-light-pink">Sisterhood</span>mu
              </h2>
            </div>

            <p class="font-poppins text-white/80 text-sm md:text-base leading-relaxed max-w-sm">
              Setiap langkah membawamu lebih dekat ke versi terbaik dirimu — dalam lingkungan yang aman, nyaman, dan penuh keberkahan.
            </p>

            <div class="brand-divider !justify-start">
              <span class="brand-flower brand-flower-ivory" style="width: 18px; height: 18px;"></span>
              <span class="h-px w-16 bg-gradient-to-r from-light-pink/60 to-transparent"></span>
            </div>

            {{-- Benefits --}}
            <div class="space-y-6">
              <div class="join-benefit flex items-start gap-4" data-step="1">
                <div class="join-benefit-icon w-11 h-11 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex-shrink-0 mt-0.5 transition-all duration-500">
                  <i class="ri-shield-user-line text-xl text-light-pink"></i>
                </div>
                <div>
                  <h3 class="font-nord text-white font-bold text-base md:text-lg">Data Diri Terlindungi</h3>
                  <p class="font-poppins text-white/60 text-sm leading-relaxed">Privasi Anda adalah prioritas kami. Setiap informasi dijaga dengan standar keamanan tertinggi.</p>
                </div>
              </div>
              <div class="join-benefit flex items-start gap-4" data-step="2">
                <div class="join-benefit-icon w-11 h-11 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex-shrink-0 mt-0.5 transition-all duration-500">
                  <i class="ri-heart-pulse-line text-xl text-light-pink"></i>
                </div>
                <div>
                  <h3 class="font-nord text-white font-bold text-base md:text-lg">Dibimbing Muslimah Profesional</h3>
                  <p class="font-poppins text-white/60 text-sm leading-relaxed">Latih dirimu bersama instruktur wanita berpengalaman yang memahami kebutuhan dan nilai-nilaimu.</p>
                </div>
              </div>
              <div class="join-benefit flex items-start gap-4" data-step="3">
                <div class="join-benefit-icon w-11 h-11 flex items-center justify-center rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex-shrink-0 mt-0.5 transition-all duration-500">
                  <i class="ri-team-line text-xl text-light-pink"></i>
                </div>
                <div>
                  <h3 class="font-nord text-white font-bold text-base md:text-lg">Komunitas yang Mendukung</h3>
                  <p class="font-poppins text-white/60 text-sm leading-relaxed">Bergabung dengan ribuan Muslimah lain yang saling menguatkan dalam perjalanan kebugaran dan kesehatan.</p>
                </div>
              </div>
            </div>

            <div class="pt-4">
              <p class="font-poppins text-white/50 text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-light-pink hover:text-primary transition-colors duration-200 underline underline-offset-4 decoration-light-pink/30 hover:decoration-primary/60">Masuk di sini</a>
              </p>
            </div>
          </div>
        </div>

        {{-- RIGHT PANEL — Multi-Step Registration Form --}}
        <div class="relative lg:w-[55%] bg-cream p-8 md:p-12 lg:p-14 flex flex-col">

          {{-- Step Indicator --}}
          <div class="mb-10">
            <div class="flex items-center justify-between max-w-sm mx-auto">
              <div class="flex flex-col items-center">
                <div class="join-step-dot w-10 h-10 rounded-full flex items-center justify-center font-nord font-bold text-sm transition-all duration-500 border-2 bg-primary border-primary text-white shadow-lg shadow-primary/30" data-step="1">
                  <span class="step-num">1</span>
                  <i class="ri-check-line hidden text-lg"></i>
                </div>
                <span class="join-step-label font-poppins text-[10px] md:text-xs font-semibold mt-2 text-primary transition-colors duration-300" data-step="1">Data Diri</span>
              </div>
              <div class="join-connector flex-1 h-0.5 mx-3 rounded-full bg-light-pink transition-all duration-500 relative">
                <div class="join-connector-fill absolute inset-0 rounded-full bg-primary transition-all duration-500" style="width: 0%;"></div>
              </div>
              <div class="flex flex-col items-center">
                <div class="join-step-dot w-10 h-10 rounded-full flex items-center justify-center font-nord font-bold text-sm transition-all duration-500 border-2 border-light-pink bg-white text-dark/40" data-step="2">
                  <span class="step-num">2</span>
                  <i class="ri-check-line hidden text-lg"></i>
                </div>
                <span class="join-step-label font-poppins text-[10px] md:text-xs font-semibold mt-2 text-dark/40 transition-colors duration-300" data-step="2">Profil</span>
              </div>
              <div class="join-connector flex-1 h-0.5 mx-3 rounded-full bg-light-pink transition-all duration-500 relative">
                <div class="join-connector-fill absolute inset-0 rounded-full bg-primary transition-all duration-500" style="width: 0%;"></div>
              </div>
              <div class="flex flex-col items-center">
                <div class="join-step-dot w-10 h-10 rounded-full flex items-center justify-center font-nord font-bold text-sm transition-all duration-500 border-2 border-light-pink bg-white text-dark/40" data-step="3">
                  <span class="step-num">3</span>
                  <i class="ri-check-line hidden text-lg"></i>
                </div>
                <span class="join-step-label font-poppins text-[10px] md:text-xs font-semibold mt-2 text-dark/40 transition-colors duration-300" data-step="3">Akun</span>
              </div>
            </div>
          </div>

          {{-- FORM --}}
          <form id="joinForm" name="Data-Member" method="POST" action="{{ route('signup.store') }}" class="flex-1 flex flex-col" novalidate>
            @csrf
            <input type="hidden" id="join-current-step" name="join_current_step" value="1">

            {{-- Error Popup --}}
            @if ($errors->any())
            <div id="join-error-popup" class="fixed inset-0 flex items-center justify-center z-50 bg-dark/50 backdrop-blur-sm">
              <div class="bg-cream rounded-2xl shadow-2xl px-8 py-8 max-w-md w-full text-center border-t-4 border-primary animate-fadeIn">
                <div class="flex justify-center mb-4">
                  <div class="bg-primary/10 rounded-full p-4">
                    <i class="ri-error-warning-line text-4xl text-primary"></i>
                  </div>
                </div>
                <h4 class="font-nord text-xl font-bold text-primary mb-2">Pendaftaran Perlu Diperbaiki</h4>
                <div class="text-dark text-sm text-left space-y-1.5 mb-6 max-h-40 overflow-y-auto">
                  @foreach ($errors->all() as $error)
                    <div class="flex items-start gap-2">
                      <i class="ri-arrow-right-s-line text-primary mt-0.5 flex-shrink-0"></i>
                      <span>{{ $error }}</span>
                    </div>
                  @endforeach
                </div>
                <button type="button" onclick="closeJoinErrorPopup()" class="bg-primary text-white px-8 py-2.5 rounded-full hover:bg-secondary transition-all font-bold font-nord text-sm">Mengerti</button>
              </div>
            </div>
            @endif

            {{-- Success Popup --}}
            @if(session('success'))
            <div id="join-success-popup" class="fixed inset-0 flex items-center justify-center z-50 bg-dark/50 backdrop-blur-sm">
              <div class="bg-cream rounded-2xl shadow-2xl px-10 py-10 max-w-sm w-full text-center animate-fadeIn">
                <div class="flex justify-center mb-5">
                  <div class="w-20 h-20 rounded-full bg-patina-green/10 flex items-center justify-center">
                    <svg class="w-10 h-10 text-patina-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                  </div>
                </div>
                <h4 class="font-nord text-xl font-bold text-secondary mb-3">Pendaftaran Berhasil!</h4>
                <p class="text-dark/70 text-sm mb-6 leading-relaxed">{{ session('success') }}</p>
                <button type="button" onclick="closeJoinSuccessPopup()" class="bg-secondary text-white px-8 py-2.5 rounded-full hover:bg-primary transition-all font-bold font-nord text-sm">Lanjutkan</button>
              </div>
            </div>
            @endif

            {{-- Steps Content --}}
            <div class="flex-1 space-y-6">

              {{-- STEP 1 --}}
              <div class="join-step-content" data-step="1">
                <div class="mb-6">
                  <h3 class="font-nord text-xl font-bold text-dark">Data Diri</h3>
                  <p class="font-poppins text-dark/50 text-sm mt-1">Lengkapi informasi pribadimu untuk memulai.</p>
                </div>
                <div class="space-y-4">
                  <div class="join-field">
                    <label for="join_name" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Nama Lengkap</label>
                    <input type="text" id="join_name" name="name" placeholder="Nama lengkap sesuai identitas" value="{{ old('name') }}" required
                      class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25" />
                  </div>
                  <div class="join-field">
                    <label for="join_phone" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Nomor WhatsApp</label>
                    <input type="tel" id="join_phone" name="phone_number" placeholder="08xxxx" value="{{ old('phone_number') }}" required
                      class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25" />
                    <p class="font-poppins text-[10px] text-dark/30 mt-1">Masukkan nomor WhatsApp aktif, contoh: 081234567890</p>
                  </div>
                  <div class="join-field">
                    <label for="join_email" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Email</label>
                    <input type="email" id="join_email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required
                      class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25" />
                  </div>
                  <div class="join-field">
                    <label for="join_birth" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Tanggal Lahir</label>
                    <input type="date" id="join_birth" name="birth_date" value="{{ old('birth_date') }}" required
                      class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark" />
                  </div>
                </div>
              </div>

              {{-- STEP 2 --}}
              <div class="join-step-content hidden" data-step="2">
                <div class="mb-6">
                  <h3 class="font-nord text-xl font-bold text-dark">Profil Kebugaran</h3>
                  <p class="font-poppins text-dark/50 text-sm mt-1">Bantu kami memahami kebutuhan dan tujuanmu.</p>
                </div>
                <div class="space-y-4">
                  <div class="join-field">
                    <label for="join_goals" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Goals / Tujuan</label>
                    <textarea id="join_goals" name="goals" rows="3" placeholder="Contoh: Ingin menurunkan berat badan, membentuk otot, atau menjaga kebugaran" class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25 resize-none">{{ old('goals') }}</textarea>
                  </div>
                  <div class="join-field">
                    <label for="join_kondisi" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Kondisi Khusus <span class="text-dark/30 font-normal">(opsional)</span></label>
                    <textarea id="join_kondisi" name="kondisi_khusus" rows="2" placeholder="Contoh: Riwayat asma, cedera lutut, atau kondisi lainnya" class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25 resize-none">{{ old('kondisi_khusus') }}</textarea>
                  </div>
                  <div class="join-field">
                    <label for="join_referensi" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Mengenal FTM dari</label>
                    <input type="text" id="join_referensi" name="referensi" placeholder="Contoh: Instagram, teman, Google" value="{{ old('referensi') }}"
                      class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25" />
                  </div>
                  <div class="join-field">
                    <label for="join_pengalaman" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Pengalaman Ikut Olahraga</label>
                    <input type="text" id="join_pengalaman" name="pengalaman" placeholder="Contoh: Pernah ikut yoga, gym, dll" value="{{ old('pengalaman') }}"
                      class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25" />
                  </div>
                  <div class="join-field">
                    <label class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Apakah Anda Muslimah?</label>
                    <div class="mb-3 p-3 bg-grounded-green/30 rounded-xl border border-grounded-green/40">
                      <div class="flex items-start gap-2.5">
                        <i class="ri-information-line text-patina-green text-base mt-0.5 flex-shrink-0"></i>
                        <p class="font-poppins text-[11px] text-dark/70 leading-relaxed">
                          <strong class="text-secondary">P.S:</strong> Kolom ini diperlukan karena adanya perbedaan pendapat di kalangan para ulama tentang batasan aurat perempuan muslim di hadapan perempuan non-muslim. Kami mengambil pendapat yang paling hati-hati. Kami tidak meminta bukti KTP, mohon isi dengan jujur sebagai bentuk toleransi. Semoga ridho dan berkenan.
                        </p>
                      </div>
                    </div>
                    <select id="join_muslim" name="is_muslim" required
                      class="w-full px-4 py-3.5 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark appearance-none join-select-arrow">
                      <option value="" disabled {{ old('is_muslim') ? '' : 'selected' }}>-- Pilih --</option>
                      <option value="ya" {{ old('is_muslim') == 'ya' ? 'selected' : '' }}>Ya</option>
                      <option value="tidak" {{ old('is_muslim') == 'tidak' ? 'selected' : '' }}>Tidak</option>
                    </select>
                  </div>
                </div>
              </div>

              {{-- STEP 3 --}}
              <div class="join-step-content hidden" data-step="3">
                <div class="mb-6">
                  <h3 class="font-nord text-xl font-bold text-dark">Buat Akun</h3>
                  <p class="font-poppins text-dark/50 text-sm mt-1">Amankan akunmu dengan password yang kuat.</p>
                </div>
                <div class="space-y-4">
                  <div class="join-field">
                    <label for="join_password" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">
                      Password <span class="font-normal text-dark/30">(minimal 8 karakter)</span>
                    </label>
                    <div class="relative">
                      <input type="password" id="join_password" name="password" placeholder="Buat password" required minlength="8"
                        class="w-full px-4 py-3.5 pr-12 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25" />
                      <button type="button" class="join-toggle-pw absolute right-3 top-1/2 -translate-y-1/2 text-dark/25 hover:text-primary transition-colors" data-target="join_password">
                        <i class="ri-eye-line text-lg"></i>
                      </button>
                    </div>
                  </div>
                  <div class="join-field">
                    <label for="join_password_confirmation" class="font-poppins text-xs font-semibold text-dark/70 block mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                      <input type="password" id="join_password_confirmation" name="password_confirmation" placeholder="Ulangi password" required minlength="8"
                        class="w-full px-4 py-3.5 pr-12 bg-white rounded-xl border-2 border-light-pink/40 outline-none transition-all duration-200 font-poppins text-sm text-dark placeholder:text-dark/25" />
                      <button type="button" class="join-toggle-pw absolute right-3 top-1/2 -translate-y-1/2 text-dark/25 hover:text-primary transition-colors" data-target="join_password_confirmation">
                        <i class="ri-eye-line text-lg"></i>
                      </button>
                    </div>
                    <p id="join-pw-match" class="font-poppins text-[11px] mt-1.5 hidden"></p>
                  </div>
                  <div class="p-4 bg-primary/5 rounded-xl border border-primary/10">
                    <div class="flex items-start gap-3">
                      <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <i class="ri-shield-check-line text-primary text-sm"></i>
                      </div>
                      <div>
                        <p class="font-nord text-xs font-bold text-secondary">Verifikasi WhatsApp</p>
                        <p class="font-poppins text-[11px] text-dark/50 mt-0.5 leading-relaxed">Setelah mendaftar, kamu akan menerima kode OTP via WhatsApp. Pastikan nomor yang kamu daftarkan aktif ya!</p>
                      </div>
                    </div>
                  </div>
                  <div class="join-field">
                    <label class="flex items-start gap-3 cursor-pointer group">
                      <input type="checkbox" name="agree" value="1" {{ old('agree') ? 'checked' : '' }}
                        class="mt-0.5 w-4 h-4 rounded border-light-pink text-primary focus:ring-primary/30 cursor-pointer" />
                      <span class="font-poppins text-xs text-dark/50 leading-relaxed group-hover:text-dark/70 transition-colors">Saya bersedia menerima informasi promo, kelas baru, dan event komunitas melalui email.</span>
                    </label>
                  </div>
                </div>
              </div>

            </div>

            {{-- Navigation Buttons --}}
            <div class="mt-8 pt-6 border-t border-light-pink/30">
              <div class="flex items-center justify-between gap-4">
                <button type="button" id="join-prev-btn"
                  class="hidden items-center gap-2 font-nord font-bold text-sm text-dark/40 hover:text-primary transition-colors px-4 py-2.5">
                  <i class="ri-arrow-left-line"></i><span>Kembali</span>
                </button>
                <div class="flex-1"></div>
                <button type="button" id="join-next-btn"
                  class="inline-flex items-center gap-2 font-nord font-bold text-sm bg-primary text-white px-8 py-3.5 rounded-full hover:bg-secondary hover:shadow-lg hover:shadow-primary/20 transition-all duration-300">
                  <span>Lanjut</span><i class="ri-arrow-right-line"></i>
                </button>
                <button type="submit" id="join-submit-btn"
                  class="hidden items-center gap-2 font-nord font-bold text-sm bg-primary text-white px-8 py-3.5 rounded-full hover:bg-secondary hover:shadow-lg hover:shadow-primary/20 transition-all duration-300">
                  <i class="ri-shield-check-line"></i><span>Daftar & Verifikasi</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  {{-- Footer --}}
  <footer class="bg-cream border-t border-light-pink/20 py-6">
    <div class="container mx-auto px-4 text-center">
      <p class="font-poppins text-xs text-dark/40">&copy; {{ date('Y') }} FTM Society. Empowering Muslimah. Elevating Wellness.</p>
    </div>
  </footer>

  <script>
  (function() {
    'use strict';

    var currentStep = 1;
    var totalSteps = 3;

    var form           = document.getElementById('joinForm');
    var stepContents    = document.querySelectorAll('.join-step-content');
    var stepDots        = document.querySelectorAll('.join-step-dot');
    var stepLabels      = document.querySelectorAll('.join-step-label');
    var benefits        = document.querySelectorAll('.join-benefit');
    var connectors      = document.querySelectorAll('.join-connector-fill');
    var prevBtn         = document.getElementById('join-prev-btn');
    var nextBtn         = document.getElementById('join-next-btn');
    var submitBtn       = document.getElementById('join-submit-btn');
    var currentStepInput = document.getElementById('join-current-step');

    @if(old('name') || old('phone_number') || old('email') || old('birth_date'))
      currentStep = 1;
    @elseif(old('goals') || old('kondisi_khusus') || old('referensi') || old('pengalaman') || old('is_muslim'))
      currentStep = 2;
    @elseif(old('password'))
      currentStep = 3;
    @endif

    function goToStep(step) {
      if (step < 1 || step > totalSteps) return;
      currentStep = step;
      if (currentStepInput) currentStepInput.value = step;

      stepContents.forEach(function(el) {
        var s = parseInt(el.getAttribute('data-step'));
        if (s === step) {
          el.classList.remove('hidden');
          el.style.animation = 'none';
          el.offsetHeight;
          el.style.animation = 'joinFadeIn 0.4s ease-out';
        } else {
          el.classList.add('hidden');
        }
      });

      stepDots.forEach(function(dot) {
        var s = parseInt(dot.getAttribute('data-step'));
        dot.classList.remove('active', 'completed');
        if (s === step) dot.classList.add('active');
        else if (s < step) dot.classList.add('completed');
      });

      stepLabels.forEach(function(label) {
        var s = parseInt(label.getAttribute('data-step'));
        label.classList.remove('active', 'completed');
        if (s === step) label.classList.add('active');
        else if (s < step) label.classList.add('completed');
      });

      benefits.forEach(function(b) {
        var s = parseInt(b.getAttribute('data-step'));
        b.classList.toggle('active', s === step);
      });

      connectors.forEach(function(conn, idx) {
        var pct = 0;
        if (idx + 2 === step) pct = 50;
        else if (idx + 2 <= step) pct = 100;
        conn.style.width = pct + '%';
      });

      if (prevBtn) prevBtn.classList.toggle('hidden', step === 1);
      if (nextBtn) nextBtn.classList.toggle('hidden', step === totalSteps);
      if (submitBtn) submitBtn.classList.toggle('hidden', step !== totalSteps);
    }

    function validateStep(step) {
      if (step === 1) {
        var name  = document.getElementById('join_name');
        var phone = document.getElementById('join_phone');
        var email = document.getElementById('join_email');
        var birth = document.getElementById('join_birth');
        if (!name.value.trim())   { shakeField(name);   return false; }
        if (!phone.value.trim())  { shakeField(phone);  return false; }
        if (!email.value.trim())  { shakeField(email);  return false; }
        if (!birth.value)         { shakeField(birth);  return false; }
        return true;
      }
      if (step === 2) {
        var muslim = document.getElementById('join_muslim');
        if (!muslim.value) { shakeField(muslim); return false; }
        return true;
      }
      if (step === 3) {
        var pw  = document.getElementById('join_password');
        var pw2 = document.getElementById('join_password_confirmation');
        if (!pw.value || pw.value.length < 8) { shakeField(pw); showPwMatch('Password minimal 8 karakter', false); return false; }
        if (pw.value !== pw2.value) { shakeField(pw2); showPwMatch('Konfirmasi password tidak cocok', false); return false; }
        return true;
      }
      return true;
    }

    function shakeField(el) {
      el.style.borderColor = '#EE4E8B';
      el.style.animation = 'none';
      el.offsetHeight;
      el.style.animation = 'joinShake 0.4s ease';
      setTimeout(function() { el.style.borderColor = ''; }, 600);
    }

    var pw1 = document.getElementById('join_password');
    var pw2 = document.getElementById('join_password_confirmation');
    var pwMatch = document.getElementById('join-pw-match');

    function showPwMatch(msg, ok) {
      if (!pwMatch) return;
      pwMatch.classList.remove('hidden');
      pwMatch.textContent = msg;
      pwMatch.className = 'font-poppins text-[11px] mt-1.5 ' + (ok ? 'text-patina-green' : 'text-primary');
    }

    function checkPwMatch() {
      if (!pw1 || !pw2 || !pwMatch) return;
      if (!pw2.value) { pwMatch.classList.add('hidden'); return; }
      showPwMatch(pw1.value === pw2.value ? 'Password cocok' : 'Password belum cocok', pw1.value === pw2.value);
    }

    if (pw1 && pw2) {
      pw1.addEventListener('input', checkPwMatch);
      pw2.addEventListener('input', checkPwMatch);
    }

    document.querySelectorAll('.join-toggle-pw').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var input = document.getElementById(this.getAttribute('data-target'));
        var icon = this.querySelector('i');
        if (!input) return;
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.remove('ri-eye-line');
          icon.classList.add('ri-eye-off-line');
        } else {
          input.type = 'password';
          icon.classList.remove('ri-eye-off-line');
          icon.classList.add('ri-eye-line');
        }
      });
    });

    if (nextBtn) {
      nextBtn.addEventListener('click', function() {
        if (!validateStep(currentStep)) return;
        if (currentStep < totalSteps) goToStep(currentStep + 1);
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function() {
        if (currentStep > 1) goToStep(currentStep - 1);
      });
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        var active = document.activeElement;
        if (active && active.closest('.join-step-content') && nextBtn && !nextBtn.classList.contains('hidden')) {
          e.preventDefault();
          nextBtn.click();
        }
      }
    });

    goToStep(currentStep);

    window.closeJoinErrorPopup = function() {
      var popup = document.getElementById('join-error-popup');
      if (popup) popup.remove();
    };
    window.closeJoinSuccessPopup = function() {
      var popup = document.getElementById('join-success-popup');
      if (popup) popup.remove();
    };
  })();
  </script>
</body>
</html>
