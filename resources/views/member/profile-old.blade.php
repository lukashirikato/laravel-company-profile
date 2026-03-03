
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- favicon  -->
    <link rel="icon" type="image/png" href="{{ asset('icon/favicon.jpg') }}" />
    <!-- end favicon  -->
    <title>FTM SOCIETY - Muslimah-Only Gym</title>
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    
    <!-- Remix Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" />
    
    <!-- Compiled CSS via Vite (Production TailwindCSS) -->
    @vite(['resources/css/member.css'])
    
    <!-- Hero Background Style (dynamic) -->
    <style>
      .hero-section {
        background-image: linear-gradient(rgba(74, 43, 48, 0.7), rgba(74, 43, 48, 0.7)), url('{{ asset('images/IMG_0278.jpg') }}');
        background-size: cover;
        background-position: center;
      }
    </style>
  </head>
  <body class="bg-white text-gray-800">
    
      <!-- Desktop Navigation -->


        <!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\member\profile.blade.php -->
<!-- HEADER -->
<header class="fixed w-full bg-white bg-opacity-95 shadow-sm z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">

        <!-- LOGO -->
        <a href="{{ route('member.dashboard') }}" class="logo text-primary text-2xl font-semibold hover:text-secondary transition">
            FTM SOCIETY
        </a>

        <!-- DESKTOP NAVIGATION -->
        <nav class="hidden md:flex items-center space-x-8">

            <!-- MENU UMUM -->
            <a href="#home" class="text-gray-700 hover:text-primary transition">Home</a>
            <a href="#about" class="text-gray-700 hover:text-primary transition">About</a>
            <a href="#Programs" class="text-gray-700 hover:text-primary transition">Programs</a>
            <a href="#Packages" class="text-gray-700 hover:text-primary transition">Packages</a>
            <a href="#classes" class="text-gray-700 hover:text-primary transition">Classes</a>
            <a href="#schedule" class="text-gray-700 hover:text-primary transition">Schedule</a>
            <a href="#Facility" class="text-gray-700 hover:text-primary transition">Gallery</a>
            <a href="#contact" class="text-gray-700 hover:text-primary transition">Contact</a>

            <!-- PROFIL -->
            <a href="{{ route('member.profile.modal') }}"
   class="flex items-center gap-2 text-gray-700 hover:text-primary transition">
    <i class="ri-user-3-line text-xl"></i> Profil
</a>


            <!-- LOGIN / LOGOUT -->
            @auth('customer')
                <button
                    type="button"
                    onclick="showLogoutModal()"
                    class="bg-red-600 text-white px-6 py-2 rounded-button font-semibold hover:bg-red-700 transition"
                >
                    Logout
                </button>
            @else
                <a href="{{ route('member.login') }}"
                    class="bg-primary text-white px-6 py-2 rounded-button hover:bg-secondary hover:scale-105 transition font-semibold">
                    Login
                </a>
            @endauth

        </nav>

        <!-- Mobile button (visible on small screens) -->
        <div class="md:hidden flex items-center">
          <button id="mobile-menu-button"
              class="w-10 h-10 flex items-center justify-center text-primary"
              aria-label="Toggle mobile menu">
            <i class="ri-menu-line ri-lg"></i>
          </button>
        </div>

      </div>
    </header>


<div id="logout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full p-8 relative flex flex-col items-center">
        <h3 class="text-xl font-bold text-primary mb-4">Konfirmasi Logout</h3>
        <p class="mb-6 text-gray-700 text-center">Anda yakin ingin logout?<br>Pilih "Reset Password" jika ingin mengganti password Anda.</p>
        <div class="flex flex-col sm:flex-row gap-3 w-full">
            <form method="POST" action="{{ route('member.logout') }}" class="w-full">
                @csrf
                <button type="submit"
    class="w-full bg-red-600 text-white px-4 py-2 rounded-button font-semibold">
    Logout
</button>
</form>
<a href="{{ route('member.password.form') }}"
    class="w-full bg-primary text-white px-4 py-2 rounded-button font-semibold text-center">
    Reset Password
</a>
        </div>
        <button type="button" onclick="closeLogoutModal()"
            class="mt-4 w-full px-4 py-2 rounded-button border border-gray-300 text-gray-700 hover:bg-gray-100 transition font-semibold">
            Batal
        </button>
    </div>
</div>

    <!-- Mobile Navigation + Backdrop -->
    <div id="mobile-backdrop" class="fixed inset-0 bg-black bg-opacity-40 hidden" style="z-index:9998; transition: opacity .25s; pointer-events:none;"></div>
    <div id="mobile-menu" class="mobile-menu fixed top-16 bottom-0 right-0 w-72 bg-white shadow-lg p-6 transform overflow-y-auto" style="z-index:9999;">
      <div class="flex items-center justify-between mb-6">
        <a href="{{ route('member.dashboard') }}" class="logo text-primary font-semibold hover:text-secondary transition">FTM SOCIETY</a>
        <button id="close-menu-button" type="button" aria-label="Tutup menu" class="w-9 h-9 inline-flex items-center justify-center rounded-md text-gray-700 hover:bg-gray-100" style="position:relative; z-index:10001; pointer-events:auto;">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <nav class="flex flex-col gap-3">
        @auth('customer')
          <a href="#" onclick="showProfilePopup(); return false;" class="block px-4 py-3 rounded-md text-gray-800 hover:bg-primary hover:text-white transition font-semibold">Profile</a>
          <a href="{{ route('member.password.form') }}" class="block px-4 py-3 rounded-md text-gray-800 hover:bg-primary hover:text-white transition font-semibold">Ubah Password</a>
          <form method="POST" action="{{ route('member.logout') }}" class="px-4">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-3 rounded-md text-gray-800 hover:bg-red-600 hover:text-white transition font-semibold">Logout ({{ Auth::guard('customer')->user()->name }})</button>
          </form>
        @else
          <a href="{{ route('member.login') }}" class="block px-4 py-3 rounded-md text-gray-800 hover:bg-primary hover:text-white transition font-semibold">Login</a>
          <a href="{{ route('member.register') }}" class="block px-4 py-3 rounded-md text-gray-800 hover:bg-primary hover:text-white transition font-semibold">Register</a>
        @endauth

        <hr class="my-2">
        <a href="{{ route('member.dashboard') }}" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">Dashboard</a>
        <a href="{{ route('member.account') }}" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">My QR Card</a>
        <a href="{{ route('member.attendance') }}" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">Attendance</a>
        <a href="#about" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">About</a>
        <a href="#Programs" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">Programs</a>
        <a href="#Classes" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">Classes</a>
        <a href="#schedule" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">Schedule</a>
        <a href="#Facility" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">Facility</a>
        <a href="#contact" class="block px-4 py-3 rounded-md text-gray-700 hover:bg-primary hover:text-white transition">Contact</a>

      </nav>
    </div>

  <!-- ========================================= -->
<!-- HERO SECTION - REFACTORED & PROFESSIONAL -->
<!-- ========================================= -->

<section id="home" class="relative min-h-screen flex items-center overflow-hidden">
    
    <!-- Background Image Layer -->
    <div class="absolute inset-0 z-0">
        <div id="hero-bg" 
             class="absolute inset-0 bg-cover bg-center transition-all duration-700 scale-105"
             style="background-image: url('/images/bg.JPG');"></div>
        
        <!-- Enhanced Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#4a2b30]/90 via-[#6a1b4d]/75 to-transparent"></div>
    </div>

    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -right-48 w-96 h-96 bg-secondary/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-1/4 -left-48 w-96 h-96 bg-primary/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 1.5s;"></div>
    </div>

    <!-- Main Content Container -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center min-h-[calc(100vh-80px)]">
            
            <!-- LEFT COLUMN - Text Content (Enhanced) -->
            <div class="lg:col-span-7 text-center lg:text-left space-y-6 md:space-y-8">
                
                <!-- Premium Badge -->
                <div data-aos="fade-right" 
                     class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-white/10 backdrop-blur-md border border-white/20 shadow-xl hover:bg-white/20 transition-all duration-300">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-secondary"></span>
                    </span>
                    <span class="text-white text-sm md:text-base font-medium tracking-wide">
                        Muslimah-Only Fitness Community
                    </span>
                </div>

                <!-- Main Heading - Enhanced Typography -->
                <div data-aos="fade-right" data-aos-delay="100" class="space-y-4">
                    <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white leading-[1.1] tracking-tight">
                        YOUR<br/>
                        <span class="relative inline-block my-2">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary via-pink-300 to-secondary bg-[length:200%_auto] animate-gradient-shift">
                                PRODUCTIVE
                            </span>
                            <!-- Decorative Underline -->
                            <svg class="absolute -bottom-2 left-0 w-full h-3" viewBox="0 0 200 8" fill="none">
                                <path d="M1 5.5C20 3.5 50 1 100 5.5C150 1 180 3.5 199 5.5" 
                                      stroke="#c68e8f" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </span><br/>
                        SISTER
                    </h1>
                </div>

                <!-- Subtitle - Enhanced -->
                <div data-aos="fade-right" data-aos-delay="200" class="space-y-2 pt-2">
                    <p class="text-xl sm:text-2xl md:text-3xl text-white/95 font-light leading-relaxed">
                        Good Habit inside
                    </p>
                    <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-secondary drop-shadow-lg">
                        Productive Muslimah
                    </p>
                </div>

                <!-- CTA Button - Enhanced -->
                <div data-aos="fade-right" data-aos-delay="300" 
                     class="flex flex-wrap gap-4 justify-center lg:justify-start pt-4">
                    @auth('customer')
                    @else
                        <a href="#join" 
                           class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 text-lg font-bold text-primary bg-white rounded-full overflow-hidden shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-secondary/50">
                            <span class="relative z-10">Join Now</span>
                            <i class="ri-arrow-right-line text-xl relative z-10 transition-transform group-hover:translate-x-1"></i>
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-pink-200 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </a>
                    @endauth
                    
                    <a href="#about" 
                       class="group inline-flex items-center justify-center gap-3 px-8 py-4 text-lg font-bold text-white bg-transparent border-2 border-white rounded-full transition-all duration-300 hover:bg-white hover:text-primary hover:scale-105">
                        <span>Learn More</span>
                        <i class="ri-arrow-down-line text-xl transition-transform group-hover:translate-y-1"></i>
                    </a>
                </div>

                

           
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 hidden md:block">
        <a href="#about" class="flex flex-col items-center gap-2 text-white/60 hover:text-white transition-all animate-bounce cursor-pointer">
            <span class="text-xs uppercase tracking-widest font-medium">Scroll</span>
            <i class="ri-arrow-down-line text-2xl"></i>
        </a>
    </div>

</section>

<!-- ========================================= -->
<!-- ABOUT SECTION - ULTIMATE PROFESSIONAL VERSION -->
<!-- Perfectly Refined & Beautiful UI/UX -->
<!-- ========================================= -->

<section id="about" class="relative py-20 md:py-28 lg:py-32 overflow-hidden">
    
  <!-- Multi-Layer Background -->
  <div class="absolute inset-0 bg-gradient-to-br from-white via-gray-50/50 to-pink-50/30"></div>
    
  <!-- Animated Decorative Background Pattern -->
  <div class="absolute inset-0 opacity-[0.03] pointer-events-none">
    <div class="absolute inset-0" 
       style="background-image: 
        radial-gradient(circle at 20% 50%, transparent 0%, transparent 10%, #c68e8f 10%, #c68e8f 11%, transparent 11%),
        radial-gradient(circle at 80% 80%, transparent 0%, transparent 10%, #4a2b30 10%, #4a2b30 11%, transparent 11%);
        background-size: 100px 100px;">
    </div>
  </div>

  <!-- Floating Gradient Orbs -->
  <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
    <div class="absolute -top-48 -left-48 w-96 h-96 bg-gradient-to-br from-secondary/20 to-pink-200/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 4s;"></div>
    <div class="absolute top-1/3 -right-64 w-[500px] h-[500px] bg-gradient-to-tl from-primary/15 to-purple-200/15 rounded-full blur-3xl animate-pulse" style="animation-duration: 6s; animation-delay: 1s;"></div>
    <div class="absolute -bottom-32 left-1/3 w-80 h-80 bg-gradient-to-tr from-pink-300/20 to-secondary/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 5s; animation-delay: 2s;"></div>
  </div>

  <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
    <!-- Section Header - Premium Design -->
    <div class="text-center mb-16 md:mb-24" data-aos="fade-up">
            
      <!-- Top Badge with Shimmer Effect -->
      <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-secondary/10 via-pink-100/50 to-secondary/10 rounded-full border border-secondary/20 shadow-lg backdrop-blur-sm">
        <div class="relative">
          <div class="w-2 h-2 bg-secondary rounded-full animate-ping absolute"></div>
          <div class="w-2 h-2 bg-secondary rounded-full relative"></div>
        </div>
        <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
          Tentang Kami
        </span>
      </div>

      <!-- Main Title with Gradient -->
      <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
        <span class="text-primary">About</span>
        <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-pink-400 animate-gradient-shift bg-[length:200%_auto]">
          FTM Society
        </span>
      </h2>

      <!-- Decorative Divider -->
      <div class="flex items-center justify-center gap-3 mb-6">
        <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-secondary rounded-full"></div>
        <div class="w-3 h-3 bg-secondary rounded-full animate-pulse"></div>
        <div class="w-24 h-1 bg-gradient-to-r from-secondary via-primary to-secondary rounded-full"></div>
        <div class="w-3 h-3 bg-primary rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
        <div class="w-16 h-0.5 bg-gradient-to-l from-transparent to-primary rounded-full"></div>
      </div>

      <!-- Subtitle -->
      <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed font-light">
        Ruang bagi muslimah untuk hidup <span class="font-semibold text-primary">aktif</span>, <span class="font-semibold text-secondary">produktif</span>, dan sesuai <span class="font-semibold text-primary">syariat</span>
      </p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

      <!-- LEFT COLUMN - Image Gallery (5 cols) -->
      <div class="lg:col-span-5 order-2 lg:order-1" data-aos="fade-right" data-aos-delay="100">
        <div class="relative">
                    
          <!-- Main Image Frame -->
          <div class="relative group">
                        
            <!-- Decorative Border Frame -->
            <div class="absolute -inset-4 bg-gradient-to-r from-secondary via-pink-300 to-primary rounded-[2rem] opacity-20 group-hover:opacity-30 blur-xl transition-all duration-500"></div>
                        
            <!-- Main Image Container -->
                        <div class="relative rounded-[2rem] overflow-hidden shadow-2xl ring-4 ring-white/50 transform group-hover:scale-[1.02] transition-all duration-500">
                          <img src="{{ asset('images/logo ftm (1).jpg') }}"
                             alt="FTM Society - Empowering Muslimah"
                             class="w-full h-auto object-cover" />
                            
              <!-- Gradient Overlay -->
              <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
              <!-- Hover Content -->
              <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500">
                <div class="text-center text-white transform scale-75 group-hover:scale-100 transition-transform duration-500">
                  <i class="ri-heart-pulse-fill text-5xl mb-3 drop-shadow-lg"></i>
                  <p class="text-lg font-bold drop-shadow-lg">Empowering Muslimah</p>
                </div>
              </div>
            </div>
          </div>

                    

          <!-- Floating Trust Badge -->
          <div class="absolute -top-6 -left-6 bg-white rounded-2xl shadow-xl p-4 border-2 border-secondary/20 backdrop-blur-sm hidden lg:block"
             data-aos="fade-down" data-aos-delay="300">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 bg-secondary rounded-full animate-pulse"></div>
              <span class="font-bold text-primary text-sm">Trusted Community</span>
            </div>
          </div>

          <!-- Decorative Blur Elements -->
          <div class="absolute -top-8 -left-8 w-32 h-32 bg-secondary/30 rounded-full blur-3xl -z-10"></div>
          <div class="absolute -bottom-8 -right-8 w-40 h-40 bg-primary/30 rounded-full blur-3xl -z-10"></div>
                    
        </div>
      </div>

      <!-- RIGHT COLUMN - Content (7 cols) -->
      <div class="lg:col-span-7 order-1 lg:order-2 space-y-8" data-aos="fade-left" data-aos-delay="200">
                
        <!-- Title with Icon -->
        <div class="flex items-start gap-4">
          <div class="flex-shrink-0 w-1 h-16 bg-gradient-to-b from-primary via-secondary to-pink-300 rounded-full"></div>
          <div>
            <h3 class="text-3xl md:text-4xl lg:text-5xl font-black text-primary mb-2">
              Vision & Mission
            </h3>
            <p class="text-sm text-gray-500 font-medium uppercase tracking-wider">Our Purpose & Goals</p>
          </div>
        </div>

        <!-- Description with Enhanced Typography -->
        <div class="space-y-5 pl-5">
          <p class="text-gray-700 leading-relaxed text-base md:text-lg relative">
            <span class="absolute -left-5 top-2 w-2 h-2 bg-secondary rounded-full"></span>
            <span class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">FTM Society</span> adalah memberikan ruang bagi para muslimah untuk memiliki gaya hidup <span class="font-semibold text-primary">aktif</span> dan <span class="font-semibold text-secondary">produktif</span> yang sesuai dengan syariat Islam.
          </p>
          <p class="text-gray-700 leading-relaxed text-base md:text-lg relative">
            <span class="absolute -left-5 top-2 w-2 h-2 bg-primary rounded-full"></span>
            Oleh karena itu, FTM Society hadir menyelenggarakan kegiatan olahraga dan kegiatan aktif sosial lainnya, seperti <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-secondary/10 text-secondary font-semibold rounded-md text-sm"><i class="ri-presentation-line"></i>webinar</span> dan <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-primary/10 text-primary font-semibold rounded-md text-sm"><i class="ri-calendar-event-line"></i>event</span>.
          </p>
        </div>

        <!-- Feature Cards Grid - Premium Design -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                    
          <!-- Feature Card 1 - Enhanced -->
          <div class="group relative bg-white rounded-2xl p-5 shadow-lg hover:shadow-2xl border border-gray-100 hover:border-secondary/30 transition-all duration-300 overflow-hidden cursor-pointer"
             data-aos="fade-up" data-aos-delay="300">
                        
            <!-- Gradient Background on Hover -->
            <div class="absolute inset-0 bg-gradient-to-br from-pink-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
            <div class="relative flex items-start gap-4">
              <!-- Icon Container -->
              <div class="relative flex-shrink-0">
                <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                  <i class="ri-shield-check-line text-3xl"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-secondary rounded-full border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </div>
                            
              <!-- Content -->
              <div class="flex-1">
                <h4 class="font-black text-gray-800 text-base mb-1 group-hover:text-primary transition-colors duration-300">
                  Muslimah Only
                </h4>
                <p class="text-sm text-gray-600 leading-relaxed">
                  100% Private & Safe Environment
                </p>
                <!-- Decorative Line -->
                <div class="mt-2 h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary to-transparent transition-all duration-500"></div>
              </div>
            </div>
          </div>

          <!-- Feature Card 2 - Enhanced -->
          <div class="group relative bg-white rounded-2xl p-5 shadow-lg hover:shadow-2xl border border-gray-100 hover:border-primary/30 transition-all duration-300 overflow-hidden cursor-pointer"
             data-aos="fade-up" data-aos-delay="400">
                        
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
            <div class="relative flex items-start gap-4">
              <div class="relative flex-shrink-0">
                <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                  <i class="ri-heart-pulse-line text-3xl"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-primary rounded-full border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </div>
                            
              <div class="flex-1">
                <h4 class="font-black text-gray-800 text-base mb-1 group-hover:text-primary transition-colors duration-300">
                  Certified Trainers
                </h4>
                <p class="text-sm text-gray-600 leading-relaxed">
                  Professional Muslimah Coaches
                </p>
                <div class="mt-2 h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary to-transparent transition-all duration-500"></div>
              </div>
            </div>
          </div>

          <!-- Feature Card 3 - Full Width Enhanced -->
          <div class="group relative bg-white rounded-2xl p-5 shadow-lg hover:shadow-2xl border border-gray-100 hover:border-secondary/30 transition-all duration-300 overflow-hidden cursor-pointer sm:col-span-2"
             data-aos="fade-up" data-aos-delay="500">
                        
            <div class="absolute inset-0 bg-gradient-to-br from-pink-50 via-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
            <div class="relative flex items-start gap-4">
              <div class="relative flex-shrink-0">
                <div class="w-16 h-16 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-600 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                  <i class="ri-pray-line text-3xl"></i>
                </div>
                <div class="absolute -top-1 -right-1 w-4 h-4 bg-secondary rounded-full border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              </div>
                            
              <div class="flex-1">
                <h4 class="font-black text-gray-800 text-base mb-1 group-hover:text-primary transition-colors duration-300">
                  No Music & No Camera
                </h4>
                <p class="text-sm text-gray-600 leading-relaxed">
                  Fully Islamic-Compliant Environment for Your Comfort
                </p>
                <div class="mt-2 h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-transparent transition-all duration-500"></div>
              </div>
            </div>
          </div>

        </div>

        <!-- CTA Section - Premium -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6" data-aos="fade-up" data-aos-delay="600">
                    
          
                    
        </div>

      </div>

    </div>

      </div>
    </div>

  </div>
</section>

    <!-- ========================================= -->
<!-- WHY CHOOSE FTM SECTION - ULTIMATE PROFESSIONAL -->
<!-- Enhanced Slider with Premium UI/UX -->
<!-- ========================================= -->

<section id="packages" class="relative py-20 md:py-28 lg:py-32 overflow-hidden">
    
    <!-- Multi-Layer Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-50 via-pink-50/30 to-white"></div>
    
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none">
        <div class="absolute inset-0" 
             style="background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 15px, #c68e8f 15px, #c68e8f 16px),
                repeating-linear-gradient(-45deg, transparent, transparent 15px, #4a2b30 15px, #4a2b30 16px);
                background-size: 80px 80px;">
        </div>
    </div>

    <!-- Floating Gradient Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-gradient-to-br from-secondary/20 to-pink-300/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 5s;"></div>
        <div class="absolute top-1/2 -right-48 w-96 h-96 bg-gradient-to-tl from-primary/15 to-purple-300/15 rounded-full blur-3xl animate-pulse" style="animation-duration: 7s; animation-delay: 1.5s;"></div>
        <div class="absolute -bottom-24 left-1/4 w-72 h-72 bg-gradient-to-tr from-pink-200/20 to-secondary/20 rounded-full blur-3xl animate-pulse" style="animation-duration: 6s; animation-delay: 0.5s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header - Premium Design -->
        <div class="text-center mb-16 md:mb-20" data-aos="fade-up">
            
            <!-- Top Badge -->
            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-lg backdrop-blur-sm">
                <div class="relative">
                    <div class="w-2 h-2 bg-primary rounded-full animate-ping absolute"></div>
                    <div class="w-2 h-2 bg-primary rounded-full relative"></div>
                </div>
                <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
                    Our Advantages
                </span>
            </div>

            <!-- Main Title -->
            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                <span class="text-primary">Why Choose</span>
                <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-secondary via-pink-400 to-secondary animate-gradient-shift bg-[length:200%_auto]">
                    FTM Society
                </span>
            </h2>

            <!-- Decorative Divider -->
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                <div class="w-24 h-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-secondary rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                <div class="w-16 h-0.5 bg-gradient-to-l from-transparent to-secondary rounded-full"></div>
            </div>

            <!-- Subtitle -->
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Temukan keunggulan yang membuat FTM Society menjadi pilihan terbaik untuk muslimah aktif
            </p>
        </div>

        <!-- Slider Container -->
        <div class="relative max-w-7xl mx-auto">
            
            <!-- Navigation Button Left -->
            <button
                type="button"
                onclick="slideFeature(-1)"
                class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 items-center justify-center w-14 h-14 bg-white text-primary rounded-full shadow-xl hover:shadow-2xl hover:bg-gradient-to-br hover:from-primary hover:to-secondary hover:text-white transition-all duration-300 z-20 group"
                aria-label="Scroll Left"
                id="featureScrollLeft"
                style="display:none"
            >
                <i class="ri-arrow-left-s-line text-3xl group-hover:scale-110 transition-transform"></i>
            </button>

            <!-- Slider Track -->
            <div
                id="feature-slider"
                class="flex overflow-x-auto gap-6 md:gap-8 scroll-smooth pb-6 px-2"
                style="scrollbar-width: none; -ms-overflow-style: none;"
                onscroll="toggleFeatureScroll()"
            >
                <!-- Feature Card 1 - Enhanced -->
                <div class="min-w-[300px] sm:min-w-[340px] max-w-[360px] flex-shrink-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-secondary/30 overflow-hidden h-full flex flex-col">
                        
                        <!-- Gradient Background on Hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-pink-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="relative z-10 flex flex-col items-center text-center flex-1">
                            
                            <!-- Icon Container -->
                            <div class="relative mb-6">
                                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                                    <i class="ri-women-line text-4xl"></i>
                                </div>
                                <!-- Decorative Ring -->
                                <div class="absolute -inset-2 rounded-2xl border-2 border-secondary/20 group-hover:border-secondary/40 group-hover:scale-110 transition-all duration-500"></div>
                                <!-- Floating Dot -->
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-secondary rounded-full border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl md:text-2xl font-black text-primary mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                                Muslimah-Only Space
                            </h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm md:text-base leading-relaxed flex-1">
                                Fasilitas kami hanya untuk wanita, dengan staf wanita saja. Nikmati privasi lengkap tanpa jendela yang menghadap area publik dan sistem masuk yang aman.
                            </p>

                            <!-- Bottom Accent Line -->
                            <div class="mt-6 h-1 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Feature Card 2 - Enhanced -->
                <div class="min-w-[300px] sm:min-w-[340px] max-w-[360px] flex-shrink-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-primary/30 overflow-hidden h-full flex flex-col">
                        
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center flex-1">
                            
                            <div class="relative mb-6">
                                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                                    <i class="ri-user-star-line text-4xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-2xl border-2 border-primary/20 group-hover:border-primary/40 group-hover:scale-110 transition-all duration-500"></div>
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-primary rounded-full border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                            </div>

                            <h3 class="text-xl md:text-2xl font-black text-primary mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                                Certified Muslimah Trainer
                            </h3>

                            <p class="text-gray-600 text-sm md:text-base leading-relaxed flex-1">
                                Dibimbing langsung oleh coach tersertifikasi dengan pengalaman profesional dan pemahaman mendalam tentang kebutuhan muslimah.
                            </p>

                            <div class="mt-6 h-1 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Feature Card 3 - Enhanced -->
                <div class="min-w-[300px] sm:min-w-[340px] max-w-[360px] flex-shrink-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-secondary/30 overflow-hidden h-full flex flex-col">
                        
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center flex-1">
                            
                            <div class="relative mb-6">
                                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                                    <i class="ri-shield-user-line text-4xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-2xl border-2 border-secondary/20 group-hover:border-secondary/40 group-hover:scale-110 transition-all duration-500"></div>
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-secondary rounded-full border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                            </div>

                            <h3 class="text-xl md:text-2xl font-black text-primary mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                                Privacy is Our Priority
                            </h3>

                            <p class="text-gray-600 text-sm md:text-base leading-relaxed flex-1">
                                Ruang latihan khusus muslimah, tanpa kamera dan tanpa musik. Kami mengutamakan kenyamanan, keamanan, dan privasimu saat berolahraga.
                            </p>

                            <div class="mt-6 h-1 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

                <!-- Feature Card 4 - Enhanced -->
                <div class="min-w-[300px] sm:min-w-[340px] max-w-[360px] flex-shrink-0" data-aos="fade-up" data-aos-delay="400">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-primary/30 overflow-hidden h-full flex flex-col">
                        
                        <div class="absolute inset-0 bg-gradient-to-br from-rose-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center flex-1">
                            
                            <div class="relative mb-6">
                                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-rose-100 to-rose-200 text-rose-600 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                                    <i class="ri-user-heart-line text-4xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-2xl border-2 border-primary/20 group-hover:border-primary/40 group-hover:scale-110 transition-all duration-500"></div>
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-primary rounded-full border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-pulse"></div>
                            </div>

                            <h3 class="text-xl md:text-2xl font-black text-primary mb-4 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                                Muslimah Friendly
                            </h3>

                            <p class="text-gray-600 text-sm md:text-base leading-relaxed flex-1">
                                Dirancang khusus untuk muslimah: area khusus wanita, pelatih perempuan bersertifikat, dan suasana nyaman sesuai nilai-nilai islami.
                            </p>

                            <div class="mt-6 h-1 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-500"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Navigation Button Right -->
            <button
                type="button"
                onclick="slideFeature(1)"
                class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 items-center justify-center w-14 h-14 bg-white text-primary rounded-full shadow-xl hover:shadow-2xl hover:bg-gradient-to-br hover:from-primary hover:to-secondary hover:text-white transition-all duration-300 z-20 group"
                aria-label="Scroll Right"
                id="featureScrollRight"
            >
                <i class="ri-arrow-right-s-line text-3xl group-hover:scale-110 transition-transform"></i>
            </button>

        </div>

        <!-- Slider Indicators (Optional) -->
        <div class="flex justify-center gap-2 mt-12" data-aos="fade-up" data-aos-delay="500">
            <div class="w-2 h-2 bg-primary rounded-full"></div>
            <div class="w-8 h-2 bg-secondary rounded-full"></div>
            <div class="w-2 h-2 bg-primary/40 rounded-full"></div>
            <div class="w-2 h-2 bg-primary/40 rounded-full"></div>
        </div>

        <!-- Bottom CTA (Optional) -->
        <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="600">
            <a href="#join" 
               class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary via-secondary to-primary bg-[length:200%_auto] text-white font-bold rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300 group">
                <span>Bergabung Sekarang</span>
                <i class="ri-arrow-right-line text-xl group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

    </div>
</section>

<!-- ========================================= -->
<!-- PROGRAMS SECTION - FIXED & STABLE        -->
<!-- Cards tidak bergoyang, slider smooth      -->
<!-- ========================================= -->

<section id="Programs" class="relative py-20 md:py-28 lg:py-32 overflow-hidden">
    
  <!-- Multi-Layer Background -->
  <div class="absolute inset-0 bg-gradient-to-b from-white via-gray-50/50 to-pink-50/30"></div>
    
  <!-- Animated Background Pattern -->
  <div class="absolute inset-0 opacity-[0.02] pointer-events-none">
    <div class="absolute inset-0" 
       style="background-image: 
        radial-gradient(circle at 25% 25%, transparent 0%, transparent 12%, #c68e8f 12%, #c68e8f 13%, transparent 13%),
        radial-gradient(circle at 75% 75%, transparent 0%, transparent 12%, #4a2b30 12%, #4a2b30 13%, transparent 13%);
        background-size: 120px 120px;">
    </div>
  </div>

  <!-- Floating Gradient Orbs -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-20 -left-40 w-96 h-96 bg-gradient-to-br from-secondary/15 to-pink-200/15 rounded-full blur-3xl animate-pulse" style="animation-duration: 6s;"></div>
    <div class="absolute -top-20 right-1/4 w-80 h-80 bg-gradient-to-tl from-primary/10 to-purple-200/10 rounded-full blur-3xl animate-pulse" style="animation-duration: 8s; animation-delay: 2s;"></div>
    <div class="absolute bottom-0 left-1/3 w-72 h-72 bg-gradient-to-tr from-pink-300/15 to-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-duration: 7s; animation-delay: 1s;"></div>
  </div>

  <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
    <!-- Section Header -->
    <div class="text-center mb-16 md:mb-20">
            
      <!-- Top Badge -->
      <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-secondary/10 via-pink-100/50 to-secondary/10 rounded-full border border-secondary/20 shadow-lg backdrop-blur-sm">
        <div class="relative">
          <div class="w-2 h-2 bg-secondary rounded-full animate-ping absolute"></div>
          <div class="w-2 h-2 bg-secondary rounded-full relative"></div>
        </div>
        <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
          Our Program 
        </span>
      </div>

      <!-- Main Title -->
      <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-secondary via-pink-400 to-secondary animate-gradient-shift bg-[length:200%_auto]">
          Programs
        </span>
      </h2>

      <!-- Decorative Divider -->
      <div class="flex items-center justify-center gap-3 mb-6">
        <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-secondary rounded-full"></div>
        <div class="w-3 h-3 bg-secondary rounded-full animate-pulse"></div>
        <div class="w-24 h-1 bg-gradient-to-r from-secondary via-primary to-secondary rounded-full"></div>
        <div class="w-3 h-3 bg-primary rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
        <div class="w-16 h-0.5 bg-gradient-to-l from-transparent to-primary rounded-full"></div>
      </div>

      <!-- Subtitle -->
      <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
        Temukan program yang sesuai dengan kebutuhan dan gaya hidup Anda
      </p>
    </div>

    <!-- Slider Container -->
    <div class="relative max-w-7xl mx-auto">
            
      <!-- Navigation Button Left -->
      <button
        type="button"
        onclick="slideService(-1)"
        class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 items-center justify-center w-14 h-14 bg-white text-primary rounded-full shadow-xl hover:shadow-2xl hover:bg-gradient-to-br hover:from-primary hover:to-secondary hover:text-white transition-all duration-300 z-20 group"
        aria-label="Scroll Left"
        id="serviceScrollLeft"
        style="display:none"
      >
        <i class="ri-arrow-left-s-line text-3xl group-hover:scale-110 transition-transform"></i>
      </button>

      <!-- Slider Track -->
      <div
        id="service-slider"
        class="flex items-stretch overflow-x-auto gap-6 md:gap-8 scroll-smooth pb-6 px-2"
        style="scrollbar-width: none; -ms-overflow-style: none;"
        onscroll="toggleServiceScroll()"
      >
        <!-- ================================ -->
        <!-- Card 1: Private Group Class      -->
        <!-- FIX: Hapus data-aos dari wrapper -->
        <!-- ================================ -->
        <div class="min-w-[85vw] sm:min-w-[300px] max-w-[320px] flex-shrink-0 flex">
          <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300 border-2 border-gray-100 hover:border-secondary/30 overflow-hidden w-full flex flex-col">
                        
            <div class="absolute inset-0 bg-gradient-to-br from-pink-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        
            <div class="relative z-10 flex flex-col items-center flex-1">
                            
              <!-- Icon — hanya icon yg bergerak, bukan card -->
              <div class="relative mb-6">
                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 shadow-lg transition-transform duration-300 group-hover:scale-110" style="will-change:transform;">
                  <i class="ri-team-line text-4xl"></i>
                </div>
                <div class="absolute -inset-2 rounded-2xl border-2 border-secondary/20 group-hover:border-secondary/40 transition-colors duration-300"></div>
                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-secondary to-pink-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  Popular
                </div>
              </div>

              <h4 class="text-xl font-black text-primary mb-3 text-center group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                Private Group Class
              </h4>

              <p class="text-gray-600 text-sm leading-relaxed text-center mb-6 flex-1">
                Latihan kelompok privat dengan instruktur berpengalaman, cocok untuk komunitas atau teman-teman.
              </p>

              <div class="w-full mb-6 space-y-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-secondary"></i>
                  <span>Max 8-10 orang</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-secondary"></i>
                  <span>Jadwal fleksibel</span>
                </div>
              </div>

              <!-- Bottom Accent -->
              <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-300 mb-3"></div>

              <!-- FIX: Hapus hover:scale-105 dari button -->
              <a href="https://wa.me/6287785767395" target="_blank" 
                 class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full hover:shadow-xl transition-shadow duration-300 text-sm font-bold text-center flex items-center justify-center gap-2 mt-auto">
                <i class="ri-whatsapp-line text-lg"></i>
                <span>Booking Sekarang</span>
              </a>
            </div>
          </div>
        </div>

        <!-- ================================ -->
        <!-- Card 2: Private Training         -->
        <!-- ================================ -->
        <div class="min-w-[85vw] sm:min-w-[300px] max-w-[320px] flex-shrink-0 flex">
          <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300 border-2 border-gray-100 hover:border-primary/30 overflow-hidden w-full flex flex-col">
                        
            <div class="absolute inset-0 bg-gradient-to-br from-purple-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        
            <div class="relative z-10 flex flex-col items-center flex-1">
                            
              <div class="relative mb-6">
                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600 shadow-lg transition-transform duration-300 group-hover:scale-110" style="will-change:transform;">
                  <i class="ri-user-heart-line text-4xl"></i>
                </div>
                <div class="absolute -inset-2 rounded-2xl border-2 border-primary/20 group-hover:border-primary/40 transition-colors duration-300"></div>
                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-primary to-purple-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  1-on-1
                </div>
              </div>

              <h4 class="text-xl font-black text-primary mb-3 text-center group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                Private Training
              </h4>

              <p class="text-gray-600 text-sm leading-relaxed text-center mb-6 flex-1">
                Sesi latihan personal sesuai kebutuhan Anda, didampingi pelatih profesional untuk hasil optimal.
              </p>

              <div class="w-full mb-6 space-y-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-primary"></i>
                  <span>Personal attention</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-primary"></i>
                  <span>Custom program</span>
                </div>
              </div>

              <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-300 mb-3"></div>

              <a href="https://wa.me/6287785767395" target="_blank" 
                 class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full hover:shadow-xl transition-shadow duration-300 text-sm font-bold text-center flex items-center justify-center gap-2 mt-auto">
                <i class="ri-whatsapp-line text-lg"></i>
                <span>Booking Sekarang</span>
              </a>
            </div>
          </div>
        </div>

        <!-- ================================ -->
        <!-- Card 3: Single Visit Class       -->
        <!-- ================================ -->
        <div class="min-w-[85vw] sm:min-w-[300px] max-w-[320px] flex-shrink-0 flex">
          <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300 border-2 border-gray-100 hover:border-secondary/30 overflow-hidden w-full flex flex-col">
                        
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        
            <div class="relative z-10 flex flex-col items-center flex-1">
                            
              <div class="relative mb-6">
                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 shadow-lg transition-transform duration-300 group-hover:scale-110" style="will-change:transform;">
                  <i class="ri-calendar-check-line text-4xl"></i>
                </div>
                <div class="absolute -inset-2 rounded-2xl border-2 border-secondary/20 group-hover:border-secondary/40 transition-colors duration-300"></div>
                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-secondary to-blue-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  Flexible
                </div>
              </div>

              <h4 class="text-xl font-black text-primary mb-3 text-center group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                Single Visit Class
              </h4>

              <p class="text-gray-600 text-sm leading-relaxed text-center mb-6 flex-1">
                Ikuti kelas tanpa harus menjadi member tetap. Fleksibel untuk Anda yang ingin mencoba atau punya jadwal padat.
              </p>

              <div class="w-full mb-6 space-y-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-secondary"></i>
                  <span>No commitment</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-secondary"></i>
                  <span>Try first</span>
                </div>
              </div>

              <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-300 mb-3"></div>

              <a href="https://wa.me/6287785767395" target="_blank" 
                 class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full hover:shadow-xl transition-shadow duration-300 text-sm font-bold text-center flex items-center justify-center gap-2 mt-auto">
                <i class="ri-whatsapp-line text-lg"></i>
                <span>Booking Sekarang</span>
              </a>
            </div>
          </div>
        </div>

        <!-- ================================ -->
        <!-- Card 4: Reformer Pilates         -->
        <!-- ================================ -->
        <div class="min-w-[85vw] sm:min-w-[300px] max-w-[320px] flex-shrink-0 flex">
          <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300 border-2 border-gray-100 hover:border-primary/30 overflow-hidden w-full flex flex-col">
                        
            <div class="absolute inset-0 bg-gradient-to-br from-green-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        
            <div class="relative z-10 flex flex-col items-center flex-1">
                            
              <div class="relative mb-6">
                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-green-100 to-green-200 text-green-600 shadow-lg transition-transform duration-300 group-hover:scale-110" style="will-change:transform;">
                  <i class="ri-group-line text-4xl"></i>
                </div>
                <div class="absolute -inset-2 rounded-2xl border-2 border-primary/20 group-hover:border-primary/40 transition-colors duration-300"></div>
                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-primary to-green-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  Trending
                </div>
              </div>

              <h4 class="text-xl font-black text-primary mb-3 text-center group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                Reformer Pilates
              </h4>

              <p class="text-gray-600 text-sm leading-relaxed text-center mb-6 flex-1">
                Latihan pilates dengan alat reformer untuk kekuatan, fleksibilitas, dan postur tubuh yang lebih baik.
              </p>

              <div class="w-full mb-6 space-y-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-primary"></i>
                  <span>Alat reformer</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-primary"></i>
                  <span>Improve posture</span>
                </div>
              </div>

              <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-300 mb-3"></div>

              <a href="https://wa.me/6287785767395" target="_blank" 
                 class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full hover:shadow-xl transition-shadow duration-300 text-sm font-bold text-center flex items-center justify-center gap-2 mt-auto">
                <i class="ri-whatsapp-line text-lg"></i>
                <span>Booking Sekarang</span>
              </a>
            </div>
          </div>
        </div>

        <!-- ================================ -->
        <!-- Card 5: Exclusive Class Program  -->
        <!-- FIX: Hapus hover:scale-105 btn  -->
        <!-- ================================ -->
        <div class="min-w-[85vw] sm:min-w-[300px] max-w-[320px] flex-shrink-0 flex">
          <div class="group relative bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-shadow duration-300 border-2 border-gray-100 hover:border-secondary/30 overflow-hidden w-full flex flex-col">
                        
            <div class="absolute inset-0 bg-gradient-to-br from-amber-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        
            <div class="relative z-10 flex flex-col items-center flex-1">
                            
              <div class="relative mb-6">
                <div class="w-20 h-20 flex items-center justify-center rounded-2xl bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 shadow-lg transition-transform duration-300 group-hover:scale-110" style="will-change:transform;">
                  <i class="ri-award-line text-4xl"></i>
                </div>
                <div class="absolute -inset-2 rounded-2xl border-2 border-secondary/20 group-hover:border-secondary/40 transition-colors duration-300"></div>
                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-secondary to-amber-400 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  Premium
                </div>
              </div>

              <h4 class="text-xl font-black text-primary mb-3 text-center group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary transition-all duration-300">
                Exclusive Class Program
              </h4>

              <p class="text-gray-600 text-sm leading-relaxed text-center mb-6 flex-1">
                Program kelas eksklusif dengan materi pilihan, peserta terbatas, dan pendampingan intensif.
              </p>

              <div class="w-full mb-6 space-y-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-secondary"></i>
                  <span>Limited seats</span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="ri-check-line text-secondary"></i>
                  <span>Intensive coaching</span>
                </div>
              </div>

              <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-300 mb-3"></div>

              <!-- FIX: hover:scale-105 dihapus -->
              <a href="https://wa.me/6287785767395" target="_blank" 
                 class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-3 rounded-full hover:shadow-xl transition-shadow duration-300 text-sm font-bold text-center flex items-center justify-center gap-2 mt-auto">
                <i class="ri-whatsapp-line text-lg"></i>
                <span>Booking Sekarang</span>
              </a>
            </div>
          </div>
        </div>

      </div>

      <!-- Navigation Button Right -->
      <button
        type="button"
        onclick="slideService(1)"
        class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 items-center justify-center w-14 h-14 bg-white text-primary rounded-full shadow-xl hover:shadow-2xl hover:bg-gradient-to-br hover:from-primary hover:to-secondary hover:text-white transition-all duration-300 z-20 group"
        aria-label="Scroll Right"
        id="serviceScrollRight"
      >
        <i class="ri-arrow-right-s-line text-3xl group-hover:scale-110 transition-transform"></i>
      </button>

    </div>

    <!-- Bottom CTA -->
    <div class="mt-16 text-center">
      <p class="text-gray-600 mb-6">
        Tidak yakin program mana yang cocok? <span class="font-semibold text-primary">Konsultasi gratis</span> dengan tim kami
      </p>
      <!-- FIX: hover:scale-105 dihapus dari CTA ini juga -->
      <a href="https://wa.me/6287785767395" 
         target="_blank"
         class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary via-secondary to-primary bg-[length:200%_auto] text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-shadow duration-300 group">
        <i class="ri-customer-service-2-line text-xl"></i>
        <span>Hubungi Kami</span>
        <i class="ri-arrow-right-line text-xl group-hover:translate-x-1 transition-transform"></i>
      </a>
    </div>

  </div>
</section>

<!-- Modal Detail Service -->
<div 
  id="service-detail-modal"
  class="fixed inset-0 hidden z-50 bg-black bg-opacity-60 items-center justify-center transition-opacity duration-200"
>
  <div 
    id="service-detail-box"
    class="bg-white rounded-lg shadow-xl max-w-md w-full p-8 relative transform transition-all duration-200 scale-95 opacity-0"
  >
    <button 
      onclick="closeServiceDetail()" 
      class="absolute top-2 right-2 text-gray-500 hover:text-primary text-2xl"
      aria-label="Close Modal"
    >
      &times;
    </button>

    <h3 id="service-detail-title" class="text-xl font-bold text-primary mb-4"></h3>
    <div id="service-detail-content" class="text-gray-700 text-sm leading-relaxed"></div>
  </div>
</div>


<!-- ========================================= -->
<!-- PACKAGES & PRICING SECTION - STABLE FIXED -->
<!-- Cards tidak bergoyang sama sekali          -->
<!-- ========================================= -->

<section id="Packages" class="relative py-20 md:py-28 lg:py-32 overflow-hidden">
    <!-- Multi-Layer Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-50 via-pink-50/40 to-white"></div>

    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-[0.015] pointer-events-none">
        <div class="absolute inset-0"
             style="background-image:
                repeating-linear-gradient(0deg, transparent, transparent 50px, #c68e8f 50px, #c68e8f 51px),
                repeating-linear-gradient(90deg, transparent, transparent 50px, #4a2b30 50px, #4a2b30 51px);
                background-size: 100px 100px;">
        </div>
    </div>

    <!-- Floating Gradient Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-48 left-0 w-96 h-96 bg-gradient-to-br from-secondary/15 to-pink-300/15 rounded-full blur-3xl animate-pulse" style="animation-duration: 7s;"></div>
        <div class="absolute top-1/3 -right-32 w-[500px] h-[500px] bg-gradient-to-tl from-primary/10 to-purple-300/10 rounded-full blur-3xl animate-pulse" style="animation-duration: 9s; animation-delay: 2s;"></div>
        <div class="absolute -bottom-32 left-1/4 w-80 h-80 bg-gradient-to-tr from-pink-200/15 to-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-duration: 8s; animation-delay: 1s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Section Header -->
        <div class="text-center mb-16 md:mb-24">
            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-lg backdrop-blur-sm">
                <div class="relative">
                    <div class="w-2 h-2 bg-primary rounded-full animate-ping absolute"></div>
                    <div class="w-2 h-2 bg-primary rounded-full relative"></div>
                </div>
                <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
                    The Best Investment for Your Health
                </span>
            </div>

            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                <span class="text-primary">Packages &</span>
                <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-secondary via-pink-400 to-secondary animate-gradient-shift bg-[length:200%_auto]">
                    Pricing
                </span>
            </h2>

            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                <div class="w-24 h-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-secondary rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                <div class="w-16 h-0.5 bg-gradient-to-l from-transparent to-secondary rounded-full"></div>
            </div>

            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Pilih rencana yang sempurna yang sesuai dengan perjalanan kebugaran dan gaya hidup Anda
            </p>
        </div>

        <!-- Slider Container -->
        <div class="relative max-w-7xl mx-auto">

            <!-- Nav Left -->
            <button type="button" onclick="slideMembership(-1)"
                class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 items-center justify-center w-14 h-14 bg-white text-primary rounded-full shadow-xl hover:shadow-2xl hover:bg-gradient-to-br hover:from-primary hover:to-secondary hover:text-white transition-all duration-300 z-20 group"
                aria-label="Scroll Left" id="membershipScrollLeft" style="display:none">
                <i class="ri-arrow-left-s-line text-3xl group-hover:scale-110 transition-transform"></i>
            </button>

            <!-- Slider Track -->
            <div id="membershipList"
                 class="flex items-stretch overflow-x-auto gap-6 md:gap-8 scroll-smooth pb-6 px-2"
                 style="scrollbar-width:none; -ms-overflow-style:none;"
                 onscroll="toggleMembershipScroll()">

                <!-- Card 1 : Exclusive Class Program -->
                <div class="min-w-[90vw] sm:min-w-[340px] max-w-[360px] flex-shrink-0 flex">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-xl
                                hover:shadow-2xl transition-shadow duration-300
                                border-2 border-secondary/30 overflow-hidden w-full flex flex-col">

                        <div class="absolute top-6 right-6 z-20">
                            <div class="relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary blur-md opacity-75 rounded-full"></div>
                                <div class="relative bg-gradient-to-r from-primary to-secondary text-white text-xs font-black px-4 py-2 rounded-full shadow-xl">
                                    BEST VALUE
                                </div>
                            </div>
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-br from-pink-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col items-center flex-1">
                            <div class="relative mb-6">
                                <div class="w-24 h-24 flex items-center justify-center rounded-3xl
                                            bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600
                                            shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                                     style="will-change:transform;">
                                    <i class="ri-star-smile-line text-5xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-3xl border-2 border-secondary/20 group-hover:border-secondary/40 transition-colors duration-300"></div>
                            </div>

                            <h3 class="text-2xl font-black text-primary mb-3 text-center
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Exclusive Class Program
                            </h3>

                            <div class="mb-6 text-center">
                                <div class="text-4xl font-black text-primary">IDR 850K</div>
                                <div class="text-sm text-gray-500 font-medium">per Month</div>
                            </div>

                            <ul class="w-full space-y-3 mb-6 flex-1">
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Muaythai</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Mat Pilates</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Body Shaping</span>
                                </li>
                            </ul>

                            <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-500 mb-4"></div>

                            <div class="w-full space-y-2 mt-auto">
                                <a href="{{ route('join.package', ['package' => 'exclusive-class-program']) }}"
                                   class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 rounded-full hover:shadow-xl hover:brightness-110 transition-all font-bold flex items-center justify-center gap-2">
                                    <i class="ri-check-line text-xl"></i>
                                    <span>Beli Sekarang</span>
                                </a>
                                <button type="button" onclick="showServiceDetail('exclusive-program')"
                                        class="w-full text-secondary text-sm font-semibold hover:text-primary transition-colors py-1">
                                    Lihat Selengkapnya ->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2 : Reformer Pilates Single Visit -->
                <div class="min-w-[90vw] sm:min-w-[340px] max-w-[360px] flex-shrink-0 flex">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg
                                hover:shadow-2xl transition-shadow duration-300
                                border-2 border-gray-100 hover:border-primary/30 overflow-hidden w-full flex flex-col">

                        <div class="absolute inset-0 bg-gradient-to-br from-purple-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col items-center flex-1">
                            <div class="relative mb-6">
                                <div class="w-24 h-24 flex items-center justify-center rounded-3xl
                                            bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600
                                            shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                                     style="will-change:transform;">
                                    <i class="ri-group-line text-5xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-3xl border-2 border-primary/20 group-hover:border-primary/40 transition-colors duration-300"></div>
                            </div>

                            <h3 class="text-2xl font-black text-primary mb-3 text-center
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Reformer Pilates
                            </h3>
                            <div class="mb-6">
                                <div class="text-lg font-bold text-primary text-center">Single Visit Group Class</div>
                            </div>

                            <ul class="w-full space-y-3 mb-6 flex-1">
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>IDR 400K / Single</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>IDR 700K / Double</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>IDR 900K / Triple</span>
                                </li>
                            </ul>

                            <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-500 mb-4"></div>

                            <div class="w-full space-y-2 mt-auto">
                                <?php $reformer_variants = [
                                    ['label' => 'Single - IDR 400K', 'url' => route('join.package', ['package' => 2])],
                                    ['label' => 'Double - IDR 700K', 'url' => route('join.package', ['package' => 3])],
                                    ['label' => 'Triple - IDR 900K', 'url' => route('join.package', ['package' => 4])],
                                ]; ?>
                                <button type="button"
                                        class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 rounded-full hover:shadow-xl hover:brightness-110 transition-all font-bold flex items-center justify-center gap-2 join-btn"
                                        data-variants='{!! json_encode($reformer_variants) !!}'>
                                    <i class="ri-check-line text-xl"></i>
                                    <span>Beli Sekarang</span>
                                </button>
                                <button type="button" onclick="showServiceDetail('reformer-pilates')"
                                        class="w-full text-secondary text-sm font-semibold hover:text-primary transition-colors py-1">
                                    Lihat Selengkapnya ->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3 : Single Visit Class -->
                <div class="min-w-[90vw] sm:min-w-[340px] max-w-[360px] flex-shrink-0 flex">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg
                                hover:shadow-2xl transition-shadow duration-300
                                border-2 border-gray-100 hover:border-secondary/30 overflow-hidden w-full flex flex-col">

                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col items-center flex-1">
                            <div class="relative mb-6">
                                <div class="w-24 h-24 flex items-center justify-center rounded-3xl
                                            bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600
                                            shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                                     style="will-change:transform;">
                                    <i class="ri-door-open-line text-5xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-3xl border-2 border-secondary/20 group-hover:border-secondary/40 transition-colors duration-300"></div>
                            </div>

                            <h3 class="text-2xl font-black text-primary mb-6 text-center
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Single Visit Class
                            </h3>

                            <ul class="w-full space-y-3 mb-6 flex-1">
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Single Class: IDR 150K</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Bundle 2 Class: IDR 275K</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Bundle 4 Class: IDR 525K</span>
                                </li>
                            </ul>

                            <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-500 mb-4"></div>

                            <div class="w-full space-y-2 mt-auto">
                                <?php $single_visit_variants = [
                                    ['label' => 'Single Class - IDR 150K', 'url' => route('join.package', ['package' => 5])],
                                    ['label' => 'Bundle 2 - IDR 275K', 'url' => route('join.package', ['package' => 6])],
                                    ['label' => 'Bundle 4 - IDR 525K', 'url' => route('join.package', ['package' => 7])],
                                ]; ?>
                                <button type="button"
                                        class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 rounded-full hover:shadow-xl hover:brightness-110 transition-all font-bold flex items-center justify-center gap-2 join-btn"
                                        data-variants='{!! json_encode($single_visit_variants) !!}'>
                                    <i class="ri-check-line text-xl"></i>
                                    <span>Beli Sekarang</span>
                                </button>
                                <button type="button" onclick="showServiceDetail('single-visit')"
                                        class="w-full text-secondary text-sm font-semibold hover:text-primary transition-colors py-1">
                                    Lihat Selengkapnya ->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 : Reformer Pilates Packages -->
                <div class="min-w-[90vw] sm:min-w-[340px] max-w-[360px] flex-shrink-0 flex">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg
                                hover:shadow-2xl transition-shadow duration-300
                                border-2 border-gray-100 hover:border-primary/30 overflow-hidden w-full flex flex-col">

                        <div class="absolute inset-0 bg-gradient-to-br from-green-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col items-center flex-1">
                            <div class="relative mb-6">
                                <div class="w-24 h-24 flex items-center justify-center rounded-3xl
                                            bg-gradient-to-br from-green-100 to-green-200 text-green-600
                                            shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                                     style="will-change:transform;">
                                    <i class="ri-calendar-check-line text-5xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-3xl border-2 border-primary/20 group-hover:border-primary/40 transition-colors duration-300"></div>
                            </div>

                            <h3 class="text-2xl font-black text-primary mb-3 text-center
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Reformer Pilates
                            </h3>
                            <div class="mb-6">
                                <div class="text-lg font-bold text-primary text-center">Packages</div>
                            </div>

                            <ul class="w-full space-y-2 mb-6 flex-1 text-xs">
                                <li class="flex items-start gap-2 text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-base text-secondary flex-shrink-0 mt-0.5"></i>
                                    <span>IDR 400K / Single Visit</span>
                                </li>
                                <li class="flex items-start gap-2 text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-base text-secondary flex-shrink-0 mt-0.5"></i>
                                    <span>IDR 1.400K / 4 Sessions 15 Days</span>
                                </li>
                                <li class="flex items-start gap-2 text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-base text-secondary flex-shrink-0 mt-0.5"></i>
                                    <span>IDR 1.540K / 4 Sessions 30 Days</span>
                                </li>
                                <li class="flex items-start gap-2 text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-base text-secondary flex-shrink-0 mt-0.5"></i>
                                    <span>IDR 2.200K / 8 Sessions 30 Days</span>
                                </li>
                                <li class="flex items-start gap-2 text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-base text-secondary flex-shrink-0 mt-0.5"></i>
                                    <span>IDR 2.640K / 8 Sessions 60 Days</span>
                                </li>
                            </ul>

                            <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-500 mb-4"></div>

                            <div class="w-full space-y-2 mt-auto">
                                <?php $reformer_packages_variants = [
                                    ['label' => '1 Visit - IDR 400K', 'url' => route('join.package', ['package' => 8])],
                                    ['label' => '4 Sessions / 15 Days - IDR 1.400K', 'url' => route('join.package', ['package' => 9])],
                                    ['label' => '4 Sessions / 30 Days - IDR 1.540K', 'url' => route('join.package', ['package' => 10])],
                                    ['label' => '8 Sessions / 30 Days - IDR 2.200K', 'url' => route('join.package', ['package' => 11])],
                                    ['label' => '8 Sessions / 60 Days - IDR 2.640K', 'url' => route('join.package', ['package' => 12])],
                                ]; ?>
                                <button type="button"
                                        class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 rounded-full hover:shadow-xl hover:brightness-110 transition-all font-bold flex items-center justify-center gap-2 join-btn"
                                        data-variants='{!! json_encode($reformer_packages_variants) !!}'>
                                    <i class="ri-check-line text-xl"></i>
                                    <span>Beli Sekarang</span>
                                </button>
                                <button type="button" onclick="showServiceDetail('reformer-pilates')"
                                        class="w-full text-secondary text-sm font-semibold hover:text-primary transition-colors py-1">
                                    Lihat Selengkapnya ->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 5 : Private Program -->
                <div class="min-w-[90vw] sm:min-w-[340px] max-w-[360px] flex-shrink-0 flex">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg
                                hover:shadow-2xl transition-shadow duration-300
                                border-2 border-gray-100 hover:border-secondary/30 overflow-hidden w-full flex flex-col">

                        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col items-center flex-1">
                            <div class="relative mb-6">
                                <div class="w-24 h-24 flex items-center justify-center rounded-3xl
                                            bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600
                                            shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                                     style="will-change:transform;">
                                    <i class="ri-user-line text-5xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-3xl border-2 border-secondary/20 group-hover:border-secondary/40 transition-colors duration-300"></div>
                            </div>

                            <h3 class="text-2xl font-black text-primary mb-6 text-center
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Private Program
                            </h3>

                            <ul class="w-full space-y-3 mb-6 flex-1">
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Muaythai</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Mat Pilates</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Body Shaping</span>
                                </li>
                            </ul>

                            <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-500 mb-4"></div>

                            <div class="w-full space-y-2 mt-auto">
                                <a href="{{ route('join.package', ['package' => 'private-program']) }}"
                                   class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 rounded-full hover:shadow-xl hover:brightness-110 transition-all font-bold flex items-center justify-center gap-2">
                                    <i class="ri-whatsapp-line text-xl"></i>
                                    <span>Hubungi Tim Kami</span>
                                </a>
                                <button type="button" onclick="showServiceDetail('private-training')"
                                        class="w-full text-secondary text-sm font-semibold hover:text-primary transition-colors py-1">
                                    Lihat Selengkapnya ->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 6 : Private Group Program -->
                <div class="min-w-[90vw] sm:min-w-[340px] max-w-[360px] flex-shrink-0 flex">
                    <div class="group relative bg-white rounded-3xl p-8 shadow-lg
                                hover:shadow-2xl transition-shadow duration-300
                                border-2 border-gray-100 hover:border-primary/30 overflow-hidden w-full flex flex-col">

                        <div class="absolute inset-0 bg-gradient-to-br from-rose-50/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col items-center flex-1">
                            <div class="relative mb-6">
                                <div class="w-24 h-24 flex items-center justify-center rounded-3xl
                                            bg-gradient-to-br from-rose-100 to-rose-200 text-rose-600
                                            shadow-lg transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                                     style="will-change:transform;">
                                    <i class="ri-team-line text-5xl"></i>
                                </div>
                                <div class="absolute -inset-2 rounded-3xl border-2 border-primary/20 group-hover:border-primary/40 transition-colors duration-300"></div>
                            </div>

                            <h3 class="text-2xl font-black text-primary mb-6 text-center
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Private Group Program
                            </h3>

                            <ul class="w-full space-y-3 mb-6 flex-1">
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Muaythai</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Mat Pilates</span>
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-700">
                                    <i class="ri-checkbox-circle-fill text-xl text-secondary flex-shrink-0"></i>
                                    <span>Body Shaping</span>
                                </li>
                            </ul>

                            <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-500 mb-4"></div>

                            <div class="w-full space-y-2 mt-auto">
                                <a href="{{ route('join.package', ['package' => 'private-group-program']) }}"
                                   class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-4 rounded-full hover:shadow-xl hover:brightness-110 transition-all font-bold flex items-center justify-center gap-2">
                                    <i class="ri-whatsapp-line text-xl"></i>
                                    <span>Hubungi Tim Kami</span>
                                </a>
                                <button type="button" onclick="showServiceDetail('private-group')"
                                        class="w-full text-secondary text-sm font-semibold hover:text-primary transition-colors py-1">
                                    Lihat Selengkapnya ->
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Nav Right -->
            <button type="button" onclick="slideMembership(1)"
                class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 items-center justify-center w-14 h-14 bg-white text-primary rounded-full shadow-xl hover:shadow-2xl hover:bg-gradient-to-br hover:from-primary hover:to-secondary hover:text-white transition-all duration-300 z-20 group"
                aria-label="Scroll Right" id="membershipScrollRight">
                <i class="ri-arrow-right-s-line text-3xl group-hover:scale-110 transition-transform"></i>
            </button>
        </div>

        <!-- Bottom Notes -->
        <div class="mt-16 text-center">
            <p class="text-gray-600 text-sm max-w-2xl mx-auto">
                All packages include Schedule will continue to be updated
            </p>
        </div>
    </div>
</section>

<!-- Package Variant Modal -->
<div id="package-variant-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 mx-4">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold text-primary">Pilih Paket</h3>
      <button type="button" onclick="closeVariantModal()" class="text-gray-600 text-2xl">&times;</button>
    </div>
    <div id="package-variant-list" class="flex flex-col gap-3"></div>
    <div class="mt-4 text-right">
      <button type="button" onclick="closeVariantModal()" class="px-4 py-2 rounded-button border border-gray-300">Batal</button>
    </div>
  </div>
</div>

<!-- ========================================= -->
<!-- CLASSES SECTION - ULTIMATE PROFESSIONAL  -->
<!-- Consistent with FTM Society design style -->
<!-- Cards TIDAK bergoyang                     -->
<!-- ========================================= -->

<section id="classes" class="relative py-20 md:py-28 lg:py-32 overflow-hidden">

    <!-- Multi-Layer Background -->
    <div class="absolute inset-0 bg-gradient-to-b from-white via-pink-50/30 to-gray-50"></div>

    <!-- Subtle Grid Pattern -->
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
         style="background-image:
            repeating-linear-gradient(0deg, transparent, transparent 50px, #c68e8f 50px, #c68e8f 51px),
            repeating-linear-gradient(90deg, transparent, transparent 50px, #4a2b30 50px, #4a2b30 51px);
            background-size: 100px 100px;">
    </div>

    <!-- Floating Gradient Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-gradient-to-br from-secondary/15 to-pink-300/15 rounded-full blur-3xl animate-pulse" style="animation-duration:7s;"></div>
        <div class="absolute top-1/2 -right-40 w-[500px] h-[500px] bg-gradient-to-tl from-primary/10 to-purple-300/10 rounded-full blur-3xl animate-pulse" style="animation-duration:9s; animation-delay:2s;"></div>
        <div class="absolute -bottom-20 left-1/3 w-80 h-80 bg-gradient-to-tr from-pink-200/15 to-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-duration:8s; animation-delay:1s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- ── Section Header ── -->
        <div class="text-center mb-16 md:mb-20">

            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-lg backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                </span>
                <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
                    Our Class Program
                </span>
            </div>

            <!-- Title -->
            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                
                <span class="block mt-1 text-transparent bg-clip-text bg-gradient-to-r from-secondary via-pink-400 to-secondary animate-gradient-shift bg-[length:200%_auto]">
                    Classes
                </span>
            </h2>

            <!-- Decorative Divider -->
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                <div class="w-24 h-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-secondary rounded-full animate-pulse" style="animation-delay:0.5s;"></div>
                <div class="w-16 h-0.5 bg-gradient-to-l from-transparent to-secondary rounded-full"></div>
            </div>

            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Temukan berbagai program kebugaran yang dirancang khusus untuk kebutuhan Anda.
            </p>
        </div>

        <!-- ── 4-Column Grid ── -->
        <!-- KEY: grid layout, tidak pakai slider → tidak perlu hover:scale pada card -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

            <!-- ══════════════════════════ -->
            <!-- CARD 1 : Muaythai         -->
            <!-- ══════════════════════════ -->
            <div class="group relative bg-white rounded-3xl overflow-hidden
                        shadow-lg hover:shadow-2xl transition-shadow duration-300
                        border-2 border-gray-100 hover:border-secondary/30 flex flex-col">

                <!-- Hover tint -->
                <div class="absolute inset-0 bg-gradient-to-br from-pink-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-0"></div>

                <!-- Image -->
                <div class="relative h-52 overflow-hidden flex-shrink-0">
                    <img src="{{ asset('images/muaythai.png') }}" alt="Muaythai" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <!-- Gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    <!-- Badge -->
                    <div class="absolute top-3 left-3 bg-gradient-to-r from-secondary to-pink-400 text-white text-xs font-black px-3 py-1 rounded-full shadow-lg">
                        Popular
                    </div>
                    <!-- Duration chip -->
                    <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-primary text-xs font-bold px-2.5 py-1 rounded-full shadow">
                        45 min
                    </div>
                </div>

                <!-- Content -->
                <div class="relative z-10 p-6 flex-1 flex flex-col">

                    <!-- Icon + Title Row -->
                    <div class="flex items-center gap-3 mb-3">
                        <!-- Icon: HANYA icon yang scale pada hover -->
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl
                                    bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600
                                    shadow-sm transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                             style="will-change:transform; flex-shrink:0;">
                            <i class="ri-boxing-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-primary leading-tight
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Muaythai
                            </h3>
                            <span class="text-xs text-gray-400 font-medium">All Levels</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-5">
                        Seni bela diri asal Thailand menggunakan delapan titik kontak tubuh: tangan, siku, lutut, dan kaki — melibatkan teknik serangan dan pertahanan.
                    </p>

                    <!-- Bottom accent line -->
                    <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-500 mb-4"></div>

                    <!-- CTA — hapus hover:scale-105 -->
                    <button onclick="openModal('muaythai')"
                            class="w-full bg-gradient-to-r from-primary to-secondary text-white px-4 py-3 rounded-full
                                   hover:shadow-xl hover:brightness-110 transition-all text-sm font-bold
                                   flex items-center justify-center gap-2 mt-auto">
                        <i class="ri-eye-line text-base"></i>
                        Cek Sekarang
                    </button>
                </div>
            </div>

            <!-- ══════════════════════════ -->
            <!-- CARD 2 : Body Shaping     -->
            <!-- ══════════════════════════ -->
            <div class="group relative bg-white rounded-3xl overflow-hidden
                        shadow-lg hover:shadow-2xl transition-shadow duration-300
                        border-2 border-gray-100 hover:border-primary/30 flex flex-col">

                <div class="absolute inset-0 bg-gradient-to-br from-purple-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-0"></div>

                <div class="relative h-52 overflow-hidden flex-shrink-0">
                    <img src="{{ asset('images/body shaping.png') }}" alt="Body Shaping" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-primary text-xs font-bold px-2.5 py-1 rounded-full shadow">
                        30 min
                    </div>
                </div>

                <div class="relative z-10 p-6 flex-1 flex flex-col">

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl
                                    bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600
                                    shadow-sm transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                             style="will-change:transform; flex-shrink:0;">
                            <i class="ri-body-scan-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-primary leading-tight
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Body Shaping
                            </h3>
                            <span class="text-xs text-gray-400 font-medium">All Levels</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-5">
                        Kelas strength training full body workout untuk toning dan shaping tubuh — dari calisthenics hingga gerakan dengan beban dan equipment pendukung.
                    </p>

                    <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-500 mb-4"></div>

                    <button onclick="openModal('body-shaping')"
                            class="w-full bg-gradient-to-r from-primary to-secondary text-white px-4 py-3 rounded-full
                                   hover:shadow-xl hover:brightness-110 transition-all text-sm font-bold
                                   flex items-center justify-center gap-2 mt-auto">
                        <i class="ri-eye-line text-base"></i>
                        Cek Sekarang
                    </button>
                </div>
            </div>

            <!-- ══════════════════════════ -->
            <!-- CARD 3 : Mat Pilates      -->
            <!-- ══════════════════════════ -->
            <div class="group relative bg-white rounded-3xl overflow-hidden
                        shadow-lg hover:shadow-2xl transition-shadow duration-300
                        border-2 border-gray-100 hover:border-secondary/30 flex flex-col">

                <div class="absolute inset-0 bg-gradient-to-br from-blue-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-0"></div>

                <div class="relative h-52 overflow-hidden flex-shrink-0">
                    <img src="{{ asset('images/mat pilates.png') }}" alt="Mat Pilates" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-primary text-xs font-bold px-2.5 py-1 rounded-full shadow">
                        60 min
                    </div>
                </div>

                <div class="relative z-10 p-6 flex-1 flex flex-col">

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl
                                    bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600
                                    shadow-sm transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                             style="will-change:transform; flex-shrink:0;">
                            <i class="ri-mental-health-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-primary leading-tight
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Mat Pilates
                            </h3>
                            <span class="text-xs text-gray-400 font-medium">All Levels</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-5">
                        Latihan di atas matras fokus pada kekuatan inti (core), stabilitas, postur, pernapasan, dan fleksibilitas — dilakukan secara perlahan dan terkontrol.
                    </p>

                    <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-secondary via-primary to-secondary rounded-full transition-all duration-500 mb-4"></div>

                    <button onclick="openModal('mat-pilates')"
                            class="w-full bg-gradient-to-r from-primary to-secondary text-white px-4 py-3 rounded-full
                                   hover:shadow-xl hover:brightness-110 transition-all text-sm font-bold
                                   flex items-center justify-center gap-2 mt-auto">
                        <i class="ri-eye-line text-base"></i>
                        Cek Sekarang
                    </button>
                </div>
            </div>

            <!-- ══════════════════════════════ -->
            <!-- CARD 4 : Reformer Pilates      -->
            <!-- ══════════════════════════════ -->
            <div class="group relative bg-white rounded-3xl overflow-hidden
                        shadow-lg hover:shadow-2xl transition-shadow duration-300
                        border-2 border-gray-100 hover:border-primary/30 flex flex-col">

                <div class="absolute inset-0 bg-gradient-to-br from-green-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-0"></div>

                <div class="relative h-52 overflow-hidden flex-shrink-0">
                    <img src="{{ asset('images/revormer pilates.png') }}" alt="Reformer Pilates" loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    <!-- Badge -->
                    <div class="absolute top-3 left-3 bg-gradient-to-r from-primary to-secondary text-white text-xs font-black px-3 py-1 rounded-full shadow-lg">
                        Popular
                    </div>
                    <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-primary text-xs font-bold px-2.5 py-1 rounded-full shadow">
                        45 min
                    </div>
                </div>

                <div class="relative z-10 p-6 flex-1 flex flex-col">

                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl
                                    bg-gradient-to-br from-green-100 to-green-200 text-green-600
                                    shadow-sm transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6"
                             style="will-change:transform; flex-shrink:0;">
                            <i class="ri-focus-3-line text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-primary leading-tight
                                       group-hover:text-transparent group-hover:bg-clip-text
                                       group-hover:bg-gradient-to-r group-hover:from-primary group-hover:to-secondary
                                       transition-all duration-300">
                                Reformer Pilates
                            </h3>
                            <span class="text-xs text-gray-400 font-medium">All Levels</span>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 leading-relaxed flex-1 mb-5">
                        Menggunakan alat reformer dengan pegas dan tali untuk resistensi tambahan — variasi Mat Pilates yang dibantu alat untuk hasil lebih optimal.
                    </p>

                    <div class="h-0.5 w-0 group-hover:w-full bg-gradient-to-r from-primary via-secondary to-primary rounded-full transition-all duration-500 mb-4"></div>

                    <button onclick="openModal('reformer-pilates')"
                            class="w-full bg-gradient-to-r from-primary to-secondary text-white px-4 py-3 rounded-full
                                   hover:shadow-xl hover:brightness-110 transition-all text-sm font-bold
                                   flex items-center justify-center gap-2 mt-auto">
                        <i class="ri-eye-line text-base"></i>
                        Cek Sekarang
                    </button>
                </div>
            </div>

        </div><!-- end grid -->

    </div>
</section>

<!-- ══════════════════════════════════════ -->
<!-- MODAL — Enhanced Premium Design       -->
<!-- ══════════════════════════════════════ -->
<div id="class-modal"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden justify-center items-center z-50 px-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative overflow-hidden">

        <!-- Modal gradient top bar -->
        <div class="h-1.5 w-full bg-gradient-to-r from-primary via-secondary to-primary"></div>

        <!-- Close Button -->
        <button onclick="closeModal()"
                class="absolute top-4 right-4 w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 hover:bg-red-100 hover:text-red-600 text-gray-500 text-lg font-bold transition-colors duration-200 z-10">
            &times;
        </button>

        <!-- Modal Content -->
        <div class="p-7">
            <h3 id="modal-title" class="text-2xl font-black text-primary mb-1"></h3>
            <div id="modal-content" class="text-sm text-gray-600 mt-3"></div>

            <!-- WA Button — hapus hover:scale-105 -->
            <div class="mt-6">
                <a id="modal-wa-btn"
                   href="#"
                   target="_blank"
                   class="w-full bg-gradient-to-r from-primary to-secondary text-white px-6 py-3.5 rounded-full
                          hover:shadow-xl hover:brightness-110 transition-all font-bold
                          flex items-center justify-center gap-2 text-sm">
                    <i class="ri-whatsapp-line text-xl"></i>
                    Daftar via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

@php
    use Illuminate\Support\Str;

    // flatten dulu (ini kunci)
    $flatSchedules = $schedules->flatten();

    $classKeywords = ['muaythai', 'pilates'];
    $groupedSchedules = [];

    foreach ($classKeywords as $keyword) {
        $groupedSchedules[$keyword] = $flatSchedules->filter(function ($schedule) use ($keyword) {
            return Str::contains(
                Str::lower($schedule->classModel->class_name ?? ''),
                $keyword
            );
        });
    }
@endphp


<script>
window.classSchedules = {
    @foreach($groupedSchedules as $keyword => $entries)
        '{{ $keyword }}': [
            @foreach($entries as $entry)
                {
                    hari: '{{ $entry->day }}',
                    jam: '{{ \Carbon\Carbon::parse($entry->class_time)->format('H:i') }}',
                    instruktur: '{{ $entry->instructor ?? '-' }}',
                    kelas: '{{ optional($entry->classModel)->class_name ?? '-' }}'
                }@if(!$loop->last),@endif
            @endforeach
        ]@if(!$loop->last),@endif
    @endforeach
};
</script>


        <!-- Notes -->
        <div class="mt-12 text-center">
          <p class="text-gray-600 text-sm">
            All packages including Schedule will continue to be updated
          </p>
          <div class="mt-8">
          </div>
        </div>
      </div>
    </section>
  <!-- Modal Service Detail -->
<div 
  id="service-detail-modal"
  class="fixed inset-0 hidden z-50 bg-black bg-opacity-60 items-center justify-center transition-opacity"
>
  <div 
    id="service-detail-box"
    class="bg-white rounded-lg shadow-xl max-w-md w-full p-8 relative transform transition-all scale-95 opacity-0"
  >
    <button 
      onclick="closeServiceDetail()" 
      class="absolute top-2 right-2 text-gray-500 hover:text-primary text-2xl"
      aria-label="Close Modal"
    >
      &times;
    </button>

    <h3 id="service-detail-title" class="text-xl font-bold text-primary mb-4"></h3>

    <p id="service-detail-desc" class="text-gray-700 leading-relaxed"></p>
  </div>
</div>

    <!-- Jadwal Kelas Simpel & Modern -->
<section id="schedule" class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">JADWAL KELAS</h2>

    <!-- Tambahan: tampilkan membership -->
    <div class="mb-4 text-center text-lg font-semibold text-gray-700">
        Membership Anda: <span class="text-primary">{{ $customer->membership }}</span>
    </div>

    @if ($schedules->count())
        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">KELAS</th>
                        <th class="p-3">HARI</th>
                        <th class="p-3">JAM</th>
                        <th class="p-3">INSTRUKTUR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->schedules as $schedule)
                        <tr class="border-t">
                            <td class="p-3">{{ $schedule->class_name }}</td>
                            <td class="p-3">{{ $schedule->day }}</td>
                            <td class="p-3">{{ \Carbon\Carbon::parse($schedule->class_time)->format('H:i') }}</td>
                            <td class="p-3">{{ $schedule->instructor }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center p-3">Anda belum terdaftar di kelas manapun.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-gray-500">Belum ada jadwal tersedia.</p>
    @endif
</section>

{{-- ========================================= --}}
{{-- GALLERY SECTION - Selaras dengan Classes --}}
{{-- ========================================= --}}

<section id="Facility" class="relative py-20 md:py-28 lg:py-32 overflow-hidden">

    {{-- Multi-Layer Background --}}
    <div class="absolute inset-0 bg-gradient-to-b from-white via-pink-50/30 to-gray-50"></div>

    {{-- Subtle Grid Pattern --}}
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
         style="background-image:
            repeating-linear-gradient(0deg, transparent, transparent 50px, #c68e8f 50px, #c68e8f 51px),
            repeating-linear-gradient(90deg, transparent, transparent 50px, #4a2b30 50px, #4a2b30 51px);
            background-size: 100px 100px;">
    </div>

    {{-- Floating Gradient Orbs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-gradient-to-bl from-secondary/15 to-pink-300/15 rounded-full blur-3xl animate-pulse" style="animation-duration:7s;"></div>
        <div class="absolute top-1/2 -left-40 w-[500px] h-[500px] bg-gradient-to-tr from-primary/10 to-purple-300/10 rounded-full blur-3xl animate-pulse" style="animation-duration:9s; animation-delay:2s;"></div>
        <div class="absolute -bottom-20 right-1/3 w-80 h-80 bg-gradient-to-tl from-pink-200/15 to-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-duration:8s; animation-delay:1s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- ── Section Header ── --}}
        <div class="text-center mb-16 md:mb-20">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-lg backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                </span>
                <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
                    Our Facilities
                </span>
            </div>

            {{-- Title --}}
            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-secondary via-pink-400 to-secondary" style="background-size:200% auto; animation: gradientShift 4s ease infinite;">
                    Our Gallery
                </span>
            </h2>

            {{-- Decorative Divider --}}
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                <div class="w-24 h-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-secondary rounded-full animate-pulse" style="animation-delay:0.5s;"></div>
                <div class="w-16 h-0.5 bg-gradient-to-l from-transparent to-secondary rounded-full"></div>
            </div>

            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Jelajahi fasilitas kelas dunia kami yang dirancang untuk menginspirasi, menantang, dan mengubah Anda.
            </p>
        </div>

        {{-- ── Slider ── --}}
        <div class="relative max-w-5xl mx-auto">

            {{-- Slider Card --}}
            <div id="facility-slider"
                 class="relative overflow-hidden rounded-3xl shadow-2xl border-2 border-gray-100"
                 style="aspect-ratio:16/9; background:#1a1a1a;">

                {{-- Image Track --}}
                <div id="facility-track"
                     class="flex h-full"
                     style="width:1000%; transition: transform 0.7s cubic-bezier(0.25,0.46,0.45,0.94);">

                    @php $facilityImages = [
                        ['src'=>'bg1.png',             'label'=>'Studio Utama'],
                        ['src'=>'muaythai.png',         'label'=>'Muaythai'],
                        ['src'=>'revormer pilates.png', 'label'=>'Reformer Pilates'],
                        ['src'=>'foto5.png',            'label'=>'Fasilitas'],
                        ['src'=>'mat pilates.png',      'label'=>'Mat Pilates'],
                        ['src'=>'body shaping.png',     'label'=>'Body Shaping'],
                        ['src'=>'foto1.png',            'label'=>'Area Latihan'],
                        ['src'=>'foto2.png',            'label'=>'Area Latihan'],
                        ['src'=>'foto3.png',            'label'=>'Area Latihan'],
                        ['src'=>'foto4.png',            'label'=>'Area Latihan'],
                    ]; @endphp

                    @foreach($facilityImages as $img)
                    <div class="relative h-full flex-shrink-0" style="width:10%;">
                        <img src="{{ asset('images/' . $img['src']) }}"
                             alt="{{ $img['label'] }}" loading="lazy"
                             class="w-full h-full object-cover"/>
                        {{-- Gradient overlay --}}
                        <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(20,5,5,0.6) 0%, rgba(20,5,5,0.1) 40%, transparent 70%);"></div>
                        {{-- Image Label --}}
                        <div class="absolute bottom-5 left-6 z-10">
                            <span class="text-white font-black text-xl md:text-2xl tracking-wide drop-shadow-lg">{{ $img['label'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- ── PREV BUTTON ── --}}
                <button id="facility-prev"
                        type="button"
                        class="absolute left-4 top-1/2 z-30 flex items-center justify-center w-12 h-12 rounded-full"
                        style="transform:translateY(-50%);
                               background:linear-gradient(135deg,#4a2b30,#c68e8f);
                               box-shadow:0 4px 20px rgba(198,142,143,0.5);
                               border:none; cursor:pointer;
                               transition: transform 0.2s, filter 0.2s;"
                        onmouseover="this.style.filter='brightness(1.15)'; this.style.transform='translateY(-50%) scale(1.1)';"
                        onmouseout="this.style.filter='brightness(1)'; this.style.transform='translateY(-50%) scale(1)';">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>

                {{-- ── NEXT BUTTON ── --}}
                <button id="facility-next"
                        type="button"
                        class="absolute right-4 top-1/2 z-30 flex items-center justify-center w-12 h-12 rounded-full"
                        style="transform:translateY(-50%);
                               background:linear-gradient(135deg,#c68e8f,#4a2b30);
                               box-shadow:0 4px 20px rgba(198,142,143,0.5);
                               border:none; cursor:pointer;
                               transition: transform 0.2s, filter 0.2s;"
                        onmouseover="this.style.filter='brightness(1.15)'; this.style.transform='translateY(-50%) scale(1.1)';"
                        onmouseout="this.style.filter='brightness(1)'; this.style.transform='translateY(-50%) scale(1)';">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>

                {{-- Counter Badge --}}
                <div class="absolute top-4 left-4 z-20">
                    <span id="facility-counter"
                          class="text-xs font-black px-3 py-1 rounded-full text-white"
                          style="background:linear-gradient(to right,#c68e8f,#4a2b30);
                                 box-shadow:0 2px 12px rgba(198,142,143,0.5);
                                 letter-spacing:0.1em;">
                        01 / 10
                    </span>
                </div>

                {{-- Live Badge --}}
                <div class="absolute top-4 right-4 z-20">
                    <div class="flex items-center gap-1.5 rounded-full px-3 py-1"
                         style="background:rgba(255,255,255,0.15); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,0.25);">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:#c68e8f;"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2" style="background:#c68e8f;"></span>
                        </span>
                        <span class="text-white text-xs font-bold">Gallery</span>
                    </div>
                </div>

            </div>{{-- end slider --}}

            {{-- Dot Indicators --}}
            <div id="facility-dots" class="flex justify-center gap-2 mt-6"></div>

            {{-- Thumbnail Strip --}}
            <div class="flex gap-3 mt-4 overflow-x-auto pb-1 justify-center" style="-ms-overflow-style:none; scrollbar-width:none;">
                @foreach($facilityImages as $i => $img)
                <button type="button"
                        class="facility-thumb flex-shrink-0 rounded-2xl overflow-hidden border-2"
                        data-index="{{ $i }}"
                        style="width:68px; height:48px; opacity:0.45; border-color:transparent; cursor:pointer; transition:all 0.3s ease; padding:0; background:none;">
                    <img src="{{ asset('images/' . $img['src']) }}"
                         alt="{{ $img['label'] }}" loading="lazy"
                         class="w-full h-full object-cover pointer-events-none"/>
                </button>
                @endforeach
            </div>

        </div>{{-- end relative wrapper --}}

    </div>
</section>


{{-- ========================================= --}}
{{-- PARTNER SECTION - Selaras dengan Classes  --}}
{{-- ========================================= --}}

<section class="relative py-20 md:py-28 overflow-hidden">

    <div class="absolute inset-0 bg-gradient-to-b from-gray-50 via-pink-50/20 to-white"></div>

    <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
         style="background-image:
            repeating-linear-gradient(0deg, transparent, transparent 50px, #c68e8f 50px, #c68e8f 51px),
            repeating-linear-gradient(90deg, transparent, transparent 50px, #4a2b30 50px, #4a2b30 51px);
            background-size: 100px 100px;">
    </div>

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-gradient-to-br from-secondary/10 to-pink-200/10 rounded-full blur-3xl animate-pulse" style="animation-duration:8s;"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-gradient-to-tl from-primary/10 to-purple-200/10 rounded-full blur-3xl animate-pulse" style="animation-duration:6s; animation-delay:1.5s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header --}}
        <div class="text-center mb-14">

            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-lg backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                </span>
                <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
                    Trusted By
                </span>
            </div>

            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-secondary via-pink-400 to-secondary" style="background-size:200% auto; animation: gradientShift 4s ease infinite;">
                    Our Partners
                </span>
            </h2>

            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-16 h-0.5 bg-gradient-to-r from-transparent to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-primary rounded-full animate-pulse"></div>
                <div class="w-24 h-1 bg-gradient-to-r from-primary via-secondary to-primary rounded-full"></div>
                <div class="w-3 h-3 bg-secondary rounded-full animate-pulse" style="animation-delay:0.5s;"></div>
                <div class="w-16 h-0.5 bg-gradient-to-l from-transparent to-secondary rounded-full"></div>
            </div>

            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Didukung oleh merek-merek terkemuka di industri yang berkomitmen pada kesehatan, kesejahteraan, dan keunggulan.
            </p>
        </div>

        {{-- Scrolling Partner Strip --}}
        <div class="relative overflow-hidden py-3">

            {{-- Fade edges --}}
            <div class="absolute left-0 top-0 bottom-0 w-28 z-10 pointer-events-none"
                 style="background:linear-gradient(to right,#f9fafb,transparent);"></div>
            <div class="absolute right-0 top-0 bottom-0 w-28 z-10 pointer-events-none"
                 style="background:linear-gradient(to left,#f9fafb,transparent);"></div>

            {{-- Track --}}
            <div class="flex items-center partner-scroll-track" style="animation: partnerScroll 24s linear infinite;">

                @php 
                    $partners = [
                        'partner 1..png',
                        'partner 2..png',
                        'partner 3..png',
                        'partner 4..png',
                        'partner 5..png',
                        'partner 6..png'
                    ]; 
                @endphp

                @foreach(array_merge($partners, $partners) as $p)
                {{-- Card identik dengan card Classes --}}
                <div class="group relative flex-shrink-0 mx-4 px-7 py-5 rounded-3xl
                            bg-white border-2 border-gray-100
                            shadow-lg transition-shadow duration-300 hover:shadow-2xl hover:border-secondary/30"
                     style="cursor:default; min-height:100px; display:flex; align-items:center; justify-content:center;">
                    {{-- Hover tint sama dengan Classes --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-pink-50/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl pointer-events-none"></div>
                    {{-- Logo BERWARNA (tidak grayscale) --}}
                    <img src="{{ asset('icons/' . $p) }}"
                         alt="Partner Logo" loading="lazy"
                         class="relative z-10 h-16 w-auto object-contain transition-transform duration-300 group-hover:scale-110"
                         style="min-width:80px; max-width:130px;" />
                    {{-- Bottom accent line identik Classes --}}
                    <div class="h-0.5 w-0 group-hover:w-full rounded-full transition-all duration-500 mt-3"
                         style="background:linear-gradient(to right,#c68e8f,#4a2b30,#c68e8f);"></div>
                </div>
                @endforeach

            </div>
        </div>

    </div>
</section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-50">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">
            Get in Touch
          </h2>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Have questions or want to learn more? We're here to help you on your
            fitness journey.
          </p>
          <div class="w-24 h-1 bg-secondary mx-auto mt-4"></div>
        </div>
        <div class="flex flex-col md:flex-row gap-10">
          <div class="md:w-1/2">
            <div class="bg-white p-8 rounded-lg shadow-md h-full">
              <h3 class="text-2xl font-semibold text-primary mb-6">
                Contact Information
              </h3>
              <div class="space-y-6">
                <div class="flex items-start space-x-4">
                  <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary flex-shrink-0"
                  >
                    <i class="ri-map-pin-line ri-lg"></i>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-800">Address</h4>
                    <p class="text-gray-600">
                    📍Jakarta Selatan: <br />
                      Jl. Wijaya 8 No.2, RT.6/RW.7. <br />Melawai, Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12160
                    </p>
                    📍Jakarta Pusat: <br />
                      Jl. Cempaka Putih Tengah XIII No.56, RT.4/RW.6. <br />Cemp. Putih Tim., Kec. Cemp. Putih, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10510
                    </p>
                  </div>
                </div>
                <div class="flex items-start space-x-4">
                  <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary flex-shrink-0"
                  >
                    <i class="ri-time-line ri-lg"></i>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-800">Opening Hours</h4>
                    <p class="text-gray-600">
                      Monday - Saturday: 08:00 AM - 20:00 PM <br />Sunday: 08:00 AM -15:00 AM
                    </p>
                  </div>
                </div>
                <div class="flex items-start space-x-4">
                  <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary flex-shrink-0"
                  >
                    <i class="ri-phone-line ri-lg"></i>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-800">Phone & WhatsApp</h4>
                    <p class="text-gray-600">+62 877-8576-7395</p>
                    <a
                     href="https://wa.me/6287785767395"
                      class="inline-flex items-center text-secondary mt-2 hover:underline"
                    >
                      <i class="ri-whatsapp-line mr-2"></i> Message us on
                      WhatsApp
                    </a>
                  </div>
                </div>
                <div class="flex items-start space-x-4">
                  <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary flex-shrink-0"
                  >
                    <i class="ri-mail-line ri-lg"></i>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-800">Email</h4>
                    <p class="text-gray-600">ftmsociety@gmail.com</p>
                  </div>
                </div>
              </div>
              <div class="mt-8">
                <h4 class="font-medium text-gray-800 mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                  <a
                    href="https://www.instagram.com/ftmsociety.id"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-white 
                    hover:bg-secondary hover:scale-105 hover:shadow-lg 
                    transition-all text-sm font-semibold"
                  >
                    <i class="ri-instagram-line"></i>
                  </a>
                  <a
                    href="https://www.facebook.com/share/129JRu5DDXa/"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-white 
                    hover:bg-secondary hover:scale-105 hover:shadow-lg 
                    transition-all text-sm font-semibold"
                  >
                    <i class="ri-facebook-fill"></i>
                  </a>
                <a
                    href="https://www.tiktok.com/@ftm.society"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-white 
                    hover:bg-secondary hover:scale-105 hover:shadow-lg 
                    transition-all text-sm font-semibold"
                    target="_blank" rel="noopener"
                  >
                    <i class="ri-tiktok-line"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="md:w-1/2">
            <div class="bg-white p-8 rounded-lg shadow-md">
              <h3 class="text-2xl font-semibold text-primary mb-6">
                Send Us a Message
              </h3>
              <form class="space-y-6" method="POST" action="{{ route('feedback.store') }}">
                @csrf
                <div>
                  <label for="contact-name" class="block text-gray-700 mb-2">Your Name</label>
                  <input
                    type="text"
                    id="contact-name"
                    name="name"
                    class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary"
                    placeholder="Your name"
                    required
                  />
                </div>
                <div>
                  <label for="contact-email" class="block text-gray-700 mb-2">Email Address</label>
                  <input
                    type="email"
                    id="contact-email"
                    name="email"
                    class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary"
                    placeholder="your.email@example.com"
                    required
                  />
                </div>
                <div>
                  <label for="subject" class="block text-gray-700 mb-2">Subject</label>
                  <select
                    id="subject"
                    name="subject"
                    class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary pr-8 appearance-none bg-white"
                    required
                  >
                    <option value="" selected disabled>Select a subject</option>
                    <option value="membership">Membership Inquiry</option>
                    <option value="classes">Class Information</option>
                    <option value="trial">Free Trial</option>
                    <option value="feedback">Feedback</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div>
                  <label for="message" class="block text-gray-700 mb-2">Your Message</label>
                  <textarea
                    id="message"
                    name="message"
                    rows="5"
                    class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary"
                    placeholder="How can we help you?"
                    required
                  ></textarea>
                </div>
                <button
                  type="submit"
                class="w-full py-3 text-white bg-primary rounded-button 
                hover:bg-secondary hover:scale-105 hover:shadow-lg 
                transition-all duration-200 text-sm font-semibold text-center shadow-lg">     
                  Send Message
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
          <!-- filepath: resources/views/dashboard.blade.php -->
        <!-- filepath: resources/views/dashboard.blade.php -->
<section id="maps" class="py-12 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="rounded-lg overflow-hidden shadow-md h-80">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.692581073393!2d106.8020902!3d-6.2463343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f100300812e3%3A0xf433b3d8a738f209!2sFTM%20Society!5e0!3m2!1sen!2sid!4v1716463880000!5m2!1sen!2sid"
        width="100%"
        height="100%"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        class="w-full h-full"
      ></iframe>
    </div>
  </div>
</section>
   <!-- Footer -->
<footer class="bg-primary text-white pt-16 pb-8">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

      <!-- Column 1: Tentang Brand -->
      <div>
        <h4 class="text-lg font-semibold mb-6">Tentang FTM Society</h4>
        <p class="text-white text-opacity-80 text-sm leading-relaxed">
          FTM Society adalah pusat kebugaran eksklusif untuk muslimah yang mengedepankan kenyamanan, privasi, dan pendekatan islami. Bergabunglah dengan komunitas wanita kuat dan sehat yang saling mendukung dalam perjalanan hidup sehat dan seimbang.
        </p>
      </div>

      <!-- Column 2: Link Navigasi -->
      <div>
        <h4 class="text-lg font-semibold mb-6">Navigasi Cepat</h4>
        <ul class="space-y-3">
          <li><a href="#home" class="text-white text-opacity-80 hover:text-secondary transition-colors">Beranda</a></li>
          <li><a href="#about" class="text-white text-opacity-80 hover:text-secondary transition-colors">Tentang Kami</a></li>
          <li><a href="#classes" class="text-white text-opacity-80 hover:text-secondary transition-colors">Kelas</a></li>
          <li><a href="#Programs" class="text-white text-opacity-80 hover:text-secondary transition-colors">Program</a></li>
          <li><a href="#schedule" class="text-white text-opacity-80 hover:text-secondary transition-colors">Jadwal</a></li>
          <li><a href="#Facility" class="text-white text-opacity-80 hover:text-secondary transition-colors">Fasilitas</a></li>
          <li><a href="#contact" class="text-white text-opacity-80 hover:text-secondary transition-colors">Hubungi Kami</a></li>
        </ul>
      </div>

      <!-- Column 3: Program Unggulan -->
      <div>
        <h4 class="text-lg font-semibold mb-6">Program Unggulan</h4>
        <ul class="space-y-3">
          <li><a href="#Programs" class="text-white text-opacity-80 hover:text-secondary transition-colors">Private Group Class</a></li>
          <li><a href="#Programs" class="text-white text-opacity-80 hover:text-secondary transition-colors">Private Training</a></li>
          <li><a href="#Programs" class="text-white text-opacity-80 hover:text-secondary transition-colors">Single Visit Class</a></li>
          <li><a href="#Programs" class="text-white text-opacity-80 hover:text-secondary transition-colors">Reformer Pilates</a></li>
          <li><a href="#Programs" class="text-white text-opacity-80 hover:text-secondary transition-colors">Exclusive Class Program</a></li>
        </ul>
      </div>

      <!-- Column 4: Newsletter & Komunitas -->
      <div>
        <h4 class="text-lg font-semibold mb-6">Gabung Komunitas Kami</h4>
        <p class="text-white text-opacity-80 mb-4">
          Dapatkan update eksklusif, promo menarik, dan tips kebugaran langsung ke email Anda.
        </p>
        <form class="mb-4">
          <div class="flex">
            <input
              type="email"
              placeholder="Alamat email Anda"
              class="px-4 py-2 rounded-l text-gray-800 w-full border-none"
            />
            <button
              type="submit"
              class="bg-secondary text-white px-4 py-2 rounded-r hover:bg-opacity-90 transition-all whitespace-nowrap"
            >
              Langganan
            </button>
          </div>
        </form>
        <p class="text-white text-opacity-60 text-sm">
          Dengan berlangganan, Anda menyetujui kebijakan privasi kami.
        </p>
      </div>
    </div>

    <div class="border-t border-white border-opacity-20 mt-12 pt-8">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <p class="text-white text-opacity-60 text-sm mb-4 md:mb-0">
          &copy; 2025 FTM Society. Empowering Muslimah. Elevating Wellness.
        </p>
        <div class="flex space-x-6">
          <a href="#" class="text-white text-opacity-60 text-sm hover:text-secondary transition-colors">Kebijakan Privasi</a>
          <a href="#" class="text-white text-opacity-60 text-sm hover:text-secondary transition-colors">Syarat & Ketentuan</a>
          <a href="#" class="text-white text-opacity-60 text-sm hover:text-secondary transition-colors">Kontak Bantuan</a>
        </div>
      </div>
    </div>
  </div>
</footer>

    <!-- Blade Data Injection -->
    <script>
      // Auth status for JS logic
      window.isCustomerAuthenticated = @json(auth('customer')->check());
      window.homeRoute = '{{ route('home') }}';
    </script>

    <!-- Compiled JavaScript via Vite -->
    @vite(['resources/js/member.js'])
  </body>
</html>



