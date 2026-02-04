<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- favicon  -->
<!-- filepath: resources/views/dashboard.blade.php -->
<link rel="icon" type="image/png" href="{{ asset('icons/favicon.jpg') }}" />
    <!-- end favicon  -->
    <title>FTM SOCIETY - Muslimah-Only Gym</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: { primary: "#4a2b30", secondary: "#c68e8f" },
            borderRadius: {
              none: "0px",
              sm: "4px",
              DEFAULT: "8px",
              md: "12px",
              lg: "16px",
              xl: "20px",
              "2xl": "24px",
              "3xl": "32px",
              full: "9999px",
              button: "8px",
            },
          },
        },
      };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"
    />
    <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />
    <style>
            /* ...existing CSS... */
      @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px);}
        to { opacity: 1; transform: translateY(0);}
      }
      .scroll-animate {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1);
      }
      .scroll-animate.visible {
        opacity: 1;
        transform: translateY(0);
        animation: fadeInUp 0.7s cubic-bezier(.4,0,.2,1);
      }
      
      body {
      font-family: 'Poppins', sans-serif;
      scroll-behavior: smooth;
      }
      h1, h2, h3, h4 {
      font-family: 'Poppins', serif;
      }
      .logo {
      font-family: 'Poppins', serif;
      }
      .hero-section {
      background-image: linear-gradient(rgba(74, 43, 48, 0.7), rgba(74, 43, 48, 0.7)), url('./images/IMG_0278.jpg');
      background-size: cover;
      background-position: center;
      }
      .testimonial-section {
      background-image: linear-gradient(rgba(74, 43, 48, 0.05), rgba(74, 43, 48, 0.05)), url('https://readdy.ai/api/search-image?query=subtle%2520elegant%2520pattern%2520background%252C%2520very%2520light%2520and%2520minimal%252C%2520soft%2520pink%2520and%2520beige%2520tones%252C%2520delicate%2520Islamic%2520geometric%2520patterns%252C%2520barely%2520visible%252C%2520extremely%2520subtle%2520texture%252C%2520professional%2520photography&width=1920&height=600&seq=2&orientation=landscape');
      background-size: cover;
      background-position: center;
      }
      .nav-link {
      position: relative;
      }
      .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: -4px;
      left: 0;
      background-color: #c68e8f;
      transition: width 0.3s;
      }
      .nav-link:hover::after {
      width: 100%;
      }
      .class-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(74, 43, 48, 0.1);
      }
      .feature-card:hover {
      transform: translateY(-5px);
      }
      input:focus, textarea:focus {
      outline: none;
      border-color: #c68e8f;
      }
      .custom-checkbox {
      appearance: none;
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid #4a2b30;
      border-radius: 4px;
      outline: none;
      cursor: pointer;
      position: relative;
      }
      .custom-checkbox:checked {
      background-color: #4a2b30;
      }
      .custom-checkbox:checked::after {
      content: '✓';
      position: absolute;
      color: white;
      font-size: 14px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      }
      .faq-item.active .faq-answer {
      max-height: 1000px;
      opacity: 1;
      padding: 1rem 0;
      }
      .faq-item.active .faq-icon {
      transform: rotate(180deg);
      }
      .faq-answer {
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      transition: all 0.3s ease;
      }
      .mobile-menu {
      transform: translateX(100%);
      transition: transform 0.3s ease-in-out;
      }
      .mobile-menu.active {
      transform: translateX(0);
      }
      .Facility-image:hover .overlay {
      opacity: 1;
      }
  @keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95);}
  to { opacity: 1; transform: scale(1);}
}
.animate-fadeIn {
  animation: fadeIn 0.3s ease;
}
    </style>
  </head>
  <body class="bg-white text-gray-800">
    
  <!-- Header -->
<header class="fixed w-full bg-white bg-opacity-95 shadow-sm z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">

        <!-- LOGO -->
        <a href="#" class="logo text-primary text-2xl">FTM SOCIETY</a>

        <!-- DESKTOP NAVIGATION -->
        <nav class="hidden md:flex items-center space-x-8">
            <a href="#home" class="text-gray-700 hover:text-primary transition">Home</a>
            <a href="#about" class="text-gray-700 hover:text-primary transition">About</a>
            <a href="#Programs" class="text-gray-700 hover:text-primary transition">Programs</a>
            <a href="#classes" class="text-gray-700 hover:text-primary transition">Classes</a>
            <a href="#schedule" class="text-gray-700 hover:text-primary transition">Schedule</a>
            <a href="#Facility" class="text-gray-700 hover:text-primary transition">Gallery</a>
            <a href="#contact" class="text-gray-700 hover:text-primary transition">Contact</a>

            <a href="#join"
                class="bg-primary text-white px-6 py-2 rounded-button hover:bg-secondary hover:scale-105 transition font-semibold">
                Join Now
            </a>

            <a href="{{ route('member.login') }}"
                class="bg-primary text-white px-6 py-2 rounded-button hover:bg-secondary hover:scale-105 transition font-semibold">
                Login
            </a>
        </nav>

        <!-- TOMBOL MOBILE (⋮) -->
        <button id="mobile-menu-button"
            class="block md:hidden text-black text-4xl font-bold leading-none">
            ⋮
        </button>

    </div>
</header>

<!-- MOBILE OVERLAY -->
<div id="mobile-overlay" class="fixed inset-0 bg-black/50 hidden z-[9999]"></div>

<!-- MOBILE MENU SLIDE -->
<div id="mobile-menu"
    class="fixed top-0 right-[-100%] h-full w-64 bg-white shadow-lg z-[10000] p-6 transition-all duration-300">

    <div class="flex justify-end mb-8">
        <button id="close-menu-button" class="w-10 h-10 flex items-center justify-center text-primary">
            <i class="ri-close-line ri-lg"></i>
        </button>
    </div>

    <nav class="flex flex-col space-y-6">
        <a href="#home" class="text-gray-700 hover:text-primary transition">Home</a>
        <a href="#about" class="text-gray-700 hover:text-primary transition">About</a>
        <a href="#Programs" class="text-gray-700 hover:text-primary transition">Programs</a>
        <a href="#classes" class="text-gray-700 hover:text-primary transition">Classes</a>
        <a href="#schedule" class="text-gray-700 hover:text-primary transition">Schedule</a>
        <a href="#Facility" class="text-gray-700 hover:text-primary transition">Gallery</a>
        <a href="#contact" class="text-gray-700 hover:text-primary transition">Contact</a>

        <a href="#join"
            class="bg-primary text-white px-6 py-3 rounded-button text-center hover:bg-secondary hover:scale-105 transition font-semibold">
            Join Now
        </a>

        <!-- LOGIN BUTTON (DITAMBAHKAN) -->
        <a href="{{ route('member.login') }}"
            class="border border-primary text-primary px-6 py-3 rounded-button text-center hover:bg-primary hover:text-white hover:scale-105 transition font-semibold">
            Login
        </a>
    </nav>
</div>


<!-- SCRIPT NAVBAR MOBILE -->
<script>
  (function(){
    const menuBtn = document.getElementById("mobile-menu-button");
    const closeBtn = document.getElementById("close-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");
    const overlay = document.getElementById("mobile-overlay");

    if (!menuBtn || !mobileMenu) return;

    function showBackdrop(){
      if (!overlay) return;
      overlay.classList.remove('hidden');
      overlay.style.pointerEvents = 'auto';
    }
    function hideBackdrop(){
      if (!overlay) return;
      overlay.classList.add('hidden');
      overlay.style.pointerEvents = 'none';
    }

    function openMenu(){
      // ensure nothing auto-opens the menu — only run on explicit click
      console.log('[mobile] openMenu');
      mobileMenu.style.right = '0';
      menuBtn.setAttribute('aria-expanded','true');
      showBackdrop();
      document.body.classList.add('overflow-hidden');
    }

    function closeMenu(){
      console.log('[mobile] closeMenu');
      mobileMenu.style.right = '-100%';
      menuBtn.setAttribute('aria-expanded','false');
      hideBackdrop();
      document.body.classList.remove('overflow-hidden');
    }

    // toggle on hamburger click (no automatic open anywhere else)
    menuBtn.addEventListener('click', function(e){ e.preventDefault(); const isOpen = mobileMenu.style.right === '0' || mobileMenu.style.right === '' && mobileMenu.classList.contains('open'); if (isOpen) closeMenu(); else openMenu(); });

    // close button — stop propagation so it can't re-open
    if (closeBtn) closeBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); try { e.stopImmediatePropagation(); } catch(_){} closeMenu(); return false; });

    // clicking any link inside menu should close the menu so the user can see the page change
    mobileMenu.querySelectorAll('a, button[type="submit"]').forEach(function(el){ el.addEventListener('click', function(){ setTimeout(closeMenu, 60); }); });

    // backdrop click closes
    if (overlay) overlay.addEventListener('click', function(){ closeMenu(); });

    // Escape to close
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeMenu(); });

    // expose helper
    window.closeMobileMenu = closeMenu;
  })();
</script>



<!-- HERO SECTION -->
<section
  id="home"
  class="relative min-h-screen flex items-center justify-start pt-20 overflow-hidden">

  <!-- Background -->
  <div
    id="hero-bg"
    class="absolute inset-0 bg-cover bg-center transition-all duration-700 z-0"
    style="background-image: url('/images/bg1.jpg');">
  </div>

  <!-- Overlay -->
  <div
    class="absolute inset-0"
    style="background: linear-gradient(
      to right,
      rgba(120, 45, 60, 0.65),
      rgba(255, 192, 203, 0.25)
    );">
  </div>

  <!-- Navigation Buttons -->
  <button id="prev-bg"
    class="hidden md:block absolute left-4 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-20">
    <i class="ri-arrow-left-s-line text-2xl"></i>
  </button>

  <button id="next-bg"
    class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-20">
    <i class="ri-arrow-right-s-line text-2xl"></i>
  </button>

  <!-- HERO CONTENT -->
  <div class="container relative z-10 mx-auto px-4 py-20">
    <div class="max-w-2xl text-left">
      <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
        YOUR <br />
        PRODUCTIVE SISTER
      </h1>
      <p class="text-lg md:text-xl text-white mb-8">
        Good Habit inside Productive Muslimah
      </p>

      <div class="flex space-x-4">
        <a href="#join"
          class="bg-[#3C1111] text-white px-6 py-3 rounded-md font-semibold hover:bg-[#5b2020] transition">
          Join Now
        </a>

        <a href="#classes"
          class="border border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-white hover:text-black transition">
          Explore Classes
        </a>
      </div>
    </div>
  </div>
</section>

<!-- HERO BACKGROUND SWITCH SCRIPT -->
<script>
  const bgImages = [
    "/images/bg.jpg",
    "/images/bg1.png",
  ];

  let currentBg = 0;
  const bgDiv = document.getElementById("hero-bg");

  function setBackground() {
    bgDiv.style.backgroundImage = `url('${bgImages[currentBg]}')`;
  }

  setBackground();

  document.getElementById("prev-bg")?.addEventListener("click", () => {
    currentBg = (currentBg - 1 + bgImages.length) % bgImages.length;
    setBackground();
  });

  document.getElementById("next-bg")?.addEventListener("click", () => {
    currentBg = (currentBg + 1) % bgImages.length;
    setBackground();
  });
</script>

<!-- About Section -->
<section id="about" class="py-20 bg-white">
  <div class="container mx-auto px-4">
    
    <!-- Title -->
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">
        About FTM
      </h2>
      <div class="w-24 h-1 bg-secondary mx-auto"></div>
    </div>

    <!-- Content Wrapper -->
    <div class="flex flex-col md:flex-row items-center gap-12">

      <!-- Image -->
      <div class="md:w-1/2 flex justify-center">
        <img
          src="./images/logo ftm (1).jpg"
          alt="Muslim women exercising"
          class="rounded-lg shadow-lg w-56 sm:w-72 md:w-full h-auto object-cover transition-all duration-300"
        />
      </div>

      <!-- Text -->
      <div class="md:w-1/2">
        <h3 class="text-2xl font-semibold text-primary mb-4">
          Vision And Mision
        </h3>

        <p class="text-gray-700 mb-6">
          FTM society adalah memberikan ruang bagi para muslimah untuk memiliki gaya hidup aktif dan produktif yang sesuai dengan syariat Islam. Oleh karena itu, FTM Society hadir menyelenggarakan kegiatan olahraga dan kegiatan aktif sosial lainnya, seperti webinar dan event.
        </p>

        <!-- Features (Fully Responsive) -->
        <div class="flex flex-col sm:flex-row sm:flex-wrap gap-6 sm:gap-8 mt-8">

          <!-- Muslimah Only -->
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-100 text-pink-600">
              <i class="ri-shield-check-line ri-lg"></i>
            </div>
            <span class="font-medium text-gray-800">Muslimah Only</span>
          </div>

          <!-- Certified Trainers -->
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-100 text-pink-600">
              <i class="ri-heart-pulse-line ri-lg"></i>
            </div>
            <span class="font-medium text-gray-800">Certified Trainers</span>
          </div>

          <!-- No Music & No Camera -->
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-100 text-pink-600">
              <i class="ri-pray-line ri-lg"></i>
            </div>
            <span class="font-medium text-gray-800">No Music & No Camera</span>
          </div>

        </div>
        <!-- End Features -->

      </div>
      <!-- End Text -->

    </div>
    <!-- End Wrapper -->

  </div>
</section>

<!-- Optional: Prevent Horizontal Scroll Global -->
<style>
  body { overflow-x: hidden; }
</style>


    <!-- Features Section (Slider) -->
<section id="packages" class="py-20 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">
        Why Choose FTM
      </h2>
      <div class="w-24 h-1 bg-secondary mx-auto mt-4"></div>
    </div>
    <div class="relative max-w-5xl mx-auto">
      <!-- Tombol kiri -->
      <button
        type="button"
        onclick="slideFeature(-1)"
        class="absolute left-0 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-10"
        aria-label="Scroll Left"
        id="featureScrollLeft"
        style="display:none"
      >
        <i class="ri-arrow-left-s-line text-2xl"></i>
      </button>
      <!-- Slider -->
      <div
        id="feature-slider"
        class="flex overflow-x-auto gap-8 scroll-smooth pb-4 px-2"
        style="scrollbar-width: none; -ms-overflow-style: none;"
        onscroll="toggleFeatureScroll()"
      >
        <!-- Feature 1 -->
        <div class="min-w-[320px] max-w-[340px] feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300 flex-shrink-0 flex flex-col items-center">
          <div class="w-16 h-16 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-6 mx-auto">
            <i class="ri-women-line ri-2x"></i>
          </div>
          <h3 class="text-xl font-semibold text-primary text-center mb-4">
            Muslimah-Only Space
          </h3>
          <p class="text-gray-600 text-center">
            Fasilitas kami hanya untuk wanita, dengan staf wanita saja. Nikmati privasi lengkap tanpa jendela yang menghadap area publik dan sistem masuk yang aman.
          </p>
        </div>
        <!-- Feature 2 -->
        <div class="min-w-[320px] max-w-[340px] feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300 flex-shrink-0 flex flex-col items-center">
          <div class="w-16 h-16 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-6 mx-auto">
            <i class="ri-user-star-line ri-2x"></i>
          </div>
          <h3 class="text-xl font-semibold text-primary text-center mb-4">
            Certified Muslimah Trainer
          </h3>
          <p class="text-gray-600 text-center">
            Dibimbing langsung oleh coach tersertifikasi
          </p>
        </div>
        <!-- Feature 3 -->
        <div class="min-w-[320px] max-w-[340px] feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300 flex-shrink-0 flex flex-col items-center">
        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-6 mx-auto">
          <i class="ri-shield-user-line ri-2x"></i> <!-- Ikon privasi -->
        </div>
        <h3 class="text-xl font-semibold text-primary text-center mb-4">
          Privacy is Our Priority
        </h3>
        <p class="text-gray-600 text-center">
          Ruang latihan khusus muslimah, tanpa kamera dan tanpa musik. Kami mengutamakan kenyamanan, keamanan, dan privasimu saat berolahraga.
        </p>
      </div>
        <!-- Feature 4 -->
        <div class="min-w-[320px] max-w-[340px] feature-card bg-white p-8 rounded-lg shadow-md transition-all duration-300 flex-shrink-0 flex flex-col items-center">
        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-6 mx-auto">
          <i class="ri-user-heart-line ri-2x"></i> <!-- Ikon yang mencerminkan kenyamanan & amanah -->
        </div>
        <h3 class="text-xl font-semibold text-primary text-center mb-4">
          Muslimah Friendly
        </h3>
        <p class="text-gray-600 text-center">
          Dirancang khusus untuk muslimah: area khusus wanita, pelatih perempuan bersertifikat, dan suasana nyaman sesuai nilai-nilai islami.
        </p>
      </div>
      <!-- Tombol kanan -->
      <button
        type="button"
        onclick="slideFeature(1)"
        class="absolute right-0 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-10"
        aria-label="Scroll Right"
        id="featureScrollRight"
      >
        <i class="ri-arrow-right-s-line text-2xl"></i>
      </button>
    </div>
  </div>
</section>
<script>
  function slideFeature(direction) {
    const slider = document.getElementById('feature-slider');
    const scrollAmount = 360; // card width + gap
    slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    setTimeout(toggleFeatureScroll, 300);
  }
  function toggleFeatureScroll() {
    const slider = document.getElementById('feature-slider');
    const leftBtn = document.getElementById('featureScrollLeft');
    const rightBtn = document.getElementById('featureScrollRight');
    leftBtn.style.display = slider.scrollLeft > 0 ? 'block' : 'none';
    rightBtn.style.display = (slider.scrollLeft + slider.clientWidth) < slider.scrollWidth ? 'block' : 'none';
  }
  window.addEventListener('DOMContentLoaded', toggleFeatureScroll);
</script>

 <!-- Additional Services (Slider) -->
<section id="Programs" class="py-20 bg-white">
  <div class="container mx-auto px-4">
    <div class="text-center mb-12">
      <h3 class="text-3xl md:text-4xl font-bold text-primary mb-4">Programs</h3>
      <div class="w-24 h-1 bg-secondary mx-auto mt-4"></div>
    </div>
    <div class="relative max-w-5xl mx-auto">
      <!-- Tombol kiri -->
      <button
        type="button"
        onclick="slideService(-1)"
        class="absolute left-0 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-10"
        aria-label="Scroll Left"
        id="serviceScrollLeft"
        style="display:none"
      >
        <i class="ri-arrow-left-s-line text-2xl"></i>
      </button>

      <!-- Slider -->
      <div
        id="service-slider"
        class="flex overflow-x-auto gap-6 scroll-smooth pb-4 px-2 justify-start md:justify-start"
        style="scrollbar-width: none; -ms-overflow-style: none;"
        onscroll="toggleServiceScroll()"
      >
        <!-- Card 1 -->
        <div class="min-w-[90vw] max-w-[240px] md:min-w-[220px] bg-white p-6 rounded-2xl shadow-lg flex-shrink-0 flex flex-col items-center border border-gray-100 hover:shadow-xl transition-all duration-300 mx-auto md:mx-0">
          <div class="w-14 h-14 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-4 shadow">
            <i class="ri-team-line ri-2x"></i>
          </div>
          <h4 class="font-semibold text-primary mb-2 text-center">Private Group Class</h4>
          <p class="text-gray-600 text-xs mb-4 text-center">
            Latihan kelompok privat dengan instruktur berpengalaman, cocok untuk komunitas atau teman-teman.
          </p>
          <div class="flex flex-col gap-1 mt-auto w-full justify-center">
            <a href="https://wa.me/6287785767395" target="_blank" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center">
              Booking Sekarang
            </a>
           
          </div>
        </div>

        <!-- Card 2 -->
        <div class="min-w-[90vw] max-w-[240px] md:min-w-[220px] bg-white p-6 rounded-2xl shadow-lg flex-shrink-0 flex flex-col items-center border border-gray-100 hover:shadow-xl transition-all duration-300 mx-auto md:mx-0">
          <div class="w-14 h-14 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-4 shadow">
            <i class="ri-user-heart-line ri-2x"></i>
          </div>
          <h4 class="font-semibold text-primary mb-2 text-center">Private Training</h4>
          <p class="text-gray-600 text-xs mb-4 text-center">
            Sesi latihan personal sesuai kebutuhan Anda, didampingi pelatih profesional untuk hasil optimal.
          </p>
          <div class="flex flex-col gap-1 mt-auto w-full justify-center">
            <a href="https://wa.me/6287785767395" target="_blank" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center">
              Booking Sekarang
            </a>
            
          </div>
        </div>

        <!-- Card 3 -->
        <div class="min-w-[90vw] max-w-[240px] md:min-w-[220px] bg-white p-6 rounded-2xl shadow-lg flex-shrink-0 flex flex-col items-center border border-gray-100 hover:shadow-xl transition-all duration-300 mx-auto md:mx-0">
          <div class="w-14 h-14 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-4 shadow">
            <i class="ri-calendar-check-line ri-2x"></i>
          </div>
          <h4 class="font-semibold text-primary mb-2 text-center">Single Visit Class</h4>
          <p class="text-gray-600 text-xs mb-4 text-center">
            Ikuti kelas tanpa harus menjadi member tetap. Fleksibel untuk Anda yang ingin mencoba atau punya jadwal padat.
          </p>
          <div class="flex flex-col gap-1 mt-auto w-full justify-center">
            <a href="https://wa.me/6287785767395" target="_blank" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center">
              Booking Sekarang
            </a>
            
          </div>
        </div>

        <!-- Card 4 -->
        <div class="min-w-[90vw] max-w-[240px] md:min-w-[220px] bg-white p-6 rounded-2xl shadow-lg flex-shrink-0 flex flex-col items-center border border-gray-100 hover:shadow-xl transition-all duration-300 mx-auto md:mx-0">
          <div class="w-14 h-14 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-4 shadow">
            <i class="ri-group-line ri-2x"></i>
          </div>
          <h4 class="font-semibold text-primary mb-2 text-center">Reformer Pilates</h4>
          <p class="text-gray-600 text-xs mb-4 text-center">
            Latihan pilates dengan alat reformer untuk kekuatan, fleksibilitas, dan postur tubuh yang lebih baik.
          </p>
          <div class="flex flex-col gap-1 mt-auto w-full justify-center">
            <a href="https://wa.me/6287785767395" target="_blank" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center">
              Booking Sekarang
            </a>
            
          </div>
        </div>

        <!-- Card 5 -->
        <div class="min-w-[90vw] max-w-[240px] md:min-w-[220px] bg-white p-6 rounded-2xl shadow-lg flex-shrink-0 flex flex-col items-center border border-gray-100 hover:shadow-xl transition-all duration-300 mx-auto md:mx-0">
          <div class="w-14 h-14 flex items-center justify-center rounded-full bg-primary bg-opacity-10 text-primary mb-4 shadow">
            <i class="ri-award-line ri-2x"></i>
          </div>
          <h4 class="font-semibold text-primary mb-2 text-center">Exclusive Class Program</h4>
          <p class="text-gray-600 text-xs mb-4 text-center">
            Program kelas eksklusif dengan materi pilihan, peserta terbatas, dan pendampingan intensif.
          </p>
          <div class="flex flex-col gap-1 mt-auto w-full justify-center">
            <a href="https://wa.me/6287785767395" target="_blank" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center">
              Booking Sekarang
            </a>
            
          </div>
        </div>
      </div>

      <!-- Tombol kanan -->
      <button
        type="button"
        onclick="slideService(1)"
        class="absolute right-0 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-10"
        aria-label="Scroll Right"
        id="serviceScrollRight"
      >
        <i class="ri-arrow-right-s-line text-2xl"></i>
      </button>
    </div>
  </div>
</section>

<script>
  function slideService(direction) {
    const slider = document.getElementById('service-slider');
    const isMobile = window.innerWidth < 768;
    const scrollAmount = isMobile ? (window.innerWidth * 0.9 + 24) : 260;
    slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    setTimeout(toggleServiceScroll, 300);
  }

  function toggleServiceScroll() {
    const slider = document.getElementById('service-slider');
    const leftBtn = document.getElementById('serviceScrollLeft');
    const rightBtn = document.getElementById('serviceScrollRight');
    leftBtn.style.display = slider.scrollLeft > 0 ? 'block' : 'none';
    rightBtn.style.display = (slider.scrollLeft + slider.clientWidth) < slider.scrollWidth ? 'block' : 'none';
  }

  window.addEventListener('DOMContentLoaded', toggleServiceScroll);
  window.addEventListener('resize', toggleServiceScroll);
</script>


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

<script>
  const serviceDetails = {
    'private-group': {
      title: 'Private Group Class',
      content: `
        <ul class="mb-3 list-disc pl-5">
          <li>Latihan kelompok privat dengan instruktur profesional</li>
          <li>Cocok untuk komunitas, keluarga, atau teman</li>
          <li>Jadwal fleksibel & suasana eksklusif</li>
        </ul>
        <p class="mb-2 font-semibold">Fasilitas:</p>
        <ul class="mb-3 list-disc pl-5">
          <li>Ruang latihan khusus</li>
          <li>Peralatan lengkap</li>
          <li>Free konsultasi awal</li>
        </ul>
      `
    },
    'private-training': {
      title: 'Private Training',
      content: `
        <ul class="mb-3 list-disc pl-5">
          <li>Latihan 1-on-1 dengan trainer profesional</li>
          <li>Program disesuaikan dengan kebutuhan Anda</li>
        </ul>
      `
    },
    'single-visit': {
      title: 'Single Visit Class',
      content: `
        <ul class="mb-3 list-disc pl-5">
          <li>Semi privat max 6–7 orang</li>
          <li>Coach perempuan tersertifikasi</li>
        </ul>
      `
    },
    'reformer-pilates': {
      title: 'Reformer Pilates',
      content: `
        <ul class="mb-3 list-disc pl-5">
          <li>Group Class: semi private max 3 orang</li>
          <li>Private Class untuk special case</li>
        </ul>
      `
    },
    'exclusive-program': {
      title: 'Exclusive Class Program',
      content: `
        <ul class="mb-3 list-disc pl-5">
          <li>8 sesi per bulan (2x/minggu)</li>
          <li>Dilatih oleh pelatih perempuan muslim tersertifikasi</li>
          <li>Semi private max 6–7 orang</li>
        </ul>
      `
    },
  };

  function showServiceDetail(key) {
    const modal = document.getElementById('service-detail-modal');
    const box = document.getElementById('service-detail-box');

    document.getElementById('service-detail-title').textContent = serviceDetails[key].title;
    document.getElementById('service-detail-content').innerHTML = serviceDetails[key].content;

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // animasi masuk
    setTimeout(() => {
      box.classList.remove('opacity-0', 'scale-95');
      box.classList.add('opacity-100', 'scale-100');
    }, 10);
  }

  function closeServiceDetail() {
    const modal = document.getElementById('service-detail-modal');
    const box = document.getElementById('service-detail-box');

    // animasi keluar
    box.classList.add('opacity-0', 'scale-95');
    box.classList.remove('opacity-100', 'scale-100');

    setTimeout(() => {
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }, 200);
  }
</script>


<!-- Packages & Pricing Section --><section class="py-20 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">
        Packages & Pricing
      </h2>
      <p class="text-gray-600 max-w-2xl mx-auto">
        Choose the perfect plan that fits your fitness journey and lifestyle.
      </p>
      <div class="w-24 h-1 bg-secondary mx-auto mt-4"></div>
    </div> 

    <div class="relative">
      <!-- Tombol scroll kiri -->
      <button
        type="button"
        onclick="slideMembership(-1)"
        class="absolute left-0 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-10"
        aria-label="Scroll Left"
        id="membershipScrollLeft"
        style="display:none"
      >
        <i class="ri-arrow-left-s-line text-2xl"></i>
      </button>

      <!-- Daftar membership scrollable -->
      <div
        id="membershipList"
        class="flex overflow-x-auto gap-8 scroll-smooth pb-4"
        style="scrollbar-width: none; -ms-overflow-style: none;"
        onscroll="toggleMembershipScroll()"
      >

      
    <div class="relative">
      <!-- Tombol scroll kiri -->
      <button
        type="button"
        onclick="slideMembership(-1)"
        class="absolute left-0 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-10"
        aria-label="Scroll Left"
        id="membershipScrollLeft"
        style="display:none"
      >
        <i class="ri-arrow-left-s-line text-2xl"></i>
      </button>

      <!-- Daftar membership scrollable -->
      <div
        id="membershipList"
        class="flex overflow-x-auto gap-8 scroll-smooth pb-4"
        style="scrollbar-width: none; -ms-overflow-style: none;"
        onscroll="toggleMembershipScroll()"
      >

        <!-- Card 1: Exclusive Class Program -->
        <div class="min-w-[320px] min-h-[500px] bg-white rounded-2xl shadow-md p-8 border-t-4 border-gradient-to-r from-primary to-secondary hover:scale-105 hover:shadow-2xl transition-all flex flex-col relative">
          <span class="absolute top-4 right-4 bg-gradient-to-r from-primary to-secondary text-white text-xs px-3 py-1 rounded-full font-bold shadow">Best Value</span>
          <div class="flex items-center justify-center mb-4">
            <i class="ri-star-smile-line text-4xl text-secondary"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Exclusive Class Program</h3>
          <p class="text-2xl font-extrabold text-primary mb-4 text-center">IDR 850K <span class="text-base font-normal text-gray-500">/ Month</span></p>
          <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Muaythai</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Mat Pilates</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Body Shaping</li>
          </ul>

          <div class="mt-auto">
            @if(auth('customer')->check())
              <form action="{{ route('guest.checkout.process', ['package' => 1]) }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ auth('customer')->user()->name }}">
                <input type="hidden" name="email" value="{{ auth('customer')->user()->email }}">
                <input type="hidden" name="phone" value="{{ auth('customer')->user()->phone }}">
                <a href="#signup"
   class="w-full bg-primary text-white px-4 py-2 rounded-lg block text-center hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold">
    Daftar Sekarang
</a>

              </form>
            @else
              <a href="{{ route('join.package', ['package' => 1]) }}"
                 class="w-full bg-primary text-white px-4 py-2 rounded-lg block text-center hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold">
                Daftar Sekarang
              </a>
            @endif
            <button type="button" onclick="showServiceDetail('exclusive-program')" class="w-full text-secondary text-xs mt-1 hover:underline">Lihat Selengkapnya</button>
          </div>
        </div>

        <!-- Card 2: Reformer Pilates -->
        <div class="min-w-[320px] min-h-[500px] bg-white rounded-2xl shadow-md p-8 border-t-4 border-primary hover:scale-105 hover:shadow-2xl transition-all flex flex-col">
          <div class="flex items-center justify-center mb-4">
            <i class="ri-group-line text-4xl text-secondary"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Reformer Pilates</h3>
          <p class="text-2xl font-extrabold text-primary mb-4 text-center">Single Visit Group Class</p>
          <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 400K / Single</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 700K / Double</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 900K / Triple</li>
          </ul>
          <div class="mt-auto">
            @if(auth('customer')->check())
              <form action="{{ route('guest.checkout.process', ['package' => 2]) }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ auth('customer')->user()->name }}">
                <input type="hidden" name="email" value="{{ auth('customer')->user()->email }}">
                <input type="hidden" name="phone" value="{{ auth('customer')->user()->phone }}">
                <button type="submit" class="w-full bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all font-semibold">
                  Daftar Sekarang
                </button>
              </form>
            @else
              <a href="{{ route('join.package', ['package' => 2]) }}"
                 class="w-full bg-primary text-white px-6 py-2 rounded-lg block text-center hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all font-semibold">
                Daftar Sekarang
              </a>
            @endif
            <button type="button" onclick="showServiceDetail('reformer-pilates')" class="w-full text-secondary text-xs mt-1 hover:underline">Lihat Selengkapnya</button>
          </div>
        </div>

        <!-- Card 3: Single Visit Class -->
        <div class="min-w-[320px] min-h-[500px] bg-white rounded-2xl shadow-md p-8 border-t-4 border-primary hover:scale-105 hover:shadow-2xl transition-all flex flex-col">
          <div class="flex items-center justify-center mb-4">
            <i class="ri-door-open-line text-4xl text-secondary"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Single Visit Class</h3>
          <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Single Class: IDR.150K</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Bundle 2 Class: IDR.275K</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Bundle 4 Class: IDR.525K</li>
          </ul>
          <div class="mt-auto">
            @if(auth('customer')->check())
              <form action="{{ route('guest.checkout.process', ['package' => 3]) }}" method="POST">
                @csrf
                <input type="hidden" name="name" value="{{ auth('customer')->user()->name }}">
                <input type="hidden" name="email" value="{{ auth('customer')->user()->email }}">
                <input type="hidden" name="phone" value="{{ auth('customer')->user()->phone }}">
                <button type="submit" class="w-full bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all font-semibold">
                  Daftar Sekarang
                </button>
              </form>
            @else
              <a href="{{ route('join.package', ['package' => 3]) }}"
                 class="w-full bg-primary text-white px-6 py-2 rounded-lg block text-center hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all font-semibold">
                Daftar Sekarang
              </a>
            @endif
            <button type="button" onclick="showServiceDetail('single-visit')" class="w-full text-secondary text-xs mt-1 hover:underline">Lihat Selengkapnya</button>
          </div>
        </div>

        <!-- Card 4: Reformer Pilates Packages -->
        <div class="min-w-[320px] min-h-[500px] bg-white rounded-2xl shadow-md p-8 border-t-4 border-primary hover:scale-105 hover:shadow-2xl transition-all flex flex-col">
          <div class="flex items-center justify-center mb-4">
            <i class="ri-calendar-check-line text-4xl text-secondary"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Reformer Pilates</h3>
          <p class="text-2xl font-extrabold text-primary mb-4 text-center">Packages</p>
          <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 400K / Single Visit</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 1.400K / 4 Sessions 15 Days</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 1.540K / 4 Sessions 30 Days</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 2.200K / 8 Sessions 30 Days</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>IDR 2.640K / 8 Sessions 60 Days</li>
          </ul>
          <div class="mt-auto">
              <a href="#signup"
   class="w-full bg-primary text-white px-4 py-2 rounded-lg block text-center hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold">
    Daftar Sekarang
</a>

            </form>
            <button type="button" onclick="showServiceDetail('reformer-pilates')" class="w-full text-secondary text-xs mt-1 hover:underline focus:outline-none bg-transparent border-0 p-0" style="box-shadow:none;">
              Lihat Selengkapnya
            </button>
          </div>
        </div>

        <!-- Card 5: Private Program -->
        <div class="min-w-[320px] min-h-[500px] bg-white rounded-2xl shadow-md p-8 border-t-4 border-primary hover:scale-105 hover:shadow-2xl transition-all flex flex-col">
          <div class="flex items-center justify-center mb-4">
            <i class="ri-user-line text-4xl text-secondary"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Private Program</h3>
          <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Muaythai</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Mat Pilates</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Body Shaping</li>
          </ul>
          <div class="mt-auto">
            <a href="https://wa.me/6287785767395" target="_blank" class="w-full bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all block text-center font-semibold">
              HUBUNGI TEAM KAMI
            </a>
            <button type="button" onclick="showServiceDetail('private-training')" class="w-full text-secondary text-xs mt-1 hover:underline focus:outline-none bg-transparent border-0 p-0" style="box-shadow:none;">
              Lihat Selengkapnya
            </button>
          </div>
        </div>

        <!-- Card 6: Private Group Program -->
        <div class="min-w-[320px] min-h-[500px] bg-white rounded-2xl shadow-md p-8 border-t-4 border-primary hover:scale-105 hover:shadow-2xl transition-all flex flex-col">
          <div class="flex items-center justify-center mb-4">
            <i class="ri-team-line text-4xl text-secondary"></i>
          </div>
          <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Private Group Program</h3>
          <ul class="text-sm text-gray-600 space-y-2 mb-4">
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Muaythai</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Mat Pilates</li>
            <li><i class="ri-checkbox-circle-fill text-secondary mr-2"></i>Body Shaping</li>
          </ul>
          <div class="mt-auto">
            <a href="https://wa.me/6287785767395" target="_blank" class="w-full bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all block text-center font-semibold">
              HUBUNGI TEAM KAMI
            </a>
            <button type="button" onclick="showServiceDetail('private-group')" class="w-full text-secondary text-xs mt-1 hover:underline focus:outline-none bg-transparent border-0 p-0" style="box-shadow:none;">
              Lihat Selengkapnya
            </button>
          </div>
        </div>

      </div> <!-- End membershipList -->
    </div>
  </div>
</section>
          <!-- Daftar membership scrollable -->
  <div
    id="membershipList"
    class="flex overflow-x-auto gap-8 scroll-smooth pb-4"
    style="scrollbar-width: none; -ms-overflow-style: none;"
    onscroll="toggleMembershipScroll()"
  >
        <!-- Card Template End -->
      </div>
      <!-- Tombol scroll kanan -->
      <button
        type="button"
        onclick="slideMembership(1)"
        class="absolute right-0 top-1/2 -translate-y-1/2 bg-white text-primary rounded-full p-2 shadow hover:bg-primary hover:text-white transition z-10"
        aria-label="Scroll Right"
        id="membershipScrollRight"
      >
        <i class="ri-arrow-right-s-line text-2xl"></i>
      </button>
    </div>
  </div>
</section>


<!-- Tambahkan link Remix Icon di <head> jika belum -->
<!-- <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet"> -->

<script>
  function slideMembership(direction) {
    const slider = document.getElementById('membershipList');
    const scrollAmount = 350;
    slider.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    setTimeout(toggleMembershipScroll, 300);
  }

  function toggleMembershipScroll() {
    const slider = document.getElementById('membershipList');
    const leftBtn = document.getElementById('membershipScrollLeft');
    const rightBtn = document.getElementById('membershipScrollRight');
    leftBtn.style.display = slider.scrollLeft > 0 ? 'block' : 'none';
    rightBtn.style.display = (slider.scrollLeft + slider.clientWidth) < slider.scrollWidth ? 'block' : 'none';
  }

  window.addEventListener('DOMContentLoaded', toggleMembershipScroll);
  window.addEventListener('resize', toggleMembershipScroll);
</script>

<!-- Our Classes Section -->
<section id="classes" class="py-20 bg-white">
  <div class="container mx-auto px-4">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Classes</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">
        Discover a variety of fitness programs designed specifically for your needs.
      </p>
      <div class="w-24 h-1 bg-secondary mx-auto mt-4"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

      <!-- Class Card Example -->
      <div class="bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-xl duration-300 flex flex-col">
        <div class="relative h-48">
          <img src="./images/muaythai.png" alt="Muaythai" class="w-full h-full object-cover" />
          <div class="absolute top-2 right-2 bg-primary text-white text-xs px-2 py-1 rounded">Popular</div>
        </div>
        <div class="p-5 flex-1 flex flex-col">
          <h3 class="text-xl font-semibold text-primary mb-2">Muaythai</h3>
          <div class="text-sm text-gray-500 mb-3">
            <span class="mr-4"><i class="ri-time-line mr-1"></i> 45 min</span>
            <span><i class="ri-bar-chart-line mr-1"></i> All Levels</span>
          </div>
          <p class="text-sm text-gray-600 mb-4 flex-1">
            Muaythai adalah seni bela diri asal Thailand yang menggunakan delapan titik kontak tubuh: dua tangan, dua siku, dua lutut, dan dua kaki.Muaythai melibatkan teknik serangan dan pertahanan.
          </p>
          <button onclick="openModal('muaythai')" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center mx-auto block">Cek Sekarang</button>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-xl duration-300 flex flex-col">
        <div class="relative h-48">
          <img src="./images/body shaping.png" alt="Body Shaping" class="w-full h-full object-cover" />
        </div>
        <div class="p-5 flex-1 flex flex-col">
          <h3 class="text-xl font-semibold text-primary mb-2">Body Shaping</h3>
          <div class="text-sm text-gray-500 mb-3">
            <span class="mr-4"><i class="ri-time-line mr-1"></i> 30 min</span>
            <span><i class="ri-bar-chart-line mr-1"></i> All Levels</span>
          </div>
          <p class="text-sm text-gray-600 mb-4 flex-1">
            Body Shaping adalah kelas strength training.Full body workout yang bertujuan untuk toning dan shaping tubuh.Gerakannya bervariasi, dari calisthenic sampai gerakan dengan beban dan equipment pendukung lainnya.
          </p>
          <button onclick="openModal('body-shaping')" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center mx-auto block">Cek Sekarang</button>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-xl duration-300 flex flex-col">
        <div class="relative h-48">
          <img src="./images/mat pilates.png" alt="Mat Pilates" class="w-full h-full object-cover" />
        </div>
        <div class="p-5 flex-1 flex flex-col">
          <h3 class="text-xl font-semibold text-primary mb-2">Mat Pilates</h3>
          <div class="text-sm text-gray-500 mb-3">
            <span class="mr-4"><i class="ri-time-line mr-1"></i> 60 min</span>
            <span><i class="ri-bar-chart-line mr-1"></i> All Levels</span>
          </div>
          <p class="text-sm text-gray-600 mb-4 flex-1">
            Mat Pilates adalah latihan berbasis gerakan yang dilakukan di atas matras, fokus pada kekuatan inti (core), stabilitas, postur, pernapasan, dan fleksibilitas.Gerakan dilakukan secara perlahan dan terkontrol.
          </p>
          <button onclick="openModal('mat-pilates')" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center mx-auto block">Cek Sekarang</button>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-md overflow-hidden transition hover:shadow-xl duration-300 flex flex-col">
        <div class="relative h-48">
          <img src="./images/revormer pilates.png" alt="Reformer Pilates" class="w-full h-full object-cover" />
          <div class="absolute top-2 right-2 bg-primary text-white text-xs px-2 py-1 rounded">Popular</div>
        </div>
        <div class="p-5 flex-1 flex flex-col">
          <h3 class="text-xl font-semibold text-primary mb-2">Reformer Pilates</h3>
          <div class="text-sm text-gray-500 mb-3">
            <span class="mr-4"><i class="ri-time-line mr-1"></i> 45 min</span>
            <span><i class="ri-bar-chart-line mr-1"></i> All Levels</span>
          </div>
          <p class="text-sm text-gray-600 mb-4 flex-1">
            Reformer Pilates menggunakan alat bernama reformer — (seperti di gambar) dengan bantuan pegas dan tali — untuk memberikan resistensi tambahan.Latihannya mirip dengan Mat Pilates dengan variasi yang dibantu oleh alat reformernya.
          </p>
          <button onclick="openModal('reformer-pilates')" class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center mx-auto block">Cek Sekarang</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Pop-up -->
<div id="class-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
  <div class="bg-white w-full max-w-lg rounded-xl shadow-lg p-6 relative animate-fadeIn">
    <button onclick="closeModal()" class="absolute top-3 right-3 text-gray-500 hover:text-red-600 text-xl cursor-pointer">&times;</button>
    <h3 id="modal-title" class="text-2xl font-semibold text-primary mb-4"></h3>
    <div id="modal-content">
      <!-- Jadwal kelas akan diisi oleh JS -->
    </div>
    <div class="mt-6 flex flex-col gap-3">
      <a
        id="modal-wa-btn"
        href="#"
        target="_blank"
        class="w-full bg-primary text-white px-4 py-2 rounded-lg text-center font-semibold hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm flex items-center justify-center gap-2"
      >
        <i class="ri-whatsapp-line text-xl"></i>
        Daftar via WhatsApp
      </a>
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
const classSchedules = {
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

<script>
  const classPrograms = {
    'muaythai': ["Exclusive Class Program", "Single Visit Class Program", "Private Program"],
    'body-shaping': ["Exclusive Class Program", "Single Visit Class Program", "Private Program"],
    'mat-pilates': ["Exclusive Class Program", "Single Visit Class Program", "Private Program"],
    'reformer-pilates': ["Single Visit Group Class", "Single Visit Packages"]
  };

  const waMessages = {
    'muaythai': "Halo FTM Society, saya ingin daftar kelas Muaythai.",
    'body-shaping': "Halo FTM Society, saya tertarik dengan kelas Body Shaping.",
    'mat-pilates': "Halo FTM Society, saya ingin info dan daftar Mat Pilates.",
    'reformer-pilates': "Halo FTM Society, saya ingin ikut Reformer Pilates."
  };

  const waNumber = "6287785767395";

  function openModal(key) {
    const modal = document.getElementById('class-modal');
    const title = document.getElementById('modal-title');
    const content = document.getElementById('modal-content');
    const waBtn = document.getElementById('modal-wa-btn');
    title.textContent = "Jadwal Kelas Exclusive Program";

    waBtn.onclick = function (e) {
      e.preventDefault();
      const waText = encodeURIComponent(waMessages[key] || "Halo FTM Society, saya ingin daftar kelas.");
      const waLink = `https://wa.me/${waNumber}?text=${waText}`;
      window.open(waLink, '_blank');
    };

    let jadwalHTML = `<h4 class="font-semibold text-primary mb-2 text-lg">Jadwal Kelas ${key.replace('-', ' ').replace(/\b\w/g, l => l.toUpperCase())}</h4>`;
    if (classSchedules[key]) {
      jadwalHTML += `<table class="w-full mb-4 text-sm">
        <thead>
          <tr>
            <th class="text-left py-1 px-2 text-gray-600">Class</th>
            <th class="text-left py-1 px-2 text-gray-600">Hari</th>
            <th class="text-left py-1 px-2 text-gray-600">Jam</th>
            <th class="text-left py-1 px-2 text-gray-600">Instruktur</th>
          </tr>
        </thead>
        <tbody>
          ${classSchedules[key].map(j => `
          <tr>
            <td class="py-1 px-2 text-xs text-gray-500">${j.kelas}</td>
            <td class="py-1 px-2">${j.hari}</td>
            <td class="py-1 px-2">${j.jam ? j.jam.split(' ')[1].slice(0,5) : ''}</td>
            <td class="py-1 px-2">${j.instruktur}</td>
          </tr>
        `).join('')}
        </tbody>
      </table>`;
    } else {
      jadwalHTML += `<p class="text-gray-500 mb-4">Jadwal belum tersedia.</p>`;
    }

    let programs = classPrograms[key] || [];
    let serviceHTML = `<h4 class="font-semibold text-primary mb-2 text-lg">Pilihan Programs</h4>
      <ul class="mb-4 grid grid-cols-2 gap-2">
        ${programs.map(s => `
          <li class="bg-white border-2 border-secondary rounded-full px-3 py-2 text-xs text-primary font-semibold text-center shadow-sm transition-all duration-200 hover:bg-secondary hover:text-white cursor-pointer" style="border-color:#c68e8f;">
            ${s}
          </li>
        `).join('')}
      </ul>`;

    content.innerHTML = jadwalHTML + serviceHTML;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  }

  function closeModal() {
    const modal = document.getElementById('class-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }
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
<section id="schedule" class="py-10 px-4 bg-gray-50">
  <div class="max-w-3xl mx-auto text-center">
    <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6 uppercase tracking-wide">
      Jadwal Kelas Exclusive Program
    </h2>

    <!-- WRAPPER supaya tabel bisa discroll jika layar kecil -->
    <div class="overflow-x-auto rounded-xl shadow-md bg-white">
      <table class="min-w-[600px] w-full text-sm md:text-base text-gray-700">
        <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
        </thead>

        <table class="w-full border-collapse">
    <thead>
        <tr class="bg-gray-800 text-white">
            <th class="py-3 px-4 text-center">Class</th>
            <th class="py-3 px-4 text-center">Day</th>
            <th class="py-3 px-4 text-center">Time</th>
            <th class="py-3 px-4 text-center">Coach</th>
        </tr>
    </thead>

    <tbody>
@foreach($schedules as $day => $items)

    {{-- HEADER HARI --}}
    <tr class="bg-gray-100">
        <td colspan="4" class="py-2 px-4 font-bold text-left">
            {{ $day }}
        </td>
    </tr>

    {{-- DATA JADWAL --}}
    @foreach($items as $schedule)
        <tr class="border-t hover:bg-gray-50 transition">
            <td class="py-3 px-4 text-center font-medium">
                {{ $schedule->classModel->class_name ?? '-' }}
            </td>

            <td class="py-3 px-4 text-center">
                {{ $schedule->day }}
            </td>

            <td class="py-3 px-4 text-center">
                {{ \Carbon\Carbon::parse($schedule->class_time)->format('H:i') }}
            </td>

            <td class="py-3 px-4 text-center">
                {{ $schedule->instructor ?? '-' }}
            </td>
        </tr>
    @endforeach

@endforeach
</tbody>
      </table>
    </div>
  </div>
</section>

<!-- Facility Gallery Section -->
<section id="Facility" class="py-24 bg-white">
  <div class="container mx-auto px-4">
    <!-- Title Section -->
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold text-primary mb-4">Our Gallery</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Explore our facilities through the gallery below.</p>
      <div class="w-24 h-1 bg-secondary mx-auto mt-4"></div>
    </div>

    <!-- Slider Wrapper -->
    <div id="facility-slider" class="relative max-w-4xl mx-auto aspect-[16/9] overflow-hidden rounded-2xl shadow-lg group">
      <!-- Image Track -->
      <div class="flex transition-transform duration-700 ease-in-out h-full w-full">
        <img src="{{ asset('images/bg1.png') }}" alt="Facility 5"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/muaythai.png') }}" alt="Facility 1"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/revormer pilates.png') }}" alt="Facility 3"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
         <img src="{{ asset('images/foto5.png') }}" alt="Facility 9"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/mat pilates.png') }}" alt="Facility 4"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/body shaping.png') }}" alt="Facility 2"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/foto1.png') }}" alt="Facility 6"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/foto2.png') }}" alt="Facility 7"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/foto3.png') }}" alt="Facility 8"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
        <img src="{{ asset('images/foto4.png') }}" alt="Facility 9"
          class="w-full h-full object-cover flex-shrink-0 transition duration-500 group-hover:scale-105" />
      </div>
      </div>

      

    <!-- Indicator -->
      <div class="text-center mt-6">
      <span
        id="facility-indicator"
        class="inline-block text-primary font-semibold rounded-full px-4 py-1 bg-white shadow-sm"
        style="border: 2px solid #c68e8f;"
      ></span>
    </div>
      </div>
    </section>

<!-- JavaScript Slider Logic -->
<script>
  const images = [
  "{{ asset('images/bg1.png') }}",
  "{{ asset('images/muaythai.png') }}",
  "{{ asset('images/revormer pilates.png') }}",
  "{{ asset('images/foto5.png') }}",
  "{{ asset('images/mat pilates.png') }}",
  "{{ asset('images/body shaping.png') }}",
  "{{ asset('images/foto1.png') }}",
  "{{ asset('images/foto2.ppg') }}",
  "{{ asset('images/foto3.png') }}",
  "{{ asset('images/foto4.png') }}"
];
  let current = 0;
  let autoSlide;

  function updateSlider() {
    const slider = document.querySelector('#facility-slider .flex');
    slider.style.transform = `translateX(-${current * 100}%)`;
    document.getElementById('facility-indicator').textContent = `${current + 1} / ${images.length}`;
  }

  function nextFacility() {
    current = (current + 1) % images.length;
    updateSlider();
    resetAutoSlide();
  }

  function prevFacility() {
    current = (current - 1 + images.length) % images.length;
    updateSlider();
    resetAutoSlide();
  }

  function resetAutoSlide() {
    clearInterval(autoSlide);
    autoSlide = setInterval(nextFacility, 4000);
  }

  document.addEventListener('DOMContentLoaded', function () {
    updateSlider();
    autoSlide = setInterval(nextFacility, 4000);
  });
</script>

<!-- Partner Section -->
<section class="py-16">
  <div class="text-center mb-10">
    <h2 class="text-4xl font-bold text-primary mb-2">Partner</h2>
    <p class="text-gray-600 max-w-2xl mx-auto">
      Supported by Trusted Partners Committed to a Healthy Lifestyle.
    </p>
    <div class="w-24 h-1 bg-secondary mx-auto mt-4"></div>
  </div>

  <!-- Wrapper -->
  <div class="overflow-hidden relative py-4">

    <!-- Track berjalan -->
    <div class="flex items-center gap-10 animate-scroll whitespace-nowrap">

      <!-- Duplikasi logo (wajib agar looping mulus) -->
      <img src="icons/partner 2..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 3..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 4..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 5..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 6..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 1..png" class="h-20 mx-4 object-contain" />

      <!-- COPY AGAIN UNTUK LOOP TANPA JEDa -->
      <img src="icons/partner 2..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 3..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 4..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 5..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 6..png" class="h-20 mx-4 object-contain" />
      <img src="icons/partner 1..png" class="h-20 mx-4 object-contain" />
    </div>
  </div>
</section>

<style>
  @keyframes scroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }

  .animate-scroll {
    animation: scroll 18s linear infinite;
  }
</style>






    <!-- Join Now Section -->
    <!-- Tambahkan setelah form Data-Member -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.querySelector('form[name="Data-Member"]');
  if (form) {
    form.addEventListener('submit', function(e) {
    });
  }
});
</script>
    <section id="join" class="py-20 bg-white">
      <div class="container mx-auto px-4">
        <div class="bg-primary rounded-lg overflow-hidden shadow-lg">
          <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2 p-10 md:p-16 flex flex-col justify-center">
              <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Start Your Journey Today
              </h2>
              <p class="text-white text-opacity-90 mb-8">
                Join our community of strong, motivated Muslimah women. Take the
                first step toward a healthier, more balanced life in a space
                designed just for you.
              </p>
              <div class="space-y-6">
                <div class="flex items-start space-x-4">
                  <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-primary flex-shrink-0 mt-1"
                  >
                    <i class="ri-check-line ri-lg"></i>
                  </div>
                  <div>
                    <h3 class="text-white font-semibold text-lg">
                      Exclusive Access to Members-Only Class
                    </h3>
                    <p class="text-white text-opacity-80">
                      Step into a private, peaceful training space — thoughtfully designed for Muslimah who value comfort, privacy, and premium quality.
                    </p>
                  </div>
                </div>
                <div class="flex items-start space-x-4">
                  <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-primary flex-shrink-0 mt-1"
                  >
                    <i class="ri-check-line ri-lg"></i>
                  </div>
                  <div>
                    <h3 class="text-white font-semibold text-lg">
                      Personal Guidance from Muslimah Trainers
                    </h3>
                    <p class="text-white text-opacity-80">
                      Train with experienced female instructors who understand your values and ensure a safe, modest, and effective workout experience.
                    </p>
                  </div>
                </div>
                <div class="flex items-start space-x-4">
                  <div
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-primary flex-shrink-0 mt-1"
                  >
                    <i class="ri-check-line ri-lg"></i>
                  </div>
                  <div>
                    <h3 class="text-white font-semibold text-lg">
                      Flexible Membership Options
                    </h3>
                    <p class="text-white text-opacity-80">
                      Choose the plan that works best for your schedule and
                      budget.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            
           <div id="signup" class="md:w-1/2 bg-white p-10 md:p-16">
    <h3 class="text-2xl font-semibold text-primary mb-6">
    Sign Up Now
</h3>

{{-- Error Validasi --}}
@if ($errors->any())
    <div class="mb-4 text-red-600">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Popup Sukses --}}
@if(session('success'))
    <div id="success-popup" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
        <div class="bg-white rounded-xl shadow-2xl px-8 py-8 max-w-sm w-full text-center border-t-4 border-secondary animate-fadeIn">
            <div class="flex justify-center mb-4">
                <div class="bg-secondary bg-opacity-20 rounded-full p-4">
                    <i class="ri-checkbox-circle-line text-4xl text-secondary"></i>
                </div>
            </div>
            <h4 class="text-xl font-bold text-primary mb-2">Berhasil!</h4>
            <p class="text-gray-700 mb-6">{{ session('success') }}</p>
            <button onclick="document.getElementById('success-popup').remove()" class="bg-secondary text-white px-6 py-2 rounded hover:bg-opacity-90 transition font-semibold">
                Tutup
            </button>
        </div>
    </div>
@endif

<form name="Data-Member" method="POST" action="{{ route('public.customers.store') }}" class="space-y-6">
    @csrf
    <div>
        <label for="name" class="block text-gray-700 mb-2">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Nama lengkap" value="{{ old('name') }}" required
            class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary" />
    </div>

    <div>
        <label for="phone_number" class="block text-gray-700 mb-2">Phone Number</label>
        <input type="tel" id="phone_number" name="phone_number" placeholder="08xxx" value="{{ old('phone_number') }}" required
            class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary" />
    </div>

    <div>
        <label for="email" class="block text-gray-700 mb-2">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required
            class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary" />
    </div>

    <div>
        <label for="birth_date" class="block text-gray-700 mb-2">Tanggal Lahir</label>
<input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
    class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary" />
    </div>

    <div>
        <label for="goals" class="block text-gray-700 mb-2">Goals</label>
        <textarea id="goals" name="goals" rows="3" placeholder="Contoh: Ingin menurunkan berat badan" class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary">{{ old('goals') }}</textarea>
    </div>

    <div>
        <label for="kondisi_khusus" class="block text-gray-700 mb-2">Kondisi Khusus</label>
        <textarea id="kondisi_khusus" name="kondisi_khusus" rows="3" placeholder="Contoh: Riwayat asma, cedera lutut" class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary">{{ old('kondisi_khusus') }}</textarea>
    </div>

    <div>
        <label for="referensi" class="block text-gray-700 mb-2">Mengenal FTM dari</label>
        <input type="text" id="referensi" name="referensi" placeholder="Contoh: Instagram, teman, Google" value="{{ old('referensi') }}"
            class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary" />
    </div>

    <div>
        <label for="pengalaman" class="block text-gray-700 mb-2">Pengalaman ikut olahraga?</label>
        <input type="text" id="pengalaman" name="pengalaman" placeholder="Contoh: Pernah ikut yoga, gym, dll" value="{{ old('pengalaman') }}"
            class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary" />
    </div>

    <div>
    <label for="is_muslim" class="block text-gray-700 mb-2">Apakah Anda Muslimah?</label>
    <div class="mb-2 text-xs text-yellow-700 bg-yellow-100 rounded px-3 py-2">
        <strong>P.S:</strong> Kolom Agama Islam diperlukan karena adanya perbedaan pendapat di kalangan para ulama tentang batasan aurat perempuan muslim di hadapan perempuan non-muslim. Karenanya, kami mengambil pendapat yang paling hati-hati dalam perkara ini. Kami tidak meminta bukti KTP Anda, oleh karena itu, kami mohon kerjasamanya agar mengisi form dengan jujur sebagai bentuk toleransi terhadap apa yang kami yakini. Semoga ridho dan berkenan.
    </div>
    <select id="is_muslim" name="is_muslim" required class="w-full px-4 py-3 rounded border border-gray-300 focus:border-secondary">
        <option value="">-- Pilih --</option>
        <option value="ya" {{ old('is_muslim') == 'ya' ? 'selected' : '' }}>Ya</option>
        <option value="tidak" {{ old('is_muslim') == 'tidak' ? 'selected' : '' }}>Tidak</option>
    </select>
</div>

    <div>
        <label class="flex items-start">
            <input type="checkbox" name="agree" class="custom-checkbox mr-3 mt-1" {{ old('agree') ? 'checked' : '' }} />
            <span class="text-sm text-gray-600">
                Saya bersedia menerima informasi promo, kelas baru, dan event komunitas melalui email.
            </span>
        </label>
    </div>

    <button type="submit"
        class="w-full bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary hover:scale-105 hover:shadow-lg transition-all text-sm font-semibold text-center mx-auto block">
        Sign Up Now
    </button>
</form>

</div>
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

  </body>
</html>