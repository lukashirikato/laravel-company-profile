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
    <script>tailwind.config = { theme: { extend: { colors: { primary: "#EE4E8B", secondary: "#7A2B4A", accent: "#1A7A5E", "light-pink": "#F4C9DF", cream: "#FCF9F2", dark: "#1C1C1C", "springs-ivy": "#1D5A4B", "grounded-green": "#C5D79B", "power-pink": "#EE4E8B", "burnt-cherry": "#7A2B4A", "soft-petals": "#F4C9DF", "patina-green": "#1A7A5E", "layl": "#1C1C1C", "rising": "#FCF9F2" }, fontFamily: { nord: ['Nord', 'Poppins', 'sans-serif'], instrument: ['"Instrument Serif"', 'Georgia', 'serif'], poppins: ['Poppins', 'sans-serif'] }, borderRadius: { button: "8px" } } } };</script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- FTM BRAND TYPOGRAPHY — Self-hosted Nord + Instrument     --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <link rel="preload" href="{{ asset('fonts/Nord-Black.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/Nord-Bold.woff2') }}"  as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/Nord-Book.woff2') }}"  as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    <style>
      /* ================================================== */
      /* FTM SOCIETY TYPOGRAPHY — per .kiro/steering/desain.md */
      /*                                                     */
      /*   Body / paragraf  → Poppins 500 (readable & solid)*/
      /*   h1-h6 / heading  → Nord (tegas, architectural)   */
      /*   Button / nav     → Nord (UI labels)               */
      /*   Form inputs      → Poppins                        */
      /*   .font-instrument → utility untuk aksen feminin    */
      /*                      (kata "Society", "Productive") */
      /* ================================================== */

      /* Global text rendering — anti-pucat */
      html {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: 'Poppins', system-ui, sans-serif;
        font-size: 16px;
        line-height: 1.65;
        font-weight: 500;
        color: #1C1C1C;                /* Layl — kontras kuat */
        scroll-behavior: smooth;
        overflow-x: hidden;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }

      main {
        flex: 1;
      }

      footer {
        margin-top: auto;
      }

      .footer-link {
        display: inline-block;
        color: rgba(255, 255, 255, 0.82);
        transition: all 0.25s ease;
      }

      .footer-link:hover {
        color: #FFD6E7;
        transform: translateX(4px);
        text-shadow: 0 0 12px rgba(255, 214, 231, 0.4);
      }

      .footer-link:focus {
        outline: none;
        color: #FFF;
      }

      p {
        font-family: 'Poppins', system-ui, sans-serif;
        font-weight: 500;
        line-height: 1.7;
        color: #1C1C1C;
      }

      /* Optional softer paragraph helper kalau perlu */
      .p-soft { color: rgba(28, 28, 28, 0.78); }

      h1, h2, h3, h4, h5, h6 {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        line-height: 1.15;
        letter-spacing: -0.015em;
        color: #1C1C1C;
      }

      nav, button, a, span, label {
        letter-spacing: 0.2px;
      }

      nav a {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
      }

      button {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
      }

      input, select, textarea, option, summary {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        color: #1C1C1C;
      }

      strong, b {
        font-weight: 700;
      }

      /* Utility class overrides (sesuai tailwind.config inline) */
      .font-nord       { font-family: 'Nord', 'Poppins', sans-serif !important; }
      .font-instrument { font-family: 'Instrument Serif', Georgia, serif !important; font-weight: 400 !important; }
      .font-poppins    { font-family: 'Poppins', sans-serif !important; }

      /* Boost ketegasan untuk Instrument Serif italic
         (font ini memang tipis bawaan — kasih text-shadow halus
         untuk meniru weight yang lebih kokoh tanpa kehilangan elegan) */
      .font-instrument.italic {
        font-style: italic;
        text-shadow: 0 0 0.4px currentColor;
      }

      i[class^="ri-"],
      i[class*=" ri-"] {
        font-family: 'remixicon' !important;
      }
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
      
      .logo {
        font-family: 'Nord', sans-serif;
        font-weight: 900;
      }
      .hero-section {
      background-image: linear-gradient(135deg, rgba(122, 43, 74, 0.88) 0%, rgba(238, 78, 139, 0.82) 100%), url('./images/IMG_0278.jpg');
      background-size: cover;
      background-position: center;
      }
      .testimonial-section {
      background-image: linear-gradient(rgba(232, 196, 216, 0.15), rgba(200, 217, 160, 0.1)), url('https://readdy.ai/api/search-image?query=subtle%2520elegant%2520pattern%2520background%252C%2520very%2520light%2520and%2520minimal%252C%2520soft%2520pink%2520and%2520beige%2520tones%252C%2520delicate%2520Islamic%2520geometric%2520patterns%252C%2520barely%2520visible%252C%2520extremely%2520subtle%2520texture%252C%2520professional%2520photography&width=1920&height=600&seq=2&orientation=landscape');
      background-size: cover;
      background-position: center;
      }
      .nav-link {
      position: relative;
      text-decoration: none;
      transition: color 0.25s ease;
      font-weight: 500;
      }
      /* Active underline — starts hidden, shows with animation */
      .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 3px;
      bottom: -6px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #EE4E8B;
      border-radius: 999px;
      transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .nav-link.active::after {
      width: calc(100% + 8px);
      }
      .nav-link.active {
      color: #EE4E8B;
      font-weight: 700;
      }
      /* Hover effect — slide from center to edges */
      .nav-link:hover::after {
      width: calc(100% + 8px);
      }
      .nav-link:hover {
      color: #EE4E8B;
      }
      /* Mobile nav-link styles */
      .nav-link-mobile {
      position: relative;
      text-decoration: none;
      transition: color 0.25s ease;
      display: inline-block;
      }
      .nav-link-mobile::after {
      content: '';
      position: absolute;
      width: 0;
      height: 3px;
      bottom: -4px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #EE4E8B;
      border-radius: 999px;
      transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .nav-link-mobile.active::after {
      width: calc(100% + 8px);
      }
      .nav-link-mobile.active {
      color: #EE4E8B;
      font-weight: 700;
      }
      .nav-link-mobile:hover {
      color: #EE4E8B;
      }
      .nav-link-mobile:hover::after {
      width: calc(100% + 8px);
      }
      .class-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px -5px rgba(238, 78, 139, 0.2);
      }
      .feature-card:hover {
      transform: translateY(-5px);
      }
      input:focus, textarea:focus {
      outline: none;
      border-color: primary;
      }
      .custom-checkbox {
      appearance: none;
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid primary;
      border-radius: 4px;
      outline: none;
      cursor: pointer;
      position: relative;
      }
      .custom-checkbox:checked {
      background-color: primary;
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

/* ============================================ */
/* FTM SOCIETY BRAND ORNAMENTS & SIGNATURE      */
/* Ciri khas visual - Flower, Asterisk, C-mark  */
/* ============================================ */

/* Brand Flower Ornament (4-petal) - inline SVG as background */
.brand-flower {
  display: inline-block;
  width: 40px;
  height: 40px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%231C1C1E' d='M50 8c-13 0-20 10-20 20 0 6 3 11 7 14-6 3-10 8-10 15 0 10 9 18 23 18s23-8 23-18c0-7-4-12-10-15 4-3 7-8 7-14 0-10-7-20-20-20z'/%3E%3C/svg%3E");
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  opacity: 0.85;
}
.brand-flower-pink  { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23E8618C' d='M50 8c-13 0-20 10-20 20 0 6 3 11 7 14-6 3-10 8-10 15 0 10 9 18 23 18s23-8 23-18c0-7-4-12-10-15 4-3 7-8 7-14 0-10-7-20-20-20z'/%3E%3C/svg%3E"); }
.brand-flower-cherry{ background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%236B2D4E' d='M50 8c-13 0-20 10-20 20 0 6 3 11 7 14-6 3-10 8-10 15 0 10 9 18 23 18s23-8 23-18c0-7-4-12-10-15 4-3 7-8 7-14 0-10-7-20-20-20z'/%3E%3C/svg%3E"); }
.brand-flower-ivory { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23F5EFE6' d='M50 8c-13 0-20 10-20 20 0 6 3 11 7 14-6 3-10 8-10 15 0 10 9 18 23 18s23-8 23-18c0-7-4-12-10-15 4-3 7-8 7-14 0-10-7-20-20-20z'/%3E%3C/svg%3E"); }

/* Brand Asterisk/Star Ornament (8-point) */
.brand-asterisk {
  display: inline-block;
  width: 28px;
  height: 28px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23E8618C' d='M45 5h10v90h-10zM5 45h90v10H5zM14.64 21.71l7.07-7.07 63.64 63.64-7.07 7.07zM85.36 14.64l7.07 7.07-63.64 63.64-7.07-7.07z'/%3E%3C/svg%3E");
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
}
.brand-asterisk-ivory { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%23F5EFE6' d='M45 5h10v90h-10zM5 45h90v10H5zM14.64 21.71l7.07-7.07 63.64 63.64-7.07 7.07zM85.36 14.64l7.07 7.07-63.64 63.64-7.07-7.07z'/%3E%3C/svg%3E"); }

/* C-mark (half-moon/lotus curve) ornament */
.brand-cmark {
  display: inline-block;
  width: 32px;
  height: 32px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Cpath fill='%236B2D4E' d='M10 50C10 28 28 10 50 10v18c-12 0-22 10-22 22s10 22 22 22v18C28 90 10 72 10 50zm80 0C90 28 72 10 50 10v18c12 0 22 10 22 22s-10 22-22 22v18c22 0 40-18 40-40z'/%3E%3C/svg%3E");
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
}

/* Three-dots ornament (Rising/trio) */
.brand-trio {
  display: inline-block;
  width: 32px;
  height: 32px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='28' r='18' fill='%23E8618C'/%3E%3Ccircle cx='26' cy='72' r='18' fill='%236B2D4E'/%3E%3Ccircle cx='74' cy='72' r='18' fill='%231A7A6E'/%3E%3C/svg%3E");
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
}

/* Brand gradient utility classes */
.bg-brand-pink-gradient   { background: linear-gradient(135deg, #EE4E8B 0%, #7A2B4A 100%); }
.bg-brand-green-gradient  { background: linear-gradient(135deg, #1A7A5E 0%, #1D5A4B 100%); }
.bg-brand-soft-gradient   { background: linear-gradient(135deg, #FCF9F2 0%, #F4C9DF 100%); }
.bg-brand-nature-gradient { background: linear-gradient(135deg, #C5D79B 0%, #1A7A5E 100%); }

.text-brand-gradient {
  background: linear-gradient(135deg, #EE4E8B 0%, #7A2B4A 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  color: transparent;
}

/* Signature decorative divider with flower + asterisk */
.brand-divider {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 12px;
}
.brand-divider::before,
.brand-divider::after {
  content: "";
  height: 1px;
  width: 80px;
  background: linear-gradient(90deg, transparent, #EE4E8B, transparent);
}

/* Keyframe: gentle float for ornaments */
@keyframes brandFloat {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50%      { transform: translateY(-10px) rotate(8deg); }
}
.brand-float {
  animation: brandFloat 5s ease-in-out infinite;
}

/* ============================================ */
/* FTM SOCIETY — 4-Logo Motion Showcase         */
/* JavaScript-driven crossfade slider            */
/* ============================================ */

.ftm-logo-stage {
  position: relative;
  aspect-ratio: 1 / 1;
  width: 100%;
  background: #FCF9F2;
  isolation: isolate;
}

/* Base — semua slide stack, hidden by default */
.ftm-logo-slide,
.ftm-logo-bg {
  position: absolute;
  inset: 0;
  opacity: 0;
  pointer-events: none;
  transition: opacity 1.2s ease-in-out, transform 6s ease-in-out;
}

/* Active state: visible + ken-burns subtle scale */
.ftm-logo-slide.is-active,
.ftm-logo-bg.is-active {
  opacity: 1;
  transform: scale(1.04);
}

/* ──── BACKDROP per slide ──── */
.ftm-logo-bg { z-index: 0; }
.ftm-logo-bg-1 {
  background: radial-gradient(circle at 30% 30%, #F4C9DF 0%, #FCF9F2 70%);
}
.ftm-logo-bg-2 {
  background: radial-gradient(circle at 70% 30%, #F4C9DF 0%, #FCF9F2 60%, #F4C9DF 100%);
}
.ftm-logo-bg-3 {
  background: radial-gradient(circle at 30% 70%, #C5D79B 0%, #FCF9F2 70%);
}
.ftm-logo-bg-4 {
  background: radial-gradient(circle at 70% 70%, #FCF9F2 0%, #F4C9DF 100%);
}

/* ──── Subtle dotted pattern overlay ──── */
.ftm-logo-pattern {
  position: absolute;
  inset: 0;
  z-index: 1;
  opacity: 0.18;
  pointer-events: none;
  background-image:
    radial-gradient(circle, #EE4E8B 1px, transparent 1px),
    radial-gradient(circle, #7A2B4A 1px, transparent 1px);
  background-size: 32px 32px, 32px 32px;
  background-position: 0 0, 16px 16px;
  mix-blend-mode: multiply;
}

/* ──── Logo image slides ──── */
.ftm-logo-slide {
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 12%;
  transform: scale(1.06);
}
.ftm-logo-slide.is-active {
  transform: scale(1) translateY(0);
}
.ftm-logo-slide img {
  max-width: 78%;
  max-height: 78%;
  width: auto;
  height: auto;
  object-fit: contain;
  filter: drop-shadow(0 18px 36px rgba(122, 43, 74, 0.18));
}

/* ──── Caption strip bawah ──── */
.ftm-logo-caption {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 5;
  padding: 1rem 1.25rem 1.1rem;
  background: linear-gradient(
    180deg,
    transparent 0%,
    rgba(28, 28, 28, 0.0) 30%,
    rgba(28, 28, 28, 0.55) 100%
  );
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.15rem;
  pointer-events: none;
}
.ftm-logo-caption-eyebrow {
  font-family: 'Nord', 'Poppins', sans-serif;
  font-weight: 800;
  font-size: 0.68rem;
  letter-spacing: 0.32em;
  text-transform: uppercase;
  color: #F4C9DF;
}
.ftm-logo-caption-tag {
  font-family: 'Instrument Serif', Georgia, serif;
  font-style: italic;
  font-size: 1.35rem;
  color: #FCF9F2;
  text-shadow: 0 2px 10px rgba(28, 28, 28, 0.35);
  line-height: 1.1;
}

/* ──── Indicator dots ──── */
.ftm-logo-dots {
  position: absolute;
  top: 1rem;
  right: 1rem;
  z-index: 5;
  display: flex;
  gap: 0.4rem;
}
.ftm-logo-dot {
  width: 6px;
  height: 6px;
  border-radius: 999px;
  background: rgba(122, 43, 74, 0.25);
  transition: background 0.4s ease, width 0.4s ease;
  display: block;
  cursor: pointer;
  border: none;
  padding: 0;
}
.ftm-logo-dot.is-active {
  background: #EE4E8B;
  width: 22px;
}

/* ──── Responsive tuning ──── */
@media (max-width: 640px) {
  .ftm-logo-slide { padding: 14%; }
  .ftm-logo-caption-tag { font-size: 1.1rem; }
  .ftm-logo-caption-eyebrow { font-size: 0.6rem; }
}
    </style>
  </head>
  <body class="bg-cream text-dark">
    
  <!-- Header -->
<header class="fixed w-full bg-cream bg-opacity-95 shadow-sm z-50">
    <div class="container mx-auto px-4 py-3 grid grid-cols-3 items-center">

        <!-- LOGO (kiri) -->
        <a href="{{ route('member.dashboard') }}" class="logo text-2xl hover:opacity-80 transition tracking-tight flex items-baseline gap-1.5 justify-self-start">
            <span class="font-nord font-black text-[#EE4E8B]">FTM</span>
            <span class="font-instrument italic text-[#7A2B4A] text-3xl">Society</span>
        </a>

        <!-- DESKTOP NAVIGATION (tengah) -->
        <nav class="hidden md:flex items-center justify-center space-x-8" id="desktop-nav">
            <a href="#home" class="nav-link text-dark hover:text-primary transition" data-nav="home">Home</a>
            <a href="#about" class="nav-link text-dark hover:text-primary transition" data-nav="about">About</a>
            <a href="#our-programs" class="nav-link text-dark hover:text-primary transition" data-nav="programs">Programs</a>
            <a href="#pricing" class="nav-link text-dark hover:text-primary transition" data-nav="package">Package</a>
            <a href="#Facility" class="nav-link text-dark hover:text-primary transition" data-nav="gallery">Gallery</a>
            <a href="#contact" class="nav-link text-dark hover:text-primary transition" data-nav="contact">Contact</a>
        </nav>

        <!-- LOGIN + HAMBURGER (kanan) -->
        <div class="flex items-center justify-end gap-3 col-start-3 justify-self-end">
            <a href="{{ route('member.login') }}"
               class="hidden md:inline-flex bg-primary text-white px-6 py-2 rounded-button hover:bg-secondary hover:scale-105 transition font-semibold">
                Login
            </a>
            <button id="mobile-menu-button"
                    type="button"
                    aria-label="Buka menu"
                    aria-expanded="false"
                    class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-full bg-white/80 backdrop-blur border border-[#F4C9DF] text-[#7A2B4A] hover:bg-[#F4C9DF] hover:border-[#EE4E8B] hover:text-[#EE4E8B] active:scale-95 transition shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="7"  x2="20" y2="7"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="17" x2="20" y2="17"></line>
                </svg>
            </button>
        </div>

    </div>
</header>

<!-- MOBILE OVERLAY -->
<div id="mobile-overlay" class="fixed inset-0 bg-dark/50 hidden z-[9999]"></div>

<!-- MOBILE MENU SLIDE -->
<div id="mobile-menu"
    class="fixed top-0 right-[-100%] h-full w-64 bg-cream shadow-lg z-[10000] p-6 transition-all duration-300">

    <div class="flex justify-end mb-8">
        <button id="close-menu-button" class="w-10 h-10 flex items-center justify-center text-primary">
            <i class="ri-close-line ri-lg"></i>
        </button>
    </div>

    <nav class="flex flex-col space-y-6">
        <a href="#home" class="nav-link-mobile text-dark hover:text-primary transition" data-nav="home">Home</a>
        <a href="#about" class="nav-link-mobile text-dark hover:text-primary transition" data-nav="about">About</a>
        <a href="#our-programs" class="nav-link-mobile text-dark hover:text-primary transition" data-nav="programs">Programs</a>
        <a href="#pricing" class="nav-link-mobile text-dark hover:text-primary transition" data-nav="package">Package</a>
        <a href="#Facility" class="nav-link-mobile text-dark hover:text-primary transition" data-nav="gallery">Gallery</a>
        <a href="#contact" class="nav-link-mobile text-dark hover:text-primary transition" data-nav="contact">Contact</a>



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

<main>

  <!-- ========================================= -->
<!-- HERO SECTION - REFACTORED & PROFESSIONAL -->
<!-- ========================================= -->

<section id="home" class="relative min-h-screen flex items-center overflow-hidden">
    
    <!-- Background Image Layer with Premium Effects -->
    <div class="absolute inset-0 z-0">
        <div id="hero-bg" 
             class="absolute inset-0 bg-cover bg-center transition-all duration-700 scale-105 backdrop-blur-xs"
             style="background-image: url('/images/bg.png');"></div>
        
        <!-- Luxury Gradient Overlay - Rose/Burgundy Blend -->
        <div class="absolute inset-0 bg-gradient-to-br from-secondary/85 via-primary/70 to-dark/80"></div>
        
        <!-- Premium Dark Accent Layer for Text Contrast -->
        <div class="absolute inset-0 bg-gradient-to-t from-dark/40 via-transparent to-transparent"></div>
    </div>

    <!-- Decorative Background Elements - Soft Luxury Ambiance -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -right-48 w-96 h-96 bg-light-pink/15 rounded-full blur-3xl opacity-60 animate-pulse" style="animation-duration: 6s;"></div>
        <div class="absolute bottom-1/4 -left-48 w-96 h-96 bg-cream/10 rounded-full blur-3xl opacity-50 animate-pulse" style="animation-duration: 7s; animation-delay: 1.5s;"></div>

        <!-- Brand Ornament Signatures -->
        <div class="brand-flower brand-flower-ivory brand-float absolute top-24 right-[12%] opacity-20" style="width: 70px; height: 70px;"></div>
        <div class="brand-asterisk brand-asterisk-ivory brand-float absolute bottom-32 right-[8%] opacity-25" style="width: 42px; height: 42px; animation-delay: 1s;"></div>
        <div class="brand-cmark brand-float absolute top-1/3 left-[6%] opacity-15" style="width: 60px; height: 60px; filter: invert(96%) sepia(10%) saturate(200%) hue-rotate(330deg); animation-delay: 2s;"></div>
    </div>

    <!-- Main Content Container -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center min-h-[calc(100vh-80px)]">
            
            <!-- LEFT COLUMN - Text Content (Premium Updated) -->
            <div class="lg:col-span-7 text-center lg:text-left space-y-6 md:space-y-8 animate-fade-in">
                
                <!-- Premium Glass Effect Badge -->
                <div data-aos="fade-right" 
                     class="inline-flex items-center gap-3 px-6 py-3 rounded-full backdrop-blur-xl bg-white/[0.08] border-2 border-white/30 shadow-2xl hover:bg-white/[0.12] hover:border-white/50 transition-all duration-300 group">
                    <span class="brand-flower brand-flower-ivory" style="width: 18px; height: 18px;"></span>
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-light-pink opacity-80"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-light-pink shadow-lg"></span>
                    </span>
                    <span class="text-white text-sm md:text-base font-semibold tracking-widest uppercase opacity-95">
                        Muslimah-Only Fitness
                    </span>
                </div>

                <!-- Main Heading - Premium Typography with Gradient & Shadow -->
                <div data-aos="fade-right" data-aos-delay="100" class="space-y-6">
                    <h1 class="font-nord text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tighter drop-shadow-2xl" style="text-shadow: 0 20px 40px rgba(0,0,0,0.3);">
                        YOUR<br/>
                        <span class="relative inline-block my-3">
                            <span class="font-instrument italic font-normal text-transparent bg-clip-text bg-gradient-to-r from-light-pink via-primary to-light-pink bg-[length:200%_auto] animate-gradient-shift drop-shadow-2xl" style="text-shadow: 0 10px 30px rgba(238, 78, 139,0.4); filter: drop-shadow(0 20px 40px rgba(122, 43, 74,0.3)); letter-spacing: -0.02em;">
                                Productive
                            </span>
                            <!-- Elegant Gradient Underline -->
                            <div class="absolute -bottom-4 left-0 right-0 h-1.5 bg-gradient-to-r from-transparent via-light-pink to-transparent rounded-full opacity-80 blur-sm"></div>
                            <div class="absolute -bottom-3 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-primary to-transparent rounded-full"></div>
                        </span><br/>
                        <span class="font-nord bg-gradient-to-r from-white to-cream bg-clip-text text-transparent">SISTER</span>
                    </h1>
                </div>

                <!-- Subtitle - Refined Spacing & Hierarchy -->
                <div data-aos="fade-right" data-aos-delay="200" class="space-y-3 pt-4">
                    <p class="font-poppins text-xl sm:text-2xl md:text-3xl text-white font-medium leading-relaxed tracking-wide">
                        Good Habit inside
                    </p>
                    <p class="font-instrument italic text-3xl sm:text-4xl md:text-5xl bg-gradient-to-r from-light-pink to-primary bg-clip-text text-transparent drop-shadow-lg" style="text-shadow: 0 8px 20px rgba(238, 78, 139,0.3);">
                        Productive Muslimah
                    </p>
                </div>

                <!-- CTA Buttons - Premium Styling & Effects -->
                <div data-aos="fade-right" data-aos-delay="300" 
                     class="flex flex-wrap gap-4 justify-center lg:justify-start pt-6">
                    @auth('customer')
                    @else
                        <a href="{{ route('join') }}" 
                           class="group relative inline-flex items-center justify-center gap-3 px-8 py-4 text-lg font-black text-white bg-gradient-to-r from-primary to-secondary rounded-full overflow-hidden shadow-2xl hover:shadow-3xl transition-all duration-300 ease-out hover:scale-105 hover:-translate-y-1">
                            <span class="relative z-10 tracking-wide">Join Now</span>
                            <i class="ri-arrow-right-line text-xl relative z-10 transition-all group-hover:translate-x-2 group-hover:scale-110"></i>
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary opacity-0 group-hover:opacity-100 transition-all duration-300"></div>
                            <div class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-20 bg-white group-hover:scale-150 transition-all duration-500"></div>
                        </a>
                    @endauth
                    
                    <a href="#about" 
                       class="group inline-flex items-center justify-center gap-3 px-8 py-4 text-lg font-bold text-white bg-white/10 backdrop-blur-sm border-2 border-white/60 rounded-full hover:bg-cream hover:text-primary hover:border-cream transition-all duration-300 ease-out hover:scale-105 hover:-translate-y-1">
                        <span>Learn More</span>
                        <i class="ri-arrow-down-line text-xl transition-all group-hover:translate-y-2"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Scroll Indicator - Perfectly Centered (Auto Hide on Scroll) -->
    <div id="scroll-indicator" class="fixed bottom-10 left-0 right-0 z-20 hidden md:flex justify-center pointer-events-none opacity-100 transition-opacity duration-300">
        <a href="#about" class="flex flex-col items-center justify-center gap-5 pointer-events-auto cursor-pointer">
            <!-- Text Indicator -->
            <span class="text-xs font-black uppercase tracking-[0.15em] text-white/70 hover:text-white/100 transition-all duration-300 whitespace-nowrap">Scroll Down</span>
            
            <!-- Animated Container -->
            <div class="flex flex-col items-center justify-center" style="animation: bounce-smooth 2.2s cubic-bezier(0.4, 0, 0.6, 1) infinite;">
                <!-- Outer Border Circle -->
                <div class="w-6 h-9 border-2 border-white/50 rounded-full flex flex-col items-center justify-start pt-2 hover:border-white/90 transition-all duration-300">
                    <!-- Inner Animated Dot -->
                    <div class="w-1 h-1.5 bg-white/70 rounded-full" style="animation: scroll-dot-bounce 1.5s ease-in-out infinite;"></div>
                </div>
            </div>
        </a>
    </div>

</section>

<!-- ========================================= -->
<!-- TENTANG KAMI SECTION - Reference Image Design -->
<!-- ========================================= -->

<!-- Brand fonts (Nord + Instrument Serif) already loaded via ftm-typography.css -->

<style>
    .tentang-kami-section {
        background: linear-gradient(100deg, #fffcfd 20%, #fff2f6 60%, #ffe3ec 100%);
        position: relative;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
    }

    /* Soft pink glow orbs */
    .tentang-kami-section .glow-orb-1 {
        position: absolute;
        top: -80px;
        left: -120px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,107,154,0.10) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .tentang-kami-section .glow-orb-2 {
        position: absolute;
        bottom: -100px;
        right: -100px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255,107,154,0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .tentang-kami-section .glow-orb-3 {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(255,182,206,0.06) 0%, transparent 60%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Decorative curved lines (subtle) */
    .tentang-kami-section .deco-lines {
        position: absolute;
        top: 0;
        right: 0;
        width: 45%;
        height: 100%;
        pointer-events: none;
        opacity: 0.06;
    }
    .tentang-kami-section .deco-lines svg {
        width: 100%;
        height: 100%;
    }

    /* Floating sparkle icons */
    .tentang-kami-section .float-sparkle {
        position: absolute;
        color: #ff6b9a;
        opacity: 0.25;
        animation: floatSparkle 6s ease-in-out infinite;
    }
    .tentang-kami-section .float-sparkle-1 {
        top: 15%;
        right: 42%;
        font-size: 18px;
        animation-delay: 0s;
    }
    .tentang-kami-section .float-sparkle-2 {
        bottom: 20%;
        right: 8%;
        font-size: 14px;
        animation-delay: 2s;
    }
    .tentang-kami-section .float-heart {
        position: absolute;
        bottom: 22%;
        right: 5%;
        width: 36px;
        height: 36px;
        background: #ff6b9a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        opacity: 0.7;
        animation: floatSparkle 5s ease-in-out infinite;
        animation-delay: 1s;
    }

    @keyframes floatSparkle {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-12px) rotate(5deg); }
    }

    /* Card styles */
    .tentang-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: 0 2px 16px rgba(255,107,154,0.08), 0 1px 4px rgba(0,0,0,0.04);
        border: 1px solid rgba(255,107,154,0.12);
        display: flex;
        align-items: flex-start;
        gap: 16px;
        transition: all 0.3s ease;
    }
    .tentang-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(255,107,154,0.15), 0 2px 8px rgba(0,0,0,0.06);
        border-color: rgba(255,107,154,0.25);
    }
    .tentang-card-icon {
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #fff0f5 0%, #ffe0ec 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff6b9a;
        font-size: 22px;
        border: 1px solid rgba(255,107,154,0.15);
    }
    .tentang-card-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 15px;
        color: #222;
        margin-bottom: 2px;
    }
    .tentang-card-desc {
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
        font-size: 13px;
        color: #777;
        line-height: 1.5;
    }

    /* Image glow effect */
    .tentang-image-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        width: 100%;
    }
    .tentang-image-wrapper img {
        width: 100%;
        max-width: 650px;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 20px 60px rgba(255,107,154,0.25)) drop-shadow(0 8px 24px rgba(255,107,154,0.15));
        transition: transform 0.5s ease;
    }
    .tentang-image-wrapper:hover img {
        transform: scale(1.02);
    }

    @media (min-width: 1024px) {
        .tentang-image-wrapper {
            justify-content: flex-end;
            margin-right: -40px;
            margin-bottom: -100px;
            margin-top: 30px;
        }
        .tentang-image-wrapper img {
            max-width: 800px;
            width: 110%;
        }
    }

    @media (min-width: 1280px) {
        .tentang-image-wrapper {
            margin-right: -80px;
            margin-bottom: -110px;
            margin-top: 40px;
        }
        .tentang-image-wrapper img {
            max-width: 950px;
            width: 115%;
        }
    }
</style>

<section id="about" class="tentang-kami-section relative" style="padding: 100px 0 110px;" data-aos="fade-up">
    
    <!-- Glow orbs -->
    <div class="glow-orb-1"></div>
    <div class="glow-orb-2"></div>
    <div class="glow-orb-3"></div>

    <!-- Decorative curved lines (right side) -->
    <div class="deco-lines">
        <svg viewBox="0 0 600 800" fill="none" xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="300" cy="400" rx="280" ry="350" stroke="#ff6b9a" stroke-width="1"/>
            <ellipse cx="300" cy="400" rx="220" ry="280" stroke="#ff6b9a" stroke-width="0.8"/>
            <ellipse cx="300" cy="400" rx="160" ry="210" stroke="#ff6b9a" stroke-width="0.5"/>
        </svg>
    </div>

    <!-- Floating decorations -->
    <div class="float-sparkle float-sparkle-1">✦</div>
    <div class="float-sparkle float-sparkle-2">✦</div>
    <div class="float-heart">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
    </div>

    <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 relative" style="z-index: 10;">
        <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-12 xl:gap-16">

            <!-- LEFT COLUMN: Text Content -->
            <div class="w-full lg:w-[42%] xl:w-[40%] text-center lg:text-left order-1">

                <!-- Eyebrow label -->
                <p style="
                    font-family: 'Poppins', sans-serif;
                    font-size: 13px;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.25em;
                    color: #ff6b9a;
                    margin-bottom: 24px;
                ">TENTANG KAMI</p>

                <!-- Main heading -->
                <h2 style="
                    font-family: 'Nord', 'Poppins', sans-serif;
                    font-weight: 800;
                    font-size: clamp(42px, 5.5vw, 72px);
                    line-height: 1.05;
                    color: #222;
                    margin-bottom: 28px;
                ">
                    Memberdayakan<br>
                    <span style="
                        color: #ff6b9a;
                        font-style: italic;
                        font-weight: 700;
                    ">Muslimah.</span>
                </h2>

                <!-- Description paragraph -->
                <p style="
                    font-family: 'Poppins', sans-serif;
                    font-size: 15px;
                    font-weight: 400;
                    line-height: 1.8;
                    color: #555;
                    max-width: 500px;
                    margin-bottom: 36px;
                " class="mx-auto lg:mx-0">
                    FTM Society adalah ruang bagi muslimah untuk hidup
                    <span style="color: #ff6b9a; font-weight: 600;">aktif</span>,
                    <span style="color: #ff6b9a; font-weight: 600;">produktif</span>,
                    dan sesuai <span style="color: #ff6b9a; font-weight: 600;">syariat</span>.
                    Kami hadir untuk menemani perjalananmu menjadi versi terbaik diri, dunia, dan akhirat.
                </p>

                <!-- Feature Cards -->
                <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-4 justify-center lg:justify-start">
                    
                    <!-- Card 1: Aktif & Produktif -->
                    <div class="tentang-card" style="flex: 1; min-width: 200px; max-width: 280px;">
                        <div class="tentang-card-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2l2.09 6.26L20.18 9l-5.09 4.09L16.18 20 12 16.54 7.82 20l1.09-6.91L3.82 9l6.09-.74z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="tentang-card-title">Aktif & Produktif</div>
                            <div class="tentang-card-desc">Menginspirasi untuk terus bermanfaat setiap hari.</div>
                        </div>
                    </div>

                    <!-- Card 2: Sesuai Syariat -->
                    <div class="tentang-card" style="flex: 1; min-width: 200px; max-width: 280px;">
                        <div class="tentang-card-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="tentang-card-title">Sesuai Syariat</div>
                            <div class="tentang-card-desc">Berlandaskan nilai Islam dalam setiap langkah.</div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- RIGHT COLUMN: Image -->
            <div class="w-full lg:w-[58%] xl:w-[60%] order-2">
                <div class="tentang-image-wrapper">
                    <img src="{{ asset('images/logoftm2-removebg-preview.png') }}" alt="FTM Society Logo" loading="lazy">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ========================================= -->
<!-- VISI & MISI SECTION -->
<!-- ========================================= -->

<style>
    .visi-misi-section {
        background-color: #FFF8FA;
        position: relative;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        color: #2D2D2D;
    }

    /* Glow Orbs */
    .visi-misi-section .glow-orb-tl {
        position: absolute;
        top: -150px;
        left: -150px;
        width: 450px;
        height: 450px;
        background: radial-gradient(circle, rgba(242,93,148,0.12) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .visi-misi-section .glow-orb-br {
        position: absolute;
        bottom: -200px;
        right: -200px;
        width: 550px;
        height: 550px;
        background: radial-gradient(circle, rgba(242,93,148,0.10) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Stat icon box */
    .visi-misi-stat-icon {
        width: 52px;
        height: 52px;
        background-color: #FCE7EF;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #F25D94;
        margin-bottom: 16px;
        border: 1px solid rgba(242, 93, 148, 0.15);
    }

    /* Card styling */
    .visi-misi-card {
        background: white;
        border-radius: 30px;
        border: 1px solid rgba(242, 93, 148, 0.1);
        padding: 36px;
        box-shadow: 0 4px 20px rgba(242, 93, 148, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease, border-color 0.4s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }
    .visi-misi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(242, 93, 148, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03);
        border-color: rgba(242, 93, 148, 0.25);
    }

    .visi-misi-card-icon {
        width: 52px;
        height: 52px;
        background-color: #FCE7EF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #F25D94;
        margin-bottom: 24px;
        border: 1px solid rgba(242, 93, 148, 0.15);
    }

    .visi-misi-card-btn {
        width: 44px;
        height: 44px;
        background-color: #F9FAFB;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2D2D2D;
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-top: 24px;
        align-self: flex-start;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
    }
    .visi-misi-card:hover .visi-misi-card-btn {
        background-color: #F25D94;
        color: white;
        border-color: #F25D94;
        transform: scale(1.05);
    }

    /* Quote Card styling */
    .visi-misi-quote-card {
        background: white;
        border-radius: 30px;
        border: 1px solid rgba(242, 93, 148, 0.08);
        padding: 24px 36px;
        box-shadow: 0 4px 20px rgba(242, 93, 148, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        gap: 24px;
        position: relative;
        overflow: hidden;
        min-height: 140px;
        width: 100%;
    }
    
    .visi-misi-quote-card .quote-icon-left {
        color: #F25D94;
        opacity: 0.8;
        flex-shrink: 0;
    }

    .visi-misi-quote-card .quote-watermark-right {
        position: absolute;
        right: 24px;
        bottom: -20px;
        font-size: 150px;
        line-height: 1;
        font-family: 'Instrument Serif', Georgia, serif;
        color: #FCE7EF;
        opacity: 0.25;
        user-select: none;
        pointer-events: none;
        font-weight: 900;
    }
</style>

<section id="visi-misi" class="visi-misi-section relative" style="padding: 100px 0 110px;" data-aos="fade-up">
    <!-- Glow orbs -->
    <div class="glow-orb-tl"></div>
    <div class="glow-orb-br"></div>

    <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 relative z-10">
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-[60px] items-center">

            <!-- LEFT COLUMN: ~45% -->
            <div class="w-full lg:w-[45%] text-center lg:text-left">
                
                <!-- Badge -->
                <p style="
                    font-family: 'Poppins', sans-serif;
                    font-size: 13px;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 0.25em;
                    color: #F25D94;
                    margin-bottom: 20px;
                ">VISI & MISI</p>

                <!-- Heading -->
                <h2 style="
                    font-family: 'Nord', 'Poppins', sans-serif;
                    font-weight: 800;
                    font-size: clamp(42px, 5vw, 64px);
                    line-height: 1.1;
                    color: #2D2D2D;
                    margin-bottom: 24px;
                ">
                    Visi & Misi <span style="color: #F25D94; font-style: italic; font-weight: 700;">Kami</span>
                </h2>

                <!-- Description -->
                <p style="
                    font-family: 'Poppins', sans-serif;
                    font-size: 15px;
                    font-weight: 400;
                    line-height: 1.8;
                    color: #6B7280;
                    max-width: 500px;
                    margin-bottom: 48px;
                " class="mx-auto lg:mx-0">
                    Kami bergerak dengan visi yang jelas dan misi yang nyata untuk memberi dampak positif bagi muslimah.
                </p>

                <!-- Feature Cards Grid (Replaces Statistics Grid) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 text-left">
                    
                    <!-- Feature Card 1 -->
                    <div class="group relative bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgba(242,93,148,0.04)] border border-[#F4C9DF]/30 hover:border-[#7A2B4A]/30 transition-all duration-300 overflow-hidden cursor-pointer">
                        <!-- Gradient Background on Hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#FCE7EF]/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative flex items-center gap-4">
                            <!-- Icon Container -->
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-[#FCE7EF] text-[#EE4E8B] group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm border border-[#F25D94]/10">
                                    <i class="ri-shield-check-line text-2xl"></i>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1">
                                <h4 style="font-family: 'Poppins', sans-serif;" class="font-bold text-[#1C1C1C] text-sm uppercase tracking-wider mb-0.5 group-hover:text-[#EE4E8B] transition-colors duration-300">
                                    Muslimah Only
                                </h4>
                                <p style="font-family: 'Poppins', sans-serif;" class="text-xs text-[#6B7280] leading-normal font-medium">
                                    100% Private & Safe Environment
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Card 2 -->
                    <div class="group relative bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgba(242,93,148,0.04)] border border-[#F4C9DF]/30 hover:border-[#EE4E8B]/30 transition-all duration-300 overflow-hidden cursor-pointer">
                        <!-- Gradient Background on Hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#FCE7EF]/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative flex items-center gap-4">
                            <!-- Icon Container -->
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-[#FCE7EF] text-[#EE4E8B] group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm border border-[#F25D94]/10">
                                    <i class="ri-heart-pulse-line text-2xl"></i>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1">
                                <h4 style="font-family: 'Poppins', sans-serif;" class="font-bold text-[#1C1C1C] text-sm uppercase tracking-wider mb-0.5 group-hover:text-[#EE4E8B] transition-colors duration-300">
                                    Certified Trainers
                                </h4>
                                <p style="font-family: 'Poppins', sans-serif;" class="text-xs text-[#6B7280] leading-normal font-medium">
                                    Professional Muslimah Coaches
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature Card 3 -->
                    <div class="group relative bg-white rounded-2xl p-5 shadow-[0_4px_20px_rgba(242,93,148,0.04)] border border-[#F4C9DF]/30 hover:border-[#7A2B4A]/30 transition-all duration-300 overflow-hidden cursor-pointer sm:col-span-2">
                        <!-- Gradient Background on Hover -->
                        <div class="absolute inset-0 bg-gradient-to-br from-[#FCE7EF]/30 via-[#FCE7EF]/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative flex items-center gap-4">
                            <!-- Icon Container -->
                            <div class="relative flex-shrink-0">
                                <div class="w-12 h-12 flex items-center justify-center rounded-2xl bg-[#FCE7EF] text-[#EE4E8B] group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm border border-[#F25D94]/10">
                                    <div class="flex items-center justify-center gap-1">
                                        <i class="ri-volume-mute-line text-lg"></i>
                                        <i class="ri-camera-off-line text-lg"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1">
                                <h4 style="font-family: 'Poppins', sans-serif;" class="font-bold text-[#1C1C1C] text-sm uppercase tracking-wider mb-0.5 group-hover:text-[#EE4E8B] transition-colors duration-300">
                                    No Music & No Camera
                                </h4>
                                <p style="font-family: 'Poppins', sans-serif;" class="text-xs text-[#6B7280] leading-normal font-medium">
                                    Fully Islamic-Compliant Environment for Your Comfort
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- RIGHT COLUMN: ~55% -->
            <div class="w-full lg:w-[55%] flex flex-col gap-6">
                
                <!-- Cards Row: Visi & Misi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Card Visi -->
                    <div class="visi-misi-card">
                        <div class="visi-misi-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                        <h3 style="font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 700; color: #2D2D2D; margin-bottom: 12px;">Visi</h3>
                        <p style="font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500; line-height: 1.6; color: #4B5563;">
                            <span class="font-bold text-[#7A2B4A]">FTM Society</span> adalah memberikan ruang bagi para muslimah untuk memiliki gaya hidup <span class="font-semibold text-[#EE4E8B]">aktif</span> dan <span class="font-semibold text-[#7A2B4A]">produktif</span> yang sesuai dengan syariat Islam.
                        </p>
                        <a href="#" class="visi-misi-card-btn" aria-label="Detail Visi">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Card Misi -->
                    <div class="visi-misi-card">
                        <div class="visi-misi-card-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="6"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                        <h3 style="font-family: 'Poppins', sans-serif; font-size: 20px; font-weight: 700; color: #2D2D2D; margin-bottom: 12px;">Misi</h3>
                        <p style="font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 500; line-height: 1.6; color: #4B5563;">
                            Oleh karena itu, <span class="font-bold text-[#7A2B4A]">FTM Society</span> hadir menyelenggarakan kegiatan olahraga dan kegiatan aktif sosial lainnya, seperti <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#7A2B4A]/10 text-[#7A2B4A] font-semibold rounded-md text-xs"><i class="ri-presentation-line"></i>webinar</span> dan <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-[#EE4E8B]/10 text-[#EE4E8B] font-semibold rounded-md text-xs"><i class="ri-calendar-event-line"></i>event</span>.
                        </p>
                        <a href="#" class="visi-misi-card-btn" aria-label="Detail Misi">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================= -->
<!-- WHY CHOOSE US SECTION -->
<!-- ========================================= -->
<section id="why-choose-us" class="relative bg-white overflow-hidden" style="padding: 100px 0 110px;" data-aos="fade-up">
    
    <!-- Background Soft Blur Orbs (Right Side) -->
    <div class="absolute -right-48 top-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-[radial-gradient(circle,_rgba(236,91,148,0.06)_0%,_transparent_70%)] pointer-events-none filter blur-2xl"></div>
    <div class="absolute -right-24 top-[60%] -translate-y-1/2 w-[400px] h-[400px] rounded-full bg-[radial-gradient(circle,_rgba(236,91,148,0.04)_0%,_transparent_70%)] pointer-events-none filter blur-xl"></div>

    <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 relative z-10 text-left">
        
        <!-- Eyebrow label -->
        <span class="block text-xs md:text-sm font-semibold tracking-[0.25em] text-[#ec5b94] uppercase mb-4">
            MENGAPA MEMILIH KAMI
        </span>

        <!-- Main Heading -->
        <h2 class="font-instrument text-5xl md:text-[72px] leading-[1.05] text-[#1C1C1C] font-normal">
            Komitmen Nyata untuk<br>
            <span class="text-[#ec5b94] italic font-instrument">Perubahan Bermakna.</span>
        </h2>

        <!-- Decorative Line and Dot -->
        <div class="flex items-center gap-2 mt-6 mb-8">
            <span class="w-12 h-[3px] bg-[#ec5b94] rounded-full"></span>
            <span class="w-2.5 h-2.5 bg-[#ec5b94] rounded-full"></span>
        </div>

        <!-- Statistics Grid (4 columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
            <!-- Item 1 -->
            <div class="flex flex-col text-left">
                <div class="text-[#ec5b94] mb-4">
                    <!-- Lucide Shield Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6Z"/></svg>
                </div>
                <h4 class="text-[#1C1C1C] font-bold text-xl mb-3 leading-snug sm:min-h-[56px] flex items-start">
                    Muslimah-Only Space
                </h4>
                <p class="text-gray-500 text-sm leading-relaxed sm:min-h-[96px] lg:min-h-[80px] flex items-start">
                    Fasilitas kami hanya untuk wanita, dengan staf wanita saja. Nikmati privasi lengkap tanpa jendela yang menghadap area publik dan sistem masuk yang aman.
                </p>
            </div>

            <!-- Item 2 -->
            <div class="flex flex-col text-left">
                <div class="text-[#ec5b94] mb-4">
                    <!-- Lucide Award Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-award"><circle cx="12" cy="8" r="7"/><path d="M8.21 13.89 7 23l5-3 5 3-1.21-9.12"/></svg>
                </div>
                <h4 class="text-[#1C1C1C] font-bold text-xl mb-3 leading-snug sm:min-h-[56px] flex items-start">
                    Certified Muslimah Trainer
                </h4>
                <p class="text-gray-500 text-sm leading-relaxed sm:min-h-[96px] lg:min-h-[80px] flex items-start">
                    Dibimbing langsung oleh coach tersertifikasi dengan pengalaman profesional dan pemahaman mendalam tentang kebutuhan muslimah.
                </p>
            </div>

            <!-- Item 3 -->
            <div class="flex flex-col text-left">
                <div class="text-[#ec5b94] mb-4">
                    <!-- Lucide Eye Off Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                </div>
                <h4 class="text-[#1C1C1C] font-bold text-xl mb-3 leading-snug sm:min-h-[56px] flex items-start">
                    Privacy is Our Priority
                </h4>
                <p class="text-gray-500 text-sm leading-relaxed sm:min-h-[96px] lg:min-h-[80px] flex items-start">
                    Ruang latihan khusus muslimah, tanpa kamera dan tanpa musik. Kami mengutamakan kenyamanan, keamanan, dan privasimu saat berolahraga.
                </p>
            </div>

            <!-- Item 4 -->
            <div class="flex flex-col text-left">
                <div class="text-[#ec5b94] mb-4">
                    <!-- Lucide Heart Icon Outline -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                </div>
                <h4 class="text-[#1C1C1C] font-bold text-xl mb-3 leading-snug sm:min-h-[56px] flex items-start">
                    Muslimah Friendly
                </h4>
                <p class="text-gray-500 text-sm leading-relaxed sm:min-h-[96px] lg:min-h-[80px] flex items-start">
                    Dirancang khusus untuk muslimah: area khusus wanita, pelatih perempuan bersertifikat, dan suasana nyaman sesuai nilai-nilai islami.
                </p>
            </div>
        </div>

        <!-- CTA Banner Bottom -->
        <div class="w-full bg-gradient-to-r from-[#6d1b45] via-[#b12768] to-[#ff5f98] rounded-3xl md:rounded-[32px] p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 md:gap-12 relative overflow-hidden shadow-[0_15px_45px_rgba(177,39,104,0.15)]">
            
            <!-- White circle decoration in CTA -->
            <div class="absolute -right-16 -bottom-16 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute left-1/3 -top-12 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>

            <!-- Left Info (Icon + Text) -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left max-w-3xl">
                <!-- White Circle with Pink Star Sparkles -->
                <div class="flex items-center justify-center w-16 h-16 bg-white rounded-full text-[#b12768] shrink-0 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-sparkles"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275Z"/><path d="m5 3 1 2.5L8.5 6 6 7 5 9.5 4 7 1.5 6 4 5Z"/><path d="m19 17 1 2.5 2.5.5-2.5 1-1 2.5-1-2.5-2.5-1 2.5-1Z"/></svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-white font-bold text-2xl md:text-3xl tracking-tight leading-snug">
                        Yuk, Jadi Bagian dari Perubahan!
                    </h3>
                    <p class="text-white/90 text-sm md:text-base font-medium leading-relaxed max-w-2xl">
                        Bersama kita bisa memberi manfaat lebih luas dan menciptakan generasi muslimah yang berdaya dan berakhlak mulia.
                    </p>
                </div>
            </div>

            <!-- Right Button -->
            <div class="shrink-0 w-full sm:w-auto flex justify-center md:justify-end">
                <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20untuk%20bergabung%20sebagai%20member." target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 bg-white/15 hover:bg-white/25 active:scale-95 border border-white/20 text-white font-bold rounded-full transition-all duration-300 backdrop-blur-sm group shadow-lg">
                    <span>Gabung Sekarang</span>
                    <div class="flex items-center justify-center w-8 h-8 bg-white rounded-full text-[#b12768] group-hover:translate-x-1 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </div>
                </a>
            </div>

        </div>

    </div>
</section>

<!-- ========================================= -->
<!-- FEATURED PROGRAMS SECTION (OUR PROGRAM)   -->
<!-- ========================================= -->
<section id="our-programs" class="bg-white font-poppins relative overflow-hidden" style="padding: 100px 0 110px;" data-aos="fade-up">
    <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 relative z-10">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-12">
            <!-- Left Side -->
            <div>
                <p class="text-[12px] uppercase tracking-[0.25em] text-[#FF4F8B] font-bold mb-3">
                    PROGRAM UNGGULAN
                </p>
                <h2 class="text-[32px] sm:text-[40px] md:text-[48px] font-bold text-[#222222] leading-tight font-nord">
                    Pilihan Program <span class="text-[#FF4F8B] italic font-semibold">Unggulan</span>
                </h2>
            </div>
            <!-- Right Side -->
            <div>
                <a href="#classes" class="group inline-flex items-center gap-2 text-[#FF4F8B] font-semibold transition-all duration-300 hover:opacity-90">
                    <span>Lihat Semua Program</span>
                    <i class="ri-arrow-right-line transition-transform duration-300 group-hover:translate-x-1.5 text-[18px]"></i>
                </a>
            </div>
        </div>

        <!-- Slider Container -->
        <div class="overflow-hidden relative w-full px-2 py-4" id="programs-slider-container">
            <div class="flex transition-transform duration-700 ease-in-out gap-8" id="programs-slider-track" style="will-change: transform;">
                
                <!-- Card 1: Private Group Class -->
                <div class="w-full md:w-[calc((100%-32px)/2)] lg:w-[calc((100%-64px)/3)] flex-shrink-0 flex flex-col bg-white rounded-[28px] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.02)] overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:border-[#FF4F8B]/30 hover:shadow-[0_15px_45px_rgba(255,79,139,0.06)] relative group">
                    <!-- Image Area -->
                    <div class="relative h-[240px] overflow-hidden">
                        <img src="{{ asset('images/foto1.png') }}" alt="Private Group Class" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" draggable="false" />
                        <!-- Badge -->
                        <div class="absolute top-[16px] left-[16px] bg-gradient-to-r from-[#FF4F8B] to-[#ff7da6] text-white text-[12px] font-bold px-[14px] py-[6px] rounded-full shadow-md z-10">
                            Unggulan
                        </div>
                    </div>

                    <!-- Floating Icon Box -->
                    <div class="absolute top-[212px] left-[28px] w-[56px] h-[56px] bg-white rounded-[18px] shadow-[0_8px_20px_rgba(0,0,0,0.08)] flex items-center justify-center z-20 transition-transform duration-300 group-hover:scale-110">
                        <i class="ri-team-line text-[#FF4F8B] text-[24px]"></i>
                    </div>

                    <!-- Content Area -->
                    <div class="p-[28px] pt-[40px] flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-[20px] font-bold text-[#222222] mb-3 leading-snug min-h-[56px] flex items-start">
                                Private Group Class
                            </h3>
                            <p class="text-[14px] text-[#666666] leading-relaxed mb-6 min-h-[80px] flex items-start">
                                Latihan kelompok privat dengan instruktur berpengalaman, cocok untuk komunitas atau teman-teman.
                            </p>
                            <!-- Meta Info -->
                            <div class="flex flex-col gap-y-2.5 mb-6">
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-team-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Max 8-10 orang</span>
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-calendar-todo-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Jadwal fleksibel</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Card -->
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-auto">
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Private%20Group%20Class." target="_blank" class="text-[#FF4F8B] font-semibold text-[14px] transition-opacity hover:opacity-80">
                                Booking Sekarang
                            </a>
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Private%20Group%20Class." target="_blank" class="w-[32px] h-[32px] rounded-full bg-[#FFF0F5] flex items-center justify-center transition-all duration-300 hover:bg-[#FF4F8B] hover:text-white text-[#FF4F8B]">
                                <i class="ri-arrow-right-line text-[14px] transition-transform duration-300 group-hover:translate-x-0.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Private Training -->
                <div class="w-full md:w-[calc((100%-32px)/2)] lg:w-[calc((100%-64px)/3)] flex-shrink-0 flex flex-col bg-white rounded-[28px] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.02)] overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:border-[#FF4F8B]/30 hover:shadow-[0_15px_45px_rgba(255,79,139,0.06)] relative group">
                    <!-- Image Area -->
                    <div class="relative h-[240px] overflow-hidden">
                        <img src="{{ asset('images/foto3.png') }}" alt="Private Training" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" draggable="false" />
                        <!-- Badge -->
                        <div class="absolute top-[16px] left-[16px] bg-gradient-to-r from-[#FF4F8B] to-[#ff7da6] text-white text-[12px] font-bold px-[14px] py-[6px] rounded-full shadow-md z-10">
                            Unggulan
                        </div>
                    </div>

                    <!-- Floating Icon Box -->
                    <div class="absolute top-[212px] left-[28px] w-[56px] h-[56px] bg-white rounded-[18px] shadow-[0_8px_20px_rgba(0,0,0,0.08)] flex items-center justify-center z-20 transition-transform duration-300 group-hover:scale-110">
                        <i class="ri-user-heart-line text-[#FF4F8B] text-[24px]"></i>
                    </div>

                    <!-- Content Area -->
                    <div class="p-[28px] pt-[40px] flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-[20px] font-bold text-[#222222] mb-3 leading-snug min-h-[56px] flex items-start">
                                Private Training
                            </h3>
                            <p class="text-[14px] text-[#666666] leading-relaxed mb-6 min-h-[80px] flex items-start">
                                Sesi latihan personal sesuai kebutuhan Anda, didampingi pelatih profesional untuk hasil optimal.
                            </p>
                            <!-- Meta Info -->
                            <div class="flex flex-col gap-y-2.5 mb-6">
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-user-star-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Personal attention</span>
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-settings-4-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Custom program</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Card -->
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-auto">
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Private%20Training." target="_blank" class="text-[#FF4F8B] font-semibold text-[14px] transition-opacity hover:opacity-80">
                                Booking Sekarang
                            </a>
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Private%20Training." target="_blank" class="w-[32px] h-[32px] rounded-full bg-[#FFF0F5] flex items-center justify-center transition-all duration-300 hover:bg-[#FF4F8B] hover:text-white text-[#FF4F8B]">
                                <i class="ri-arrow-right-line text-[14px] transition-transform duration-300 group-hover:translate-x-0.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Single Visit Class -->
                <div class="w-full md:w-[calc((100%-32px)/2)] lg:w-[calc((100%-64px)/3)] flex-shrink-0 flex flex-col bg-white rounded-[28px] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.02)] overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:border-[#FF4F8B]/30 hover:shadow-[0_15px_45px_rgba(255,79,139,0.06)] relative group">
                    <!-- Image Area -->
                    <div class="relative h-[240px] overflow-hidden">
                        <img src="{{ asset('images/mat pilates.png') }}" alt="Single Visit Class" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" draggable="false" />
                        <!-- Badge -->
                        <div class="absolute top-[16px] left-[16px] bg-gradient-to-r from-[#FF4F8B] to-[#ff7da6] text-white text-[12px] font-bold px-[14px] py-[6px] rounded-full shadow-md z-10">
                            Unggulan
                        </div>
                    </div>

                    <!-- Floating Icon Box -->
                    <div class="absolute top-[212px] left-[28px] w-[56px] h-[56px] bg-white rounded-[18px] shadow-[0_8px_20px_rgba(0,0,0,0.08)] flex items-center justify-center z-20 transition-transform duration-300 group-hover:scale-110">
                        <i class="ri-calendar-check-line text-[#FF4F8B] text-[24px]"></i>
                    </div>

                    <!-- Content Area -->
                    <div class="p-[28px] pt-[40px] flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-[20px] font-bold text-[#222222] mb-3 leading-snug min-h-[56px] flex items-start">
                                Single Visit Class
                            </h3>
                            <p class="text-[14px] text-[#666666] leading-relaxed mb-6 min-h-[80px] flex items-start">
                                Ikuti kelas tanpa harus menjadi member tetap. Fleksibel untuk Anda yang ingin mencoba atau punya jadwal padat.
                            </p>
                            <!-- Meta Info -->
                            <div class="flex flex-col gap-y-2.5 mb-6">
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-heart-3-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>No commitment</span>
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-compass-3-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Try first</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Card -->
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-auto">
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Single%20Visit%20Class." target="_blank" class="text-[#FF4F8B] font-semibold text-[14px] transition-opacity hover:opacity-80">
                                Booking Sekarang
                            </a>
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Single%20Visit%20Class." target="_blank" class="w-[32px] h-[32px] rounded-full bg-[#FFF0F5] flex items-center justify-center transition-all duration-300 hover:bg-[#FF4F8B] hover:text-white text-[#FF4F8B]">
                                <i class="ri-arrow-right-line text-[14px] transition-transform duration-300 group-hover:translate-x-0.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Reformer Pilates -->
                <div class="w-full md:w-[calc((100%-32px)/2)] lg:w-[calc((100%-64px)/3)] flex-shrink-0 flex flex-col bg-white rounded-[28px] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.02)] overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:border-[#FF4F8B]/30 hover:shadow-[0_15px_45px_rgba(255,79,139,0.06)] relative group">
                    <!-- Image Area -->
                    <div class="relative h-[240px] overflow-hidden">
                        <img src="{{ asset('images/revormer pilates.png') }}" alt="Reformer Pilates" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" draggable="false" />
                        <!-- Badge -->
                        <div class="absolute top-[16px] left-[16px] bg-gradient-to-r from-[#FF4F8B] to-[#ff7da6] text-white text-[12px] font-bold px-[14px] py-[6px] rounded-full shadow-md z-10">
                            Unggulan
                        </div>
                    </div>

                    <!-- Floating Icon Box -->
                    <div class="absolute top-[212px] left-[28px] w-[56px] h-[56px] bg-white rounded-[18px] shadow-[0_8px_20px_rgba(0,0,0,0.08)] flex items-center justify-center z-20 transition-transform duration-300 group-hover:scale-110">
                        <i class="ri-focus-3-line text-[#FF4F8B] text-[24px]"></i>
                    </div>

                    <!-- Content Area -->
                    <div class="p-[28px] pt-[40px] flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-[20px] font-bold text-[#222222] mb-3 leading-snug min-h-[56px] flex items-start">
                                Reformer Pilates
                            </h3>
                            <p class="text-[14px] text-[#666666] leading-relaxed mb-6 min-h-[80px] flex items-start">
                                Latihan pilates dengan alat reformer untuk kekuatan, fleksibilitas, dan postur tubuh yang lebih baik.
                            </p>
                            <!-- Meta Info -->
                            <div class="flex flex-col gap-y-2.5 mb-6">
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-tools-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Alat reformer</span>
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-body-scan-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Improve posture</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Card -->
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-auto">
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Reformer%20Pilates." target="_blank" class="text-[#FF4F8B] font-semibold text-[14px] transition-opacity hover:opacity-80">
                                Booking Sekarang
                            </a>
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Reformer%20Pilates." target="_blank" class="w-[32px] h-[32px] rounded-full bg-[#FFF0F5] flex items-center justify-center transition-all duration-300 hover:bg-[#FF4F8B] hover:text-white text-[#FF4F8B]">
                                <i class="ri-arrow-right-line text-[14px] transition-transform duration-300 group-hover:translate-x-0.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Exclusive Class Program -->
                <div class="w-full md:w-[calc((100%-32px)/2)] lg:w-[calc((100%-64px)/3)] flex-shrink-0 flex flex-col bg-white rounded-[28px] border border-gray-100 shadow-[0_8px_30px_rgba(0,0,0,0.02)] overflow-hidden transition-all duration-300 hover:-translate-y-1.5 hover:border-[#FF4F8B]/30 hover:shadow-[0_15px_45px_rgba(255,79,139,0.06)] relative group">
                    <!-- Image Area -->
                    <div class="relative h-[240px] overflow-hidden">
                        <img src="{{ asset('images/foto5.png') }}" alt="Exclusive Class Program" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" draggable="false" />
                        <!-- Badge -->
                        <div class="absolute top-[16px] left-[16px] bg-gradient-to-r from-[#FF4F8B] to-[#ff7da6] text-white text-[12px] font-bold px-[14px] py-[6px] rounded-full shadow-md z-10">
                            Unggulan
                        </div>
                    </div>

                    <!-- Floating Icon Box -->
                    <div class="absolute top-[212px] left-[28px] w-[56px] h-[56px] bg-white rounded-[18px] shadow-[0_8px_20px_rgba(0,0,0,0.08)] flex items-center justify-center z-20 transition-transform duration-300 group-hover:scale-110">
                        <i class="ri-award-line text-[#FF4F8B] text-[24px]"></i>
                    </div>

                    <!-- Content Area -->
                    <div class="p-[28px] pt-[40px] flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="text-[20px] font-bold text-[#222222] mb-3 leading-snug min-h-[56px] flex items-start">
                                Exclusive Class Program
                            </h3>
                            <p class="text-[14px] text-[#666666] leading-relaxed mb-6 min-h-[80px] flex items-start">
                                Program kelas eksklusif dengan materi pilihan, peserta terbatas, dan pendampingan intensif.
                            </p>
                            <!-- Meta Info -->
                            <div class="flex flex-col gap-y-2.5 mb-6">
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-lock-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Limited seats</span>
                                </div>
                                <div class="flex items-center gap-2 text-[13px] text-[#666666]">
                                    <i class="ri-star-line text-[#FF4F8B] text-[16px]"></i>
                                    <span>Intensive coaching</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Card -->
                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center mt-auto">
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Exclusive%20Class%20Program." target="_blank" class="text-[#FF4F8B] font-semibold text-[14px] transition-opacity hover:opacity-80">
                                Booking Sekarang
                            </a>
                            <a href="https://wa.me/6287785767395?text=Halo%20FTM%20Society%2C%20saya%20tertarik%20booking%20Exclusive%20Class%20Program." target="_blank" class="w-[32px] h-[32px] rounded-full bg-[#FFF0F5] flex items-center justify-center transition-all duration-300 hover:bg-[#FF4F8B] hover:text-white text-[#FF4F8B]">
                                <i class="ri-arrow-right-line text-[14px] transition-transform duration-300 group-hover:translate-x-0.5"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Slider Pagination Indicators -->
        <div id="programs-slider-dots" class="flex justify-center items-center gap-2 mt-12">
            <span class="w-[24px] h-[6px] rounded-full bg-[#FF4F8B] transition-all duration-300"></span>
            <span class="w-[6px] h-[6px] rounded-full bg-gray-200 transition-all duration-300"></span>
            <span class="w-[6px] h-[6px] rounded-full bg-gray-200 transition-all duration-300"></span>
            <span class="w-[6px] h-[6px] rounded-full bg-gray-200 transition-all duration-300"></span>
            <span class="w-[6px] h-[6px] rounded-full bg-gray-200 transition-all duration-300"></span>
        </div>

    </div>
</section>

<!-- ========================================= -->
<!-- CLASSES SECTION - PREMIUM REDESIGNED      -->
<!-- Placed right below Program Unggulan       -->
<!-- ========================================= -->
<section id="classes" class="premium-classes-section relative animate-fade-in" style="padding: 100px 0 110px;">
    
    <!-- Glow Orbs -->
    <div class="glow-orb-left"></div>
    <div class="glow-orb-right"></div>

    <!-- Decorative Ornaments -->
    <div class="brand-flower brand-flower-pink brand-float absolute top-12 left-8 opacity-20" style="width: 54px; height: 54px;"></div>
    <div class="brand-asterisk brand-float absolute bottom-16 right-12 opacity-15" style="width: 36px; height: 36px; animation-delay: 1.5s;"></div>
    <div class="brand-cmark brand-float absolute top-1/4 right-[15%] opacity-10" style="width: 48px; height: 48px; animation-delay: 0.8s;"></div>

    <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 relative z-10">

        <!-- Header Section -->
        <div class="text-left mb-16 md:mb-20" data-aos="fade-up">
            
            <!-- Eyebrow badge -->
            <div class="inline-flex items-center gap-2.5 px-5 py-2 mb-6 bg-primary/10 rounded-full border border-primary/20 shadow-md backdrop-blur-sm">
                <span class="brand-flower brand-flower-pink" style="width: 16px; height: 16px;"></span>
                <span class="text-xs md:text-sm font-semibold tracking-[0.2em] text-[#EE4E8B] uppercase">
                    PILIHAN KELAS
                </span>
            </div>

            <!-- Title -->
            <h2 class="font-nord text-4xl sm:text-5xl md:text-[72px] font-black text-dark mb-6 leading-[1.05]">
                Kelas Kebugaran <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary animate-gradient-shift bg-[length:200%_auto] font-instrument italic font-normal tracking-tight">
                    Terbaik Kami
                </span>
            </h2>

            <!-- Decorative Divider (Left-Aligned like other sections) -->
            <div class="flex items-center gap-2.5 mt-6 mb-8">
                <span class="w-16 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                <span class="w-3 h-3 bg-primary rounded-full animate-pulse"></span>
                <span class="w-8 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                <span class="w-2.5 h-2.5 bg-secondary rounded-full animate-pulse" style="animation-delay: 0.5s;"></span>
            </div>

            <p class="text-base md:text-lg text-dark/70 max-w-3xl leading-relaxed font-poppins">
                Temukan program kebugaran yang dirancang khusus untuk kenyamanan dan kebutuhan fisik muslimah, dibimbing langsung oleh instruktur berpengalaman.
            </p>
        </div>

        <!-- 4-Column Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

            <!-- Card 1: Muaythai -->
            <div class="premium-class-card" data-aos="fade-up" data-aos-delay="0">
                <!-- Image Wrapper -->
                <div class="premium-class-img-container relative h-56 overflow-hidden flex-shrink-0">
                    <img src="./images/muaythai.png" alt="Muaythai" class="w-full h-full object-cover" />
                    <!-- Dark gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/50 via-transparent to-transparent"></div>
                    <!-- Badges -->
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-[#EE4E8B] to-[#7A2B4A] text-white text-xs font-black px-3.5 py-1.5 rounded-full shadow-md tracking-wider uppercase">
                        Populer
                    </div>
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm text-primary text-xs font-black px-3 py-1 rounded-full shadow-sm">
                        45 Min
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 flex-1 flex flex-col justify-between relative z-10">
                    <div>
                        <!-- Icon & Title Row -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="premium-class-icon-box w-12 h-12 flex items-center justify-center rounded-2xl bg-[#FFF2F6] text-[#EE4E8B] shadow-sm">
                                <i class="ri-boxing-line text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-nord font-bold text-lg text-dark group-hover:text-primary transition-colors duration-300">
                                    Muaythai
                                </h3>
                                <span class="text-xs text-dark/50 font-semibold font-poppins">Semua Level</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-xs md:text-sm text-dark/70 leading-relaxed mb-6 font-poppins min-h-[72px]">
                            Seni bela diri asal Thailand menggunakan delapan titik kontak tubuh: tangan, siku, lutut, dan kaki — melibatkan teknik serangan dan pertahanan.
                        </p>
                    </div>

                    <div>
                        <!-- Accent Line -->
                        <div class="premium-card-accent-line mb-5"></div>

                        <!-- CTA Button -->
                        <button onclick="openModal('muaythai')"
                                class="w-full relative overflow-hidden group/btn bg-gradient-to-r from-primary to-secondary text-white py-3.5 rounded-full hover:shadow-lg transition-all duration-300 text-xs md:text-sm font-bold flex items-center justify-center gap-2">
                            <span class="relative z-10">Cek Jadwal & Program</span>
                            <i class="ri-arrow-right-line text-sm relative z-10 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2: Body Shaping -->
            <div class="premium-class-card" data-aos="fade-up" data-aos-delay="100">
                <!-- Image Wrapper -->
                <div class="premium-class-img-container relative h-56 overflow-hidden flex-shrink-0">
                    <img src="./images/body shaping.png" alt="Body Shaping" class="w-full h-full object-cover" />
                    <!-- Dark gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/50 via-transparent to-transparent"></div>
                    <!-- Duration -->
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm text-primary text-xs font-black px-3 py-1 rounded-full shadow-sm">
                        30 Min
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 flex-1 flex flex-col justify-between relative z-10">
                    <div>
                        <!-- Icon & Title Row -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="premium-class-icon-box w-12 h-12 flex items-center justify-center rounded-2xl bg-[#FFF2F6] text-[#EE4E8B] shadow-sm">
                                <i class="ri-body-scan-line text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-nord font-bold text-lg text-dark group-hover:text-primary transition-colors duration-300">
                                    Body Shaping
                                </h3>
                                <span class="text-xs text-dark/50 font-semibold font-poppins">Semua Level</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-xs md:text-sm text-dark/70 leading-relaxed mb-6 font-poppins min-h-[72px]">
                            Kelas strength training full body workout untuk toning dan shaping tubuh — dari calisthenics hingga gerakan dengan beban dan equipment pendukung.
                        </p>
                    </div>

                    <div>
                        <!-- Accent Line -->
                        <div class="premium-card-accent-line mb-5"></div>

                        <!-- CTA Button -->
                        <button onclick="openModal('body-shaping')"
                                class="w-full relative overflow-hidden group/btn bg-gradient-to-r from-primary to-secondary text-white py-3.5 rounded-full hover:shadow-lg transition-all duration-300 text-xs md:text-sm font-bold flex items-center justify-center gap-2">
                            <span class="relative z-10">Cek Jadwal & Program</span>
                            <i class="ri-arrow-right-line text-sm relative z-10 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3: Mat Pilates -->
            <div class="premium-class-card" data-aos="fade-up" data-aos-delay="200">
                <!-- Image Wrapper -->
                <div class="premium-class-img-container relative h-56 overflow-hidden flex-shrink-0">
                    <img src="./images/mat pilates.png" alt="Mat Pilates" class="w-full h-full object-cover" />
                    <!-- Dark gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/50 via-transparent to-transparent"></div>
                    <!-- Duration -->
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm text-primary text-xs font-black px-3 py-1 rounded-full shadow-sm">
                        60 Min
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 flex-1 flex flex-col justify-between relative z-10">
                    <div>
                        <!-- Icon & Title Row -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="premium-class-icon-box w-12 h-12 flex items-center justify-center rounded-2xl bg-[#FFF2F6] text-[#EE4E8B] shadow-sm">
                                <i class="ri-mental-health-line text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-nord font-bold text-lg text-dark group-hover:text-primary transition-colors duration-300">
                                    Mat Pilates
                                </h3>
                                <span class="text-xs text-dark/50 font-semibold font-poppins">Semua Level</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-xs md:text-sm text-dark/70 leading-relaxed mb-6 font-poppins min-h-[72px]">
                            Latihan di atas matras fokus pada kekuatan inti (core), stabilitas, postur, pernapasan, dan fleksibilitas — dilakukan secara perlahan dan terkontrol.
                        </p>
                    </div>

                    <div>
                        <!-- Accent Line -->
                        <div class="premium-card-accent-line mb-5"></div>

                        <!-- CTA Button -->
                        <button onclick="openModal('mat-pilates')"
                                class="w-full relative overflow-hidden group/btn bg-gradient-to-r from-primary to-secondary text-white py-3.5 rounded-full hover:shadow-lg transition-all duration-300 text-xs md:text-sm font-bold flex items-center justify-center gap-2">
                            <span class="relative z-10">Cek Jadwal & Program</span>
                            <i class="ri-arrow-right-line text-sm relative z-10 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 4: Reformer Pilates -->
            <div class="premium-class-card" data-aos="fade-up" data-aos-delay="300">
                <!-- Image Wrapper -->
                <div class="premium-class-img-container relative h-56 overflow-hidden flex-shrink-0">
                    <img src="./images/revormer pilates.png" alt="Reformer Pilates" class="w-full h-full object-cover" />
                    <!-- Dark gradient overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-dark/50 via-transparent to-transparent"></div>
                    <!-- Badges -->
                    <div class="absolute top-4 left-4 bg-gradient-to-r from-[#EE4E8B] to-[#7A2B4A] text-white text-xs font-black px-3.5 py-1.5 rounded-full shadow-md tracking-wider uppercase">
                        Populer
                    </div>
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm text-primary text-xs font-black px-3 py-1 rounded-full shadow-sm">
                        45 Min
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-6 flex-1 flex flex-col justify-between relative z-10">
                    <div>
                        <!-- Icon & Title Row -->
                        <div class="flex items-center gap-4 mb-4">
                            <div class="premium-class-icon-box w-12 h-12 flex items-center justify-center rounded-2xl bg-[#FFF2F6] text-[#EE4E8B] shadow-sm">
                                <i class="ri-focus-3-line text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-nord font-bold text-lg text-dark group-hover:text-primary transition-colors duration-300">
                                    Reformer Pilates
                                </h3>
                                <span class="text-xs text-dark/50 font-semibold font-poppins">Semua Level</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-xs md:text-sm text-dark/70 leading-relaxed mb-6 font-poppins min-h-[72px]">
                            Menggunakan alat reformer dengan pegas dan tali untuk resistensi tambahan — variasi Mat Pilates yang dibantu alat untuk hasil lebih optimal.
                        </p>
                    </div>

                    <div>
                        <!-- Accent Line -->
                        <div class="premium-card-accent-line mb-5"></div>

                        <!-- CTA Button -->
                        <button onclick="openModal('reformer-pilates')"
                                class="w-full relative overflow-hidden group/btn bg-gradient-to-r from-primary to-secondary text-white py-3.5 rounded-full hover:shadow-lg transition-all duration-300 text-xs md:text-sm font-bold flex items-center justify-center gap-2">
                            <span class="relative z-10">Cek Jadwal & Program</span>
                            <i class="ri-arrow-right-line text-sm relative z-10 transition-transform duration-300 group-hover/btn:translate-x-1"></i>
                            <div class="absolute inset-0 bg-gradient-to-r from-secondary to-primary opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



<!-- Auto Slide Javascript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const track = document.getElementById('programs-slider-track');
    const container = document.getElementById('programs-slider-container');
    const dots = document.querySelectorAll('#programs-slider-dots span');
    let currentIndex = 0;
    const totalSlides = 5;
    let autoSlideInterval;

    function getVisibleCardsCount() {
        if (window.innerWidth >= 1024) return 3; // lg (Desktop)
        if (window.innerWidth >= 768) return 2;  // md (Tablet)
        return 1;                                // sm (Mobile)
    }

    function getMaxIndex() {
        return totalSlides - getVisibleCardsCount();
    }

    function updateSlider() {
        const visibleCards = getVisibleCardsCount();
        const maxIndex = getMaxIndex();
        
        // Cap index
        if (currentIndex > maxIndex) {
            currentIndex = 0; 
        }
        if (currentIndex < 0) {
            currentIndex = maxIndex;
        }

        const card = track.firstElementChild;
        if (!card) return;

        const cardWidth = card.getBoundingClientRect().width;
        // Gap is 32px
        const gap = 32;
        const amount = currentIndex * (cardWidth + gap);
        
        track.style.transform = `translateX(-${amount}px)`;

        // Update dots
        dots.forEach((dot, index) => {
            if (index <= maxIndex) {
                dot.style.display = 'inline-block';
                if (index === currentIndex) {
                    dot.className = 'w-[24px] h-[6px] rounded-full bg-[#FF4F8B] transition-all duration-300';
                } else {
                    dot.className = 'w-[6px] h-[6px] rounded-full bg-gray-200 transition-all duration-300';
                }
            } else {
                dot.style.display = 'none';
            }
        });
    }

    function nextSlide() {
        const maxIndex = getMaxIndex();
        if (currentIndex >= maxIndex) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }
        updateSlider();
    }

    function startAutoSlide() {
        stopAutoSlide();
        autoSlideInterval = setInterval(nextSlide, 4000); 
    }

    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }

    // Manual dot click handlers
    dots.forEach((dot, index) => {
        dot.style.cursor = 'pointer';
        dot.addEventListener('click', () => {
            const maxIndex = getMaxIndex();
            if (index <= maxIndex) {
                currentIndex = index;
            } else {
                currentIndex = maxIndex;
            }
            updateSlider();
            startAutoSlide();
        });
    });

    // Pause on hover
    container.addEventListener('mouseenter', stopAutoSlide);
    container.addEventListener('mouseleave', startAutoSlide);

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            updateSlider();
        }, 100);
    });

    // Grabbing and Dragging Support (Mouse + Touch)
    let isDragging = false;
    let hasDragged = false; // true only if pointer moved beyond click threshold
    let startX = 0;
    let startY = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let dragTarget = null; // store the original event target

    const CLICK_THRESHOLD = 8; // px — movement below this counts as a click

    container.style.cursor = 'grab';
    container.style.userSelect = 'none'; // Prevent text selection during drag

    container.addEventListener('pointerdown', dragStart);
    container.addEventListener('pointerup', dragEnd);
    container.addEventListener('pointercancel', dragEnd);
    container.addEventListener('pointerleave', dragEnd);
    container.addEventListener('pointermove', dragAction);

    // Prevent default drag behaviors on images only (not links)
    container.addEventListener('dragstart', (e) => e.preventDefault());

    // Block clicks ONLY when a real drag occurred (prevents link navigation mid-swipe)
    container.addEventListener('click', (e) => {
        if (hasDragged) {
            e.preventDefault();
            e.stopPropagation();
        }
    }, true);

    function dragStart(e) {
        // Only trigger on left mouse button, or touch pointers
        if (e.pointerType === 'mouse' && e.button !== 0) return;

        isDragging = true;
        hasDragged = false;
        startX = e.clientX;
        startY = e.clientY;
        dragTarget = e.target;
        stopAutoSlide();

        const card = track.firstElementChild;
        if (card) {
            const cardWidth = card.getBoundingClientRect().width;
            const gap = 32;
            prevTranslate = -currentIndex * (cardWidth + gap);
        } else {
            prevTranslate = 0;
        }
        currentTranslate = prevTranslate;
    }

    function dragAction(e) {
        if (!isDragging) return;
        const currentX = e.clientX;
        const diffX = currentX - startX;

        // Only start visual dragging once we exceed click threshold
        if (!hasDragged && Math.abs(diffX) > CLICK_THRESHOLD) {
            hasDragged = true;
            container.style.cursor = 'grabbing';
            // Disable CSS transition only once real drag begins
            track.style.transition = 'none';
            // Capture pointer so drag continues outside container
            container.setPointerCapture(e.pointerId);
        }

        if (!hasDragged) return; // still within click threshold, don't move track

        currentTranslate = prevTranslate + diffX;

        // Boundary resistance calculations
        const maxIndex = getMaxIndex();
        const card = track.firstElementChild;
        if (card) {
            const cardWidth = card.getBoundingClientRect().width;
            const gap = 32;
            const maxTranslate = -maxIndex * (cardWidth + gap);

            if (currentTranslate > 0) {
                currentTranslate = currentTranslate * 0.3; // resistance when dragging past start
            } else if (currentTranslate < maxTranslate) {
                currentTranslate = maxTranslate + (currentTranslate - maxTranslate) * 0.3; // resistance when dragging past end
            }
        }

        track.style.transform = `translateX(${currentTranslate}px)`;
    }

    function dragEnd(e) {
        if (!isDragging) return;
        isDragging = false;
        container.style.cursor = 'grab';

        // Release pointer capture if we had it
        try { container.releasePointerCapture(e.pointerId); } catch(_) {}

        // Re-enable smooth CSS transition
        track.style.transition = 'transform 0.7s ease-in-out';

        if (!hasDragged) {
            // It was a simple click, not a drag — let the browser handle it naturally.
            // The click event will fire on the original target (link, button, etc.)
            startAutoSlide();
            return;
        }

        // It was a real drag — decide whether to switch slides
        const diffX = e.clientX - startX;
        const threshold = 60; // drag threshold to switch slides (in pixels)

        if (Math.abs(diffX) > threshold) {
            if (diffX < 0) {
                // Dragged left -> next slide
                const maxIndex = getMaxIndex();
                if (currentIndex < maxIndex) {
                    currentIndex++;
                }
            } else {
                // Dragged right -> prev slide
                if (currentIndex > 0) {
                    currentIndex--;
                }
            }
        }

        updateSlider();
        startAutoSlide();

        // Reset hasDragged after a microtask so the click handler above can still catch it
        setTimeout(() => { hasDragged = false; }, 0);
    }

    // Initialize
    updateSlider();
    startAutoSlide();
});
</script>

<!-- ========================================= -->
<!-- ENHANCED CSS - Tambahkan/Update di <style> -->
<!-- ========================================= -->

<style>
    /* Nord font helper (replaced Playfair Display per brand guidelines) */
    .font-playfair {
        font-family: 'Nord', 'Poppins', sans-serif !important;
    }

    /* Gradient Shift Animation */
    @keyframes gradient-shift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Premium Fade In Animation for Hero */
    @keyframes fadeInHero {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeInHero 0.9s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }

    /* Smooth Bounce Animation for Scroll Indicator */
    @keyframes bounce-smooth {
        0%, 100% {
            transform: translateY(0);
            opacity: 1;
        }
        50% {
            transform: translateY(-10px);
            opacity: 0.85;
        }
    }

    /* Scroll Dot Bounce Animation */
    @keyframes scroll-dot-bounce {
        0% {
            opacity: 0;
            transform: translateY(-8px);
        }
        50% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: translateY(8px);
        }
    }

    /* Enhanced AOS Animations */
    [data-aos] {
        opacity: 0;
        transition-property: opacity, transform;
        transition-duration: 0.8s;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-aos].aos-animate {
        opacity: 1;
        transform: translate(0, 0) !important;
    }

    [data-aos="fade-right"] { transform: translateX(-60px); }
    [data-aos="fade-left"] { transform: translateX(60px); }
    [data-aos="fade-up"] { transform: translateY(60px); }
    [data-aos="fade-down"] { transform: translateY(-60px); }

    /* Smooth Transitions */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Prevent horizontal scroll */
    body {
        overflow-x: hidden;
    }

    /* Scroll Indicator Auto-Hide on Scroll */
    #scroll-indicator {
        transition: opacity 0.3s ease-in-out;
    }

    /* Premium Redesigned Classes Section Styling */
    .premium-classes-section {
        background: linear-gradient(135deg, #FCF9F2 0%, #FFF2F6 40%, #FFF9FA 70%, #FCEAF2 100%);
        position: relative;
        overflow: hidden;
    }

    .premium-classes-section .glow-orb-left {
        position: absolute;
        top: -10%;
        left: -15%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(238,78,139,0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .premium-classes-section .glow-orb-right {
        position: absolute;
        bottom: -15%;
        right: -10%;
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(26,122,94,0.05) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Floating sparkle icons for Classes */
    .premium-classes-section .float-sparkle {
        position: absolute;
        color: #EE4E8B;
        opacity: 0.25;
        animation: floatSparkleClasses 6s ease-in-out infinite;
    }
    
    @keyframes floatSparkleClasses {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(8deg); }
    }

    /* Glassmorphism premium card */
    .premium-class-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 32px;
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 30px rgba(122, 43, 74, 0.03), 0 1px 3px rgba(0, 0, 0, 0.01);
    }

    .premium-class-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 32px;
        padding: 1.5px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(238, 78, 139, 0), rgba(122, 43, 74, 0.15));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        transition: all 0.5s ease;
    }

    .premium-class-card:hover {
        transform: translateY(-8px);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 20px 40px rgba(122, 43, 74, 0.1), 0 8px 16px rgba(122, 43, 74, 0.05);
        border-color: rgba(238, 78, 139, 0.25);
    }

    .premium-class-card:hover::before {
        background: linear-gradient(135deg, #EE4E8B, rgba(238, 78, 139, 0.2), #7A2B4A);
    }

    /* Icon Box styling */
    .premium-class-icon-box {
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    .premium-class-card:hover .premium-class-icon-box {
        transform: scale(1.12) rotate(6deg);
        background: linear-gradient(135deg, #EE4E8B 0%, #7A2B4A 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(238, 78, 139, 0.25);
    }

    /* Accent bottom line */
    .premium-card-accent-line {
        height: 2px;
        width: 0;
        background: linear-gradient(90deg, #EE4E8B, #7A2B4A, #EE4E8B);
        border-radius: 99px;
        transition: width 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .premium-class-card:hover .premium-card-accent-line {
        width: 100%;
    }

    /* Image zoom scaling */
    .premium-class-img-container img {
        transition: transform 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .premium-class-card:hover .premium-class-img-container img {
        transform: scale(1.08);
    }

    /* Premium Pricing Section Styling */
    /* ═══════════════════════════════════════════ */
    /* PREMIUM PRICING SECTION — Ultra Premium     */
    /* ═══════════════════════════════════════════ */
    .premium-pricing-section {
        background: linear-gradient(100deg, #fffcfd 20%, #fff2f6 60%, #ffe3ec 100%);
        position: relative;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
    }

    /* Animated mesh gradient overlay */
    .premium-pricing-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 800px 600px at 15% 20%, rgba(238,78,139,0.05) 0%, transparent 60%),
            radial-gradient(ellipse 600px 800px at 85% 80%, rgba(122,43,74,0.04) 0%, transparent 60%);
        pointer-events: none;
        z-index: 1;
    }

    /* Premium glow orbs */
    .pricing-glow-orb {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(80px);
        z-index: 1;
    }
    .pricing-glow-orb-1 {
        top: -100px;
        left: -50px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(238,78,139,0.08) 0%, transparent 70%);
        animation: pricing-float-orb 8s ease-in-out infinite;
    }
    .pricing-glow-orb-2 {
        bottom: -150px;
        right: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(122,43,74,0.06) 0%, transparent 70%);
        animation: pricing-float-orb 10s ease-in-out infinite reverse;
    }
    .pricing-glow-orb-3 {
        top: 50%;
        left: 40%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(244,201,223,0.08) 0%, transparent 70%);
        animation: pricing-float-orb 12s ease-in-out infinite 2s;
    }

    @keyframes pricing-float-orb {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -20px) scale(1.05); }
        66% { transform: translate(-20px, 15px) scale(0.95); }
    }

    /* Subtle grid pattern */
    .pricing-grid-pattern {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(238,78,139,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(238,78,139,0.02) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none;
        z-index: 1;
    }

    /* Premium pricing card — light, feminine glassmorphism */
    .premium-pricing-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(238,78,139,0.12);
        border-radius: 28px;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow:
            0 4px 20px rgba(238,78,139,0.06),
            0 1px 3px rgba(0, 0, 0, 0.02),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
    }

    /* Shimmer border effect */
    .premium-pricing-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 28px;
        padding: 1.5px;
        background: linear-gradient(
            135deg,
            rgba(238,78,139,0.2) 0%,
            rgba(255,255,255,0.6) 25%,
            rgba(244,201,223,0.3) 50%,
            rgba(255,255,255,0.6) 75%,
            rgba(238,78,139,0.2) 100%
        );
        background-size: 300% 300%;
        animation: pricing-shimmer 6s ease infinite;
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    @keyframes pricing-shimmer {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Inner card glow on hover */
    .premium-pricing-card::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 30%, rgba(238,78,139,0.05), transparent 50%);
        opacity: 0;
        transition: opacity 0.6s ease;
        pointer-events: none;
        z-index: 0;
    }

    .premium-pricing-card:hover {
        transform: translateY(-5px) scale(1.015);
        background: rgba(255, 255, 255, 0.95);
        box-shadow:
            0 20px 40px rgba(238,78,139,0.12),
            0 10px 20px rgba(0, 0, 0, 0.04),
            inset 0 1px 0 rgba(255, 255, 255, 0.9);
        border-color: rgba(238,78,139,0.25);
    }

    .premium-pricing-card:hover::after {
        opacity: 1;
    }

    .premium-pricing-card:hover::before {
        background: linear-gradient(135deg, #EE4E8B, rgba(238,78,139,0.3), #F4C9DF, rgba(238,78,139,0.3), #EE4E8B);
        background-size: 300% 300%;
        animation: pricing-shimmer 3s ease infinite;
    }

    /* Premium card price tag */
    .pricing-price-tag {
        background: linear-gradient(135deg, #fff0f5 0%, #ffe0ec 100%);
        border: 1px solid rgba(238,78,139,0.15);
        border-radius: 16px;
        padding: 14px 18px;
        position: relative;
        overflow: hidden;
        min-height: 82px;
        display: flex;
        align-items: center;
    }

    .pricing-price-tag::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(238,78,139,0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Feature list item */
    .pricing-feature-item {
        display: flex;
        gap: 12px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(238,78,139,0.06);
        transition: all 0.3s ease;
    }

    .pricing-feature-item:last-child {
        border-bottom: none;
    }

    .pricing-feature-item:hover {
        padding-left: 8px;
    }

    .pricing-feature-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: linear-gradient(135deg, #fff0f5 0%, #ffe0ec 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #EE4E8B;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 1px solid rgba(238,78,139,0.1);
    }

    .pricing-feature-item:hover .pricing-feature-icon {
        background: linear-gradient(135deg, #EE4E8B, #7A2B4A);
        color: white;
        transform: scale(1.1);
        border-color: transparent;
    }

    /* CTA button premium */
    .pricing-cta-btn {
        display: block;
        width: 100%;
        padding: 13px 24px;
        text-align: center;
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        border-radius: 14px;
        transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .pricing-cta-btn-primary {
        background: linear-gradient(135deg, #EE4E8B, #C2185B, #7A2B4A);
        color: white;
        border: none;
        box-shadow: 0 8px 25px rgba(238,78,139,0.3);
    }

    .pricing-cta-btn-primary:hover {
        box-shadow: 0 12px 35px rgba(238,78,139,0.5);
        transform: translateY(-3px);
    }

    .pricing-cta-btn-primary::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #7A2B4A, #EE4E8B, #C2185B);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: -1;
    }

    .pricing-cta-btn-primary:hover::before {
        opacity: 1;
    }

    /* Exclusive badge */
    .pricing-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        background: linear-gradient(135deg, #EE4E8B, #7A2B4A);
        color: white;
        border: none;
    }

    /* Decorative floating elements */
    .pricing-float-deco {
        position: absolute;
        pointer-events: none;
        z-index: 1;
        opacity: 0.4;
    }

    @keyframes pricing-float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }

    /* Decorative line */
    .pricing-accent-divider {
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(238,78,139,0.2), rgba(244,201,223,0.3), transparent);
        margin: 16px 0;
    }

    /* Left column header decoratives */
    .pricing-header-deco-line {
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #EE4E8B, #7A2B4A);
        border-radius: 3px;
        position: relative;
    }

    .pricing-header-deco-line::after {
        content: '';
        position: absolute;
        right: -16px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: #EE4E8B;
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    /* Sparkle decoration */
    .pricing-sparkle {
        position: absolute;
        color: rgba(238,78,139,0.2);
        font-size: 18px;
        pointer-events: none;
        z-index: 2;
    }
    .pricing-sparkle-1 { top: 12%; right: 8%; animation: pricing-float 6s ease infinite; }
    .pricing-sparkle-2 { bottom: 15%; left: 5%; animation: pricing-float 8s ease infinite 1s; }
    .pricing-sparkle-3 { top: 45%; right: 3%; animation: pricing-float 7s ease infinite 2s; }
</style>

<!-- ========================================= -->
<!-- ENHANCED JAVASCRIPT - Update existing atau tambahkan -->
<!-- ========================================= -->

<script>
    // ─── SCROLL SPY using Intersection Observer ───
    function initScrollSpy() {
        const sections = document.querySelectorAll('section[id]');
        const desktopLinks = document.querySelectorAll('#desktop-nav .nav-link');
        const mobileLinks = document.querySelectorAll('.nav-link-mobile');
        const allLinks = [...desktopLinks, ...mobileLinks];
        
        if (!sections.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    // Map section id to data-nav value
                    const navMap = {
                        'home': 'home',
                        'our-programs': 'programs',
                        'about': 'about',
                        'pricing': 'package',
                        'Facility': 'gallery',
                        'contact': 'contact'
                    };
                    const navValue = navMap[id] || id;
                    
                    // Update all nav links
                    allLinks.forEach(link => {
                        const linkNav = link.getAttribute('data-nav');
                        const isActive = linkNav === navValue;
                        
                        // Desktop or mobile class handling
                        if (link.classList.contains('nav-link')) {
                            link.classList.toggle('active', isActive);
                        } else if (link.classList.contains('nav-link-mobile')) {
                            link.classList.toggle('active', isActive);
                        }
                        
                        // Accessibility: aria-current
                        if (isActive) {
                            link.setAttribute('aria-current', 'page');
                        } else {
                            link.removeAttribute('aria-current');
                        }
                    });
                }
            });
        }, {
            rootMargin: '-80px 0px -40% 0px',
            threshold: 0
        });

        sections.forEach(section => observer.observe(section));
    }

    // ─── SMOOTH SCROLL with navbar offset ───
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && document.querySelector(href)) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    const header = document.querySelector('header');
                    const headerHeight = header ? header.offsetHeight : 80;
                    const offsetTop = target.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                    
                    // Update URL hash without scroll jump
                    history.pushState(null, null, href);
                    
                    // Update active state immediately on click (for non-landing page routing)
                    const navMap = {
                        '#home': 'home',
                        '#our-programs': 'programs',
                        '#about': 'about',
                        '#pricing': 'package',
                        '#Facility': 'gallery',
                        '#contact': 'contact'
                    };
                    const navValue = navMap[href];
                    if (navValue) {
                        const allLinks = document.querySelectorAll('.nav-link, .nav-link-mobile');
                        allLinks.forEach(link => {
                            const linkNav = link.getAttribute('data-nav');
                            const isActive = linkNav === navValue;
                            if (link.classList.contains('nav-link')) {
                                link.classList.toggle('active', isActive);
                            } else if (link.classList.contains('nav-link-mobile')) {
                                link.classList.toggle('active', isActive);
                            }
                            if (isActive) {
                                link.setAttribute('aria-current', 'page');
                            } else {
                                link.removeAttribute('aria-current');
                            }
                        });
                    }
                }
            });
        });
    }

    // ─── HASH CHANGE SUPPORT ───
    function initHashSync() {
        function syncFromHash() {
            const hash = window.location.hash || '#home';
            const navMap = {
                '#home': 'home',
                '#our-programs': 'programs',
                '#about': 'about',
                '#pricing': 'package',
                '#Facility': 'gallery',
                '#contact': 'contact'
            };
            const navValue = navMap[hash];
            if (navValue) {
                const allLinks = document.querySelectorAll('.nav-link, .nav-link-mobile');
                allLinks.forEach(link => {
                    const linkNav = link.getAttribute('data-nav');
                    const isActive = linkNav === navValue;
                    if (link.classList.contains('nav-link')) {
                        link.classList.toggle('active', isActive);
                    } else if (link.classList.contains('nav-link-mobile')) {
                        link.classList.toggle('active', isActive);
                    }
                    if (isActive) {
                        link.setAttribute('aria-current', 'page');
                    } else {
                        link.removeAttribute('aria-current');
                    }
                });
            }
        }

        // Sync on page load / refresh
        window.addEventListener('DOMContentLoaded', syncFromHash);
        // Sync on hash change
        window.addEventListener('hashchange', syncFromHash);
    }

    // ─── Enhanced AOS Implementation ───
    function initEnhancedAOS() {
        const elements = document.querySelectorAll('[data-aos]');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.getAttribute('data-aos-delay') || 0;
                    setTimeout(() => {
                        entry.target.classList.add('aos-animate');
                    }, delay);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -80px 0px'
        });

        elements.forEach(el => observer.observe(el));
    }

    // ─── Scroll Indicator Auto Hide ───
    function initScrollIndicator() {
        const scrollIndicator = document.getElementById('scroll-indicator');
        const heroSection = document.getElementById('home');
        
        if (!scrollIndicator || !heroSection) return;
        
        window.addEventListener('scroll', function() {
            const heroBottom = heroSection.offsetHeight;
            const scrollPosition = window.scrollY;
            
            if (scrollPosition > heroBottom - 100) {
                scrollIndicator.style.opacity = '0';
                scrollIndicator.style.pointerEvents = 'none';
            } else {
                scrollIndicator.style.opacity = '1';
                scrollIndicator.style.pointerEvents = 'auto';
            }
        });
    }

    // ─── Initialize Everything on DOM Ready ───
    document.addEventListener('DOMContentLoaded', function() {
        initScrollSpy();
        initSmoothScroll();
        initHashSync();
        initEnhancedAOS();
        initScrollIndicator();
        
        // Trigger immediate animations for visible elements
        setTimeout(() => {
            const visibleElements = document.querySelectorAll('#about [data-aos]');
            visibleElements.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    el.classList.add('aos-animate');
                }
            });
        }, 100);
    });
</script>


<!-- ========================================= -->
<!-- ========================================= -->
<!-- PACKAGES & PRICING SECTION — ULTRA PREMIUM -->
<!-- Header top left, cards horizontal slider    -->
<!-- ========================================= -->

<section id="pricing" class="premium-pricing-section relative" style="padding: 100px 0 110px;" data-aos="fade-up">
    
    <!-- Premium Background Effects -->
    <div class="pricing-glow-orb pricing-glow-orb-1"></div>
    <div class="pricing-glow-orb pricing-glow-orb-2"></div>
    <div class="pricing-glow-orb pricing-glow-orb-3"></div>
    <div class="pricing-grid-pattern"></div>

    <!-- Floating Sparkles -->
    <div class="pricing-sparkle pricing-sparkle-1">✦</div>
    <div class="pricing-sparkle pricing-sparkle-2">✦</div>
    <div class="pricing-sparkle pricing-sparkle-3">✦</div>

    <div class="w-full max-w-[1440px] mx-auto px-6 sm:px-10 lg:px-16 xl:px-20 relative" style="z-index: 10;">

        <!-- TOP: Section Header (left-aligned, like Tentang Kami) -->
        <div class="text-center lg:text-left mb-14 md:mb-16 max-w-2xl">

            <!-- Eyebrow label -->
            <div class="inline-flex items-center gap-2.5 px-5 py-2.5 mb-8 rounded-full border border-[rgba(238,78,139,0.15)] backdrop-blur-md" style="background: rgba(238,78,139,0.05);">
                <div class="relative">
                    <div class="w-2.5 h-2.5 bg-primary rounded-full animate-ping absolute"></div>
                    <div class="w-2.5 h-2.5 bg-primary rounded-full relative"></div>
                </div>
                <span class="text-xs md:text-sm font-semibold tracking-[0.2em] text-[#EE4E8B] uppercase font-poppins">
                    The Best Investment for Your Health
                </span>
            </div>

            <!-- Main Heading -->
            <h2 style="
                font-family: 'Nord', 'Poppins', sans-serif;
                font-weight: 900;
                font-size: clamp(42px, 5.5vw, 72px);
                line-height: 1.05;
                color: #2D2D2D;
                margin-bottom: 24px;
            ">
                Packages &<br>
                <span style="
                    background: linear-gradient(135deg, #EE4E8B, #F472B6, #EE4E8B);
                    background-size: 200% auto;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                    font-family: 'Instrument Serif', Georgia, serif;
                    font-style: italic;
                    font-weight: 400;
                    animation: gradient-shift 4s ease infinite;
                ">Pricing</span>
            </h2>

            <!-- Decorative divider -->
            <div class="flex items-center gap-2.5 mt-4 mb-6 justify-center lg:justify-start">
                <span class="w-16 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                <span class="w-3 h-3 bg-primary rounded-full animate-pulse"></span>
                <span class="w-8 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                <span class="w-2.5 h-2.5 bg-secondary rounded-full animate-pulse" style="animation-delay: 0.5s;"></span>
            </div>

            <!-- Description -->
            <p style="
                font-family: 'Poppins', sans-serif;
                font-size: 15px;
                font-weight: 400;
                line-height: 1.8;
                color: #6B7280;
            " class="mx-auto lg:mx-0">
                Pilih rencana yang sempurna yang sesuai dengan perjalanan kebugaran dan gaya hidup Anda.
            </p>
        </div>

        <!-- BOTTOM: Pricing Cards Horizontal Slider -->
        <div class="relative">
            
            <!-- Nav Left -->
            <button type="button" id="pricingScrollLeft"
                class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 items-center justify-center w-12 h-12 rounded-full shadow-lg transition-all duration-300 z-20 group hover:scale-110"
                style="background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(238,78,139,0.2); backdrop-filter: blur(12px); display:none;"
                aria-label="Scroll Left"
                onclick="pricingSlide(-1)">
                <i class="ri-arrow-left-s-line text-2xl text-primary group-hover:text-secondary transition-colors"></i>
            </button>

            <!-- Cards Container -->
            <div id="pricingSlider"
                 class="flex items-stretch gap-5 md:gap-6 overflow-x-auto overflow-y-hidden scroll-smooth snap-x snap-mandatory pt-3 pb-5"
                 style="scrollbar-width: none; -ms-overflow-style: none;"
                 onscroll="pricingToggleNav()">

                {{-- Dynamic Packages from Admin Panel --}}
                @if(isset($packages) && $packages->count() > 0)
                    @foreach($packages as $index => $package)
                    <div class="min-w-[280px] max-w-[300px] flex-shrink-0 flex snap-start" data-aos="fade-up" data-aos-delay="{{ min($index * 100, 500) }}">
                        <div class="premium-pricing-card group p-5 md:p-6 w-full flex flex-col">

                             <div class="relative z-10 flex flex-col h-full">

                                {{-- Badge --}}
                                <div class="mb-3">
                                    <span class="pricing-badge">
                                        <i class="ri-vip-crown-2-fill text-xs"></i>
                                        <span>EKSKLUSIF</span>
                                    </span>
                                </div>

                                <!-- Package Name -->
                                <h3 class="font-nord font-bold text-base md:text-lg text-dark mb-3 leading-snug group-hover:text-primary transition-colors duration-500" style="min-height: 72px;">
                                    {{ $package->name }}
                                </h3>

                                {{-- Price Tag --}}
                                <div class="pricing-price-tag mb-3">
                                    <div class="relative z-10 w-full">
                                        <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-primary via-[#F472B6] to-primary" style="background-size: 200% auto; animation: gradient-shift 4s ease infinite;">
                                            Rp {{ number_format($package->price, 0, ',', '.') }}
                                        </span>
                                        @if($package->duration_days)
                                            <p class="text-dark/50 text-xs mt-1 font-poppins">Valid {{ $package->duration_days }} hari</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Features --}}
                                <div class="w-full mb-3 flex-1">
                                    @if($package->quota)
                                    <div class="pricing-feature-item min-h-[40px] flex items-center">
                                        <div class="pricing-feature-icon">
                                            <i class="ri-checkbox-circle-fill"></i>
                                        </div>
                                        <span class="text-dark/70 text-xs font-poppins">{{ $package->quota }} Sessions</span>
                                    </div>
                                    @else
                                    <div class="pricing-feature-item min-h-[40px] flex items-center">
                                        <div class="pricing-feature-icon">
                                            <i class="ri-checkbox-circle-fill"></i>
                                        </div>
                                        <span class="text-dark/70 text-xs font-poppins">Unlimited Sessions</span>
                                    </div>
                                    @endif

                                    @if($package->description)
                                    <div class="pricing-feature-item min-h-[56px] flex items-start pt-1">
                                        <div class="pricing-feature-icon mt-0.5">
                                            <i class="ri-information-fill"></i>
                                        </div>
                                        <span class="text-dark/60 text-xs font-poppins leading-normal pl-1">{{ Str::limit($package->description, 60) }}</span>
                                    </div>
                                    @endif
                                </div>

                                {{-- Divider --}}
                                <div class="pricing-accent-divider mb-3"></div>

                                {{-- CTA Button --}}
                                <div class="w-full mt-auto">
                                    @auth('customer')
                                        <a href="{{ route('join') }}"
                                             class="pricing-cta-btn pricing-cta-btn-primary">
                                             <i class="ri-arrow-right-line mr-1.5"></i> Daftar Sekarang
                                        </a>
                                    @else
                                        <a href="{{ route('join') }}"
                                             class="pricing-cta-btn pricing-cta-btn-primary">
                                             <i class="ri-arrow-right-line mr-1.5"></i> Sign Up Now
                                        </a>
                                    @endauth
                                </div>

                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif

            </div>

            <!-- Nav Right -->
            <button type="button" id="pricingScrollRight"
                class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 items-center justify-center w-12 h-12 rounded-full shadow-lg transition-all duration-300 z-20 group hover:scale-110"
                style="background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(238,78,139,0.2); backdrop-filter: blur(12px);"
                aria-label="Scroll Right"
                onclick="pricingSlide(1)">
                <i class="ri-arrow-right-s-line text-2xl text-primary group-hover:text-secondary transition-colors"></i>
            </button>

        </div>

    </div>
</section>

<style>
    #pricingSlider {
        cursor: grab;
        user-select: none;
        -webkit-user-select: none;
    }
    #pricingSlider:active {
        cursor: grabbing;
    }
    #pricingSlider::-webkit-scrollbar { display: none; }
    @keyframes gradient-shift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animate-gradient-shift { animation: gradient-shift 4s ease infinite; }
</style>

<script>
(function() {
    var slider = document.getElementById('pricingSlider');

    // Smooth scroll with requestAnimationFrame and custom easing
    function smoothScrollTo(element, target, duration) {
        if (!element) return;
        const start = element.scrollLeft;
        const change = target - start;
        let startTime = null;

        function animateScroll(timestamp) {
            if (!startTime) startTime = timestamp;
            const elapsed = timestamp - startTime;
            
            // Cubic ease-in-out curve
            const t = Math.min(elapsed / duration, 1);
            const ease = t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
            
            element.scrollLeft = start + change * ease;

            if (elapsed < duration) {
                requestAnimationFrame(animateScroll);
            } else {
                element.scrollLeft = target;
                pricingToggleNav();
            }
        }
        requestAnimationFrame(animateScroll);
    }

    // Get the scroll position of the nearest card
    function getNearestSnapTarget() {
        if (!slider) return 0;
        const children = slider.children;
        let nearestTarget = 0;
        let minDiff = Infinity;
        const currentScroll = slider.scrollLeft;

        for (let i = 0; i < children.length; i++) {
            const child = children[i];
            if (child.nodeType !== 1) continue; 
            const childLeft = child.offsetLeft - slider.offsetLeft;
            const diff = Math.abs(childLeft - currentScroll);
            if (diff < minDiff) {
                minDiff = diff;
                nearestTarget = childLeft;
            }
        }
        
        const maxScroll = slider.scrollWidth - slider.clientWidth;
        return Math.max(0, Math.min(nearestTarget, maxScroll));
    }

    function pricingSlide(dir) {
        if (!slider) return;
        
        slider.style.scrollSnapType = 'none';
        
        const children = slider.children;
        let nextIndex = 0;
        
        for (let i = 0; i < children.length; i++) {
            if (children[i].nodeType !== 1) continue;
            const childLeft = children[i].offsetLeft - slider.offsetLeft;
            if (Math.abs(childLeft - slider.scrollLeft) < 15) {
                nextIndex = i;
                break;
            }
        }
        
        nextIndex = Math.max(0, Math.min(nextIndex + dir, children.length - 1));
        const targetScroll = children[nextIndex].offsetLeft - slider.offsetLeft;
        const maxScroll = slider.scrollWidth - slider.clientWidth;
        const safeTarget = Math.max(0, Math.min(targetScroll, maxScroll));

        smoothScrollTo(slider, safeTarget, 400);

        setTimeout(function() {
            slider.style.scrollSnapType = 'x mandatory';
        }, 450);
    }

    function pricingToggleNav() {
        var l = document.getElementById('pricingScrollLeft');
        var r = document.getElementById('pricingScrollRight');
        if (!slider || !l || !r) return;
        l.style.display = slider.scrollLeft > 10 ? 'flex' : 'none';
        r.style.display = (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) ? 'none' : 'flex';
    }

    if (slider) {
        let isDown = false;
        let startX;
        let scrollLeft;
        let hasMoved = false;

        slider.addEventListener('mousedown', (e) => {
            if (e.button !== 0) return; // Only left click drag
            isDown = true;
            hasMoved = false;
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
            
            slider.style.scrollSnapType = 'none';
            slider.style.scrollBehavior = 'auto';
            slider.style.cursor = 'grabbing';
        });

        function handleDragEnd() {
            if (!isDown) return;
            isDown = false;
            slider.style.cursor = 'grab';
            
            const target = getNearestSnapTarget();
            smoothScrollTo(slider, target, 300);

            setTimeout(function() {
                slider.style.scrollSnapType = 'x mandatory';
            }, 350);
        }

        slider.addEventListener('mouseleave', handleDragEnd);
        slider.addEventListener('mouseup', handleDragEnd);

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5;
            if (Math.abs(walk) > 5) {
                hasMoved = true;
            }
            slider.scrollLeft = scrollLeft - walk;
            pricingToggleNav();
        });

        slider.addEventListener('click', (e) => {
            if (hasMoved) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
    }

    window.pricingSlide = pricingSlide;
    window.pricingToggleNav = pricingToggleNav;
    document.addEventListener('DOMContentLoaded', pricingToggleNav);
    window.addEventListener('resize', pricingToggleNav);
})();
</script>


<!-- ========================================= -->
<!-- CLASSES SECTION - ULTIMATE PROFESSIONAL  -->
<!-- Consistent with FTM Society design style -->
<!-- Cards TIDAK bergoyang                     -->
<!-- ========================================= -->



<div id="class-modal"
     class="fixed inset-0 bg-dark/60 backdrop-blur-sm hidden justify-center items-center z-50 px-4">
    <div class="bg-cream w-full max-w-lg rounded-3xl shadow-2xl relative overflow-hidden">

        <!-- Modal gradient top bar -->
        <div class="h-1.5 w-full bg-gradient-to-r from-primary via-secondary to-primary"></div>

        <!-- Close Button -->
        <button onclick="closeModal()"
                class="absolute top-4 right-4 w-9 h-9 flex items-center justify-center rounded-full bg-cream hover:bg-light-pink hover:text-primary text-dark/55 text-lg font-bold transition-colors duration-200 z-10">
            &times;
        </button>

        <!-- Modal Content -->
        <div class="p-7">
            <h3 id="modal-title" class="text-2xl font-black text-primary mb-1"></h3>
            <div id="modal-content" class="text-sm text-dark/70 mt-3"></div>

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
const classSchedules = {
    @foreach($groupedSchedules as $keyword => $entries)
        '{{ $keyword }}': [
            @foreach($entries->take(100) as $entry)
                {
                    hari: '{{ $entry->day }}',
                    tanggal: '{{ $entry->schedule_date }}',
                    jam: '{{ \Carbon\Carbon::parse($entry->class_time)->format('H:i') }}',
                    instruktur: '{{ $entry->instructor ?? '-' }}',
                    kelas: '{{ optional($entry->classModel)->class_name ?? '-' }}'
                }@if(!$loop->last),@endif
            @endforeach
        ]@if(!$loop->last),@endif
    @endforeach
};

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
            <th class="text-left py-1 px-2 text-dark/70">Class</th>
            <th class="text-left py-1 px-2 text-dark/70">Hari</th>
            <th class="text-left py-1 px-2 text-dark/70">Tanggal</th>
            <th class="text-left py-1 px-2 text-dark/70">Jam</th>
            <th class="text-left py-1 px-2 text-dark/70">Instruktur</th>
          </tr>
        </thead>
        <tbody>
          ${classSchedules[key].map(j => `
          <tr>
            <td class="py-1 px-2 text-xs text-dark/55">${j.kelas}</td>
            <td class="py-1 px-2">${j.hari}</td>
            <td class="py-1 px-2">${j.tanggal}</td>
            <td class="py-1 px-2">${j.jam ? j.jam.split(' ')[1].slice(0,5) : ''}</td>
            <td class="py-1 px-2">${j.instruktur}</td>
          </tr>
        `).join('')}
        </tbody>
      </table>`;
    } else {
      jadwalHTML += `<p class="text-dark/55 mb-4">Jadwal belum tersedia.</p>`;
    }

    let programs = classPrograms[key] || [];
    let serviceHTML = `<h4 class="font-semibold text-primary mb-2 text-lg">Pilihan Programs</h4>
      <ul class="mb-4 grid grid-cols-2 gap-2">
        ${programs.map(s => `
          <li class="bg-cream border-2 border-secondary rounded-full px-3 py-2 text-xs text-primary font-semibold text-center shadow-sm transition-all duration-200 hover:bg-secondary hover:text-white cursor-pointer" style="border-color:primary;">
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



      </div>
    </section>
  <!-- Modal Service Detail -->
<div 
  id="service-detail-modal"
  class="fixed inset-0 hidden z-50 bg-dark bg-opacity-60 items-center justify-center transition-opacity"
>
  <div 
    id="service-detail-box"
    class="bg-cream rounded-lg shadow-xl max-w-md w-full p-8 relative transform transition-all scale-95 opacity-0"
  >
    <button 
      onclick="closeServiceDetail()" 
      class="absolute top-2 right-2 text-dark/55 hover:text-primary text-2xl"
      aria-label="Close Modal"
    >
      &times;
    </button>

    <h3 id="service-detail-title" class="text-xl font-bold text-primary mb-4"></h3>

    <p id="service-detail-desc" class="text-dark leading-relaxed"></p>
  </div>
</div>

   <!-- Jadwal Kelas Exclusive Program - Premium & Elegant -->
<section id="schedule" class="relative overflow-hidden bg-[#FCF9F2]" style="padding: 100px 0 110px;">
    
    {{-- Glow boundary effects --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -right-48 w-96 h-96 bg-[#F4C9DF]/10 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute bottom-1/4 -left-48 w-96 h-96 bg-[#C5D79B]/8 rounded-full blur-3xl opacity-50"></div>
        
        {{-- Brand Ornament Signatures --}}
        <div class="brand-flower brand-flower-pink opacity-[0.06] absolute top-12 left-[10%] w-10 h-10 animate-pulse"></div>
        <div class="brand-asterisk opacity-[0.08] absolute bottom-16 right-[8%] w-8 h-8 animate-pulse" style="animation-delay: 1.5s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 max-w-5xl">
        
        <!-- Section Header -->
        <div class="text-center mb-12">
            <!-- Eyebrow label -->
            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-md backdrop-blur-sm">
                <span class="brand-flower brand-flower-pink" style="width: 16px; height: 16px;"></span>
                <span class="text-xs md:text-sm font-semibold tracking-[0.2em] text-[#EE4E8B] uppercase font-poppins">
                    WAKTU TERBAIK UNTUK SEHAT
                </span>
            </div>

            <!-- Main Heading -->
            <h2 class="text-4xl sm:text-5xl font-black mb-6 leading-tight text-dark font-nord">
                Jadwal Kelas<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-[#F472B6] to-primary font-instrument italic font-normal animate-gradient-shift" style="background-size: 200% auto; font-size: clamp(38px, 5.5vw, 64px);">Exclusive Program</span>
            </h2>

            <!-- Decorative Divider -->
            <div class="flex items-center justify-center gap-2.5 mt-4">
                <span class="w-16 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                <span class="w-3 h-3 bg-primary rounded-full animate-pulse"></span>
                <span class="w-8 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
            </div>
        </div>

        <!-- Table Container with Premium Glass Effect -->
        <div class="overflow-hidden rounded-3xl border border-white/60 bg-white/40 backdrop-blur-md shadow-[0_20px_50px_rgba(122,43,74,0.05)] p-2 md:p-4" data-aos="fade-up">
            <div class="overflow-x-auto rounded-2xl">
                <table class="min-w-[700px] w-full border-collapse text-left">
                    <thead>
                        <tr class="bg-gradient-to-r from-secondary to-primary text-white">
                            <th class="py-4 px-6 font-nord text-xs font-bold tracking-wider uppercase rounded-l-2xl">Class</th>
                            <th class="py-4 px-6 font-nord text-xs font-bold tracking-wider uppercase">Day</th>
                            <th class="py-4 px-6 font-nord text-xs font-bold tracking-wider uppercase">Date</th>
                            <th class="py-4 px-6 font-nord text-xs font-bold tracking-wider uppercase">Time</th>
                            <th class="py-4 px-6 font-nord text-xs font-bold tracking-wider uppercase rounded-r-2xl">Coach</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-light-pink/20">
                        @foreach($schedules as $day => $items)
                            @foreach($items->take(50) as $schedule)
                                <tr class="hover:bg-white/65 hover:shadow-[0_8px_30px_rgba(122,43,74,0.02)] transition-all duration-300 group">
                                    {{-- Class Name with pulse icon --}}
                                    <td class="py-4 px-6 font-medium text-dark">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300">
                                                <i class="ri-heart-pulse-fill text-base"></i>
                                            </div>
                                            <span class="font-bold font-poppins text-sm md:text-base tracking-tight">{{ $schedule->classModel->class_name ?? '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- Day --}}
                                    <td class="py-4 px-6 text-dark/70 font-poppins text-sm md:text-base">
                                        <div class="flex items-center gap-2">
                                            <i class="ri-calendar-line text-primary/80"></i>
                                            <span>{{ $schedule->day }}</span>
                                        </div>
                                    </td>

                                    {{-- Date or Routine Badge --}}
                                    <td class="py-4 px-6 font-poppins text-sm md:text-base">
                                        <div class="flex items-center gap-2">
                                            <i class="ri-calendar-event-line text-secondary/80"></i>
                                            <span>
                                                @if($schedule->schedule_date)
                                                    {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }}
                                                @else
                                                    <span class="text-[10px] uppercase tracking-wider text-secondary bg-secondary/10 px-2 py-0.5 rounded-md font-bold">Weekly Routine</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Time --}}
                                    <td class="py-4 px-6 font-poppins text-sm">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#7A2B4A]/5 border border-[#7A2B4A]/10 text-secondary font-semibold text-xs md:text-sm">
                                            <i class="ri-time-line"></i>
                                            <span>{{ \Carbon\Carbon::parse($schedule->class_time)->format('H:i') }} WIB</span>
                                        </div>
                                    </td>

                                    {{-- Coach --}}
                                    <td class="py-4 px-6 text-dark/80 font-poppins text-sm md:text-base">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-light-pink/40 flex items-center justify-center text-secondary text-xs">
                                                <i class="ri-user-smile-fill"></i>
                                            </div>
                                            <span class="font-medium">{{ $schedule->instructor ?? '-' }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</section>
{{-- ========================================= --}}
{{-- GALLERY SECTION - Selaras dengan Classes --}}
{{-- ========================================= --}}

<section id="Facility" class="relative overflow-hidden" style="padding: 100px 0 110px;">

    {{-- Multi-Layer Background --}}
    <div class="absolute inset-0 bg-gradient-to-b from-white via-light-pink/30 to-cream"></div>

    {{-- Subtle Grid Pattern --}}
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
         style="background-image:
            repeating-linear-gradient(0deg, transparent, transparent 50px, primary 50px, primary 51px),
            repeating-linear-gradient(90deg, transparent, transparent 50px, #7A2B4A 50px, #7A2B4A 51px);
            background-size: 100px 100px;">
    </div>

    {{-- Floating Gradient Orbs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-gradient-to-bl from-secondary/15 to-primary/15 rounded-full blur-3xl animate-pulse" style="animation-duration:7s;"></div>
        <div class="absolute top-1/2 -left-40 w-[500px] h-[500px] bg-gradient-to-tr from-primary/10 to-light-pink/10 rounded-full blur-3xl animate-pulse" style="animation-duration:9s; animation-delay:2s;"></div>
        <div class="absolute -bottom-20 right-1/3 w-80 h-80 bg-gradient-to-tl from-light-pink/15 to-secondary/15 rounded-full blur-3xl animate-pulse" style="animation-duration:8s; animation-delay:1s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- ── Section Header ── --}}
        <div class="text-center mb-16 md:mb-20">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-lg backdrop-blur-sm">
                <span class="brand-flower brand-flower-pink" style="width: 16px; height: 16px;"></span>
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
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-secondary via-primary to-secondary" style="background-size:200% auto; animation: gradientShift 4s ease infinite;">
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

            <p class="text-lg md:text-xl text-dark/70 max-w-2xl mx-auto leading-relaxed">
                Jelajahi fasilitas kelas dunia kami yang dirancang untuk menginspirasi, menantang, dan mengubah Anda.
            </p>
        </div>

        {{-- ── Slider ── --}}
        <div class="relative max-w-5xl mx-auto">

            {{-- Slider Card --}}
            <div id="facility-slider"
                 class="relative overflow-hidden rounded-3xl shadow-2xl border-2 border-light-pink/60"
                 style="aspect-ratio:16/9; background:#1C1C1C;">

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
                             alt="{{ $img['label'] }}"
                             class="w-full h-full object-cover"/>
                        {{-- Gradient overlay --}}
                        <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(38,40,43,0.6) 0%, rgba(38,40,43,0.1) 40%, transparent 70%);"></div>
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
                               background:linear-gradient(135deg,#7A2B4A,primary);
                               box-shadow:0 4px 20px rgba(238, 78, 139,0.5);
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
                               background:linear-gradient(135deg,primary,#7A2B4A);
                               box-shadow:0 4px 20px rgba(238, 78, 139,0.5);
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
                          style="background:linear-gradient(to right,primary,#7A2B4A);
                                 box-shadow:0 2px 12px rgba(238, 78, 139,0.5);
                                 letter-spacing:0.1em;">
                        01 / 10
                    </span>
                </div>

                {{-- Live Badge --}}
                <div class="absolute top-4 right-4 z-20">
                    <div class="flex items-center gap-1.5 rounded-full px-3 py-1"
                         style="background:rgba(255,255,255,0.15); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,0.25);">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background:primary;"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2" style="background:primary;"></span>
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
                         alt="{{ $img['label'] }}"
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

<section class="relative overflow-hidden" style="padding: 100px 0 110px;">

    <div class="absolute inset-0 bg-gradient-to-b from-cream via-light-pink/20 to-cream"></div>

    <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
         style="background-image:
            repeating-linear-gradient(0deg, transparent, transparent 50px, primary 50px, primary 51px),
            repeating-linear-gradient(90deg, transparent, transparent 50px, #7A2B4A 50px, #7A2B4A 51px);
            background-size: 100px 100px;">
    </div>

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-gradient-to-br from-secondary/10 to-light-pink/10 rounded-full blur-3xl animate-pulse" style="animation-duration:8s;"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-gradient-to-tl from-primary/10 to-light-pink/10 rounded-full blur-3xl animate-pulse" style="animation-duration:6s; animation-delay:1.5s;"></div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Header --}}
        <div class="text-center mb-14">

            <div class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 bg-gradient-to-r from-primary/10 via-secondary/10 to-primary/10 rounded-full border border-primary/20 shadow-lg backdrop-blur-sm">
                <span class="brand-flower brand-flower-pink" style="width: 16px; height: 16px;"></span>
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                </span>
                <span class="text-sm md:text-base font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-primary">
                    Trusted By
                </span>
            </div>

            <h2 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-secondary via-primary to-secondary" style="background-size:200% auto; animation: gradientShift 4s ease infinite;">
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

            <p class="text-lg md:text-xl text-dark/70 max-w-2xl mx-auto leading-relaxed">
                Didukung oleh merek-merek terkemuka di industri yang berkomitmen pada kesehatan, kesejahteraan, dan keunggulan.
            </p>
        </div>

        {{-- Scrolling Partner Strip with Drag --}}
        <div class="relative overflow-hidden py-3" id="partner-section">

            {{-- Fade edges --}}
            <div class="absolute left-0 top-0 bottom-0 w-28 z-10 pointer-events-none"
                 style="background:linear-gradient(to right,cream,transparent);"></div>
            <div class="absolute right-0 top-0 bottom-0 w-28 z-10 pointer-events-none"
                 style="background:linear-gradient(to left,cream,transparent);"></div>

            {{-- Track --}}
            <div id="partner-track" class="flex items-center cursor-grab active:cursor-grabbing select-none">

                @php $partners = ['partner 2..png','partner 3..png','partner 4..png','partner 5..png','partner 6..png','partner 1..png']; @endphp

                @foreach(array_merge($partners, $partners) as $p)
                <div class="group relative flex-shrink-0 mx-4 px-7 py-5 rounded-3xl
                            bg-cream border-2 border-light-pink/60
                            shadow-lg transition-shadow duration-300 hover:shadow-2xl hover:border-secondary/30"
                     style="cursor:default; pointer-events:none;">
                    <div class="absolute inset-0 bg-gradient-to-br from-light-pink/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-3xl pointer-events-none"></div>
                    <img src="icons/{{ $p }}"
                         class="relative z-10 h-14 object-contain transition-transform duration-300 group-hover:scale-110"
                         style="min-width:80px;" />
                    <div class="h-0.5 w-0 group-hover:w-full rounded-full transition-all duration-500 mt-3"
                         style="background:linear-gradient(to right,primary,#7A2B4A,primary);"></div>
                </div>
                @endforeach

            </div>
        </div>

    </div>
</section>


{{-- ========================================= --}}
{{-- STYLES                                    --}}
{{-- ========================================= --}}
<style>
    /* Gradient title animation */
    @keyframes gradientShift {
        0%,100% { background-position: 0% 50%; }
        50%      { background-position: 100% 50%; }
    }

    /* Partner drag overrides */
    #partner-track {
        will-change: transform;
        user-select: none;
        -webkit-user-select: none;
    }
    #partner-track.dragging {
        cursor: grabbing !important;
    }

    /* Dot indicator */
    .facility-dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        background: light-pink;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.35s ease;
        flex-shrink: 0;
    }
    .facility-dot.active {
        width: 28px;
        background: primary;
    }

    /* Hide scrollbar on thumb strip */
    #facility-slider + div + div::-webkit-scrollbar { display:none; }
</style>


{{-- ========================================= --}}
{{-- SLIDER SCRIPT                             --}}
{{-- ========================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const TOTAL   = 10;
    const track   = document.getElementById('facility-track');
    const counter = document.getElementById('facility-counter');
    const dotsWrap= document.getElementById('facility-dots');
    const thumbs  = document.querySelectorAll('.facility-thumb');
    const btnPrev = document.getElementById('facility-prev');
    const btnNext = document.getElementById('facility-next');

    let idx   = 0;
    let timer = null;

    // ── Buat dots ──
    const dots = [];
    for (let i = 0; i < TOTAL; i++) {
        const btn = document.createElement('button');
        btn.type      = 'button';
        btn.className = 'facility-dot' + (i === 0 ? ' active' : '');
        btn.addEventListener('click', () => { idx = i; render(); resetTimer(); });
        dotsWrap.appendChild(btn);
        dots.push(btn);
    }

    // ── Render state ──
    function render() {
        // Track
        track.style.transform = `translateX(-${idx * 10}%)`;

        // Counter
        counter.textContent = `${String(idx + 1).padStart(2,'0')} / ${String(TOTAL).padStart(2,'0')}`;

        // Dots
        dots.forEach((d, i) => {
            d.classList.toggle('active', i === idx);
        });

        // Thumbnails
        thumbs.forEach((t, i) => {
            const active = i === idx;
            t.style.opacity     = active ? '1'         : '0.45';
            t.style.borderColor = active ? 'primary'   : 'transparent';
            t.style.transform   = active ? 'scale(1.07)' : 'scale(1)';
        });
    }

    // ── Navigation ──
    function next() { idx = (idx + 1) % TOTAL; render(); resetTimer(); }
    function prev() { idx = (idx - 1 + TOTAL) % TOTAL; render(); resetTimer(); }

    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(next, 4500);
    }

    // ── Button Events ──
    btnNext.addEventListener('click', next);
    btnPrev.addEventListener('click', prev);

    // ── Thumbnail Events ──
    thumbs.forEach(t => {
        t.addEventListener('click', () => {
            idx = parseInt(t.dataset.index);
            render();
            resetTimer();
        });
    });

    // ── Pause on hover ──
    const sliderEl = document.getElementById('facility-slider');
    sliderEl.addEventListener('mouseenter', () => clearInterval(timer));
    sliderEl.addEventListener('mouseleave', () => resetTimer());

    // ── Touch swipe ──
    let startX = 0;
    sliderEl.addEventListener('touchstart', e => startX = e.touches[0].clientX, { passive:true });
    sliderEl.addEventListener('touchend', e => {
        const diff = startX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) diff > 0 ? next() : prev();
    });

    // ── Init ──
    render();
    resetTimer();
});
</script>

{{-- ========================================= --}}
{{-- PARTNER CAROUSEL SCRIPT                   --}}
{{-- ========================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const track      = document.getElementById('partner-track');
    if (!track) return;

    const speed      = 0.5; // px per frame at 60fps
    let pos          = 0;
    let isDragging   = false;
    let startX       = 0;
    let dragPos      = 0;
    let animId       = null;
    let isPaused     = false;

    // Duplicate content if not already duplicated (ensure seamless loop)
    // The track already has 2x items via array_merge in Blade, so offset is totalWidth/2
    const totalWidth = () => track.scrollWidth / 2;

    // ── Auto scroll ──
    function autoScroll() {
        if (isPaused) return;
        pos -= speed;
        const half = totalWidth();
        if (pos <= -half) pos += half;
        track.style.transform = `translateX(${pos}px)`;
        animId = requestAnimationFrame(autoScroll);
    }

    function startAuto() {
        if (animId) cancelAnimationFrame(animId);
        isPaused = false;
        animId = requestAnimationFrame(autoScroll);
    }

    function stopAuto() {
        isPaused = true;
        if (animId) {
            cancelAnimationFrame(animId);
            animId = null;
        }
    }

    // ── Drag ──
    function onDown(e) {
        stopAuto();
        isDragging = true;
        startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
        dragPos = pos;
        track.classList.add('dragging');
    }

    function onMove(e) {
        if (!isDragging) return;
        const x = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
        const dx = x - startX;
        pos = dragPos + dx;
        const half = totalWidth();
        if (pos <= -half) pos += half;
        if (pos > 0) pos -= half;
        track.style.transform = `translateX(${pos}px)`;
    }

    function onUp() {
        if (!isDragging) return;
        isDragging = false;
        track.classList.remove('dragging');
        startAuto();
    }

    // ── Mouse events ──
    track.addEventListener('mousedown', onDown);
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);

    // ── Touch events ──
    track.addEventListener('touchstart', onDown, { passive: true });
    window.addEventListener('touchmove', onMove, { passive: true });
    window.addEventListener('touchend', onUp);

    // ── Pause on hover ──
    track.addEventListener('mouseenter', stopAuto);
    track.addEventListener('mouseleave', startAuto);

    // ── Init ──
    startAuto();
});
</script>

    <!-- Contact Section -->
    <section id="contact" class="relative overflow-hidden py-24 md:py-32 bg-white">
        <!-- Desktop Building Hero Image (Background) with Organic Curved Mask SVG -->
        <div class="absolute top-0 right-0 w-[60%] h-[600px] z-0 hidden lg:block overflow-hidden">
            <img src="{{ asset('images/building_ftm.png') }}" class="w-full h-full object-cover" style="object-position: center 82%;" alt="FTM Society Building" />
            <!-- Organic S-curve White Separator -->
            <svg class="absolute inset-0 w-full h-full pointer-events-none z-10" viewBox="0 0 1440 1200" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <filter id="wave-shadow" x="-20%" y="-20%" width="140%" height="140%">
                  <feDropShadow dx="-6" dy="2" stdDeviation="15" flood-color="#ff2d75" flood-opacity="0.015" />
                </filter>
              </defs>
              <path d="M 0 0 L 220 0 C 20 350, 380 850, 160 1200 L 0 1200 Z" fill="#ffffff" filter="url(#wave-shadow)" />
            </svg>
            <!-- Bottom fade to white -->
            <div class="absolute bottom-0 left-0 right-0 h-[150px] bg-gradient-to-t from-white via-white/95 to-transparent z-20"></div>
        </div>
        
        <!-- Mobile/Tablet decorative blur blobs -->
        <div class="absolute top-1/4 left-10 w-72 h-72 rounded-full bg-[#ff2d75]/[0.015] blur-3xl pointer-events-none z-0 lg:hidden"></div>
        <div class="absolute bottom-1/3 right-10 w-96 h-96 rounded-full bg-[#ff5d9e]/[0.02] blur-3xl pointer-events-none z-0 lg:hidden"></div>

        <div class="container mx-auto px-6 sm:px-8 lg:px-12 relative z-20">
            <div class="flex flex-col lg:flex-row gap-16 lg:gap-20">
                <!-- Left Column: Info & Maps (w-full lg:w-[48%]) -->
                <div class="w-full lg:w-[48%] space-y-12">
                    <div class="space-y-4">
                        <span class="text-[#ff2d75] font-semibold text-xs tracking-[0.25em] uppercase block">
                            KONTAK KAMI
                        </span>
                        <h2 class="text-[#111827] font-instrument text-5xl lg:text-[72px] leading-[1.02] tracking-tight font-light">
                            Kami Siap<br/>
                            <span class="text-[#ff2d75] italic">Mendengar & Membantu</span>
                        </h2>
                        <p class="text-[#6b7280] text-sm md:text-base leading-relaxed max-w-[520px] pt-2 font-poppins">
                            Punya pertanyaan, ingin berkolaborasi, atau butuh informasi lebih lanjut? Jangan ragu untuk menghubungi kami. Tim kami akan dengan senang hati membantu Anda.
                        </p>
                    </div>

                    <div class="space-y-8 font-poppins">
                        <!-- Address -->
                        <div class="flex items-start gap-6">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-white shadow-[0_8px_30px_rgba(255,45,117,0.06)] border border-[#ececec]/60 text-[#ff2d75] flex-shrink-0">
                                <i class="ri-map-pin-line text-xl"></i>
                            </div>
                            <div class="space-y-1 mt-1">
                                <h4 class="font-bold text-sm text-[#111827] uppercase tracking-wider">Alamat</h4>
                                <p class="text-[#6b7280] text-sm leading-relaxed">
                            <strong class="text-[#111827]">Jakarta Selatan:</strong><br />
                            Jl. Wijaya 8 No.2, RT.6/RW.7, Melawai,<br />
                            Kec. Kby. Baru, Kota Jakarta Selatan,<br />
                            Daerah Khusus Ibukota Jakarta 12160
                        </p>
                        <p class="text-[#6b7280] text-sm leading-relaxed mt-2">
                            <strong class="text-[#111827]">Jakarta Pusat:</strong><br />
                            Jl. Cempaka Putih Tengah XIII No.56, RT.4/RW.6,<br />
                            Cemp. Putih Tim., Kec. Cemp. Putih,<br />
                            Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta 10510
                        </p>
                            </div>
                        </div>
                        
                        <!-- Email -->
                        <div class="flex items-start gap-6">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-white shadow-[0_8px_30px_rgba(255,45,117,0.06)] border border-[#ececec]/60 text-[#ff2d75] flex-shrink-0">
                                <i class="ri-mail-line text-xl"></i>
                            </div>
                            <div class="space-y-1 mt-1">
                                <h4 class="font-bold text-sm text-[#111827] uppercase tracking-wider">Email</h4>
                                <p class="text-[#6b7280] text-sm">ftmsociety@gmail.com</p>
                            </div>
                        </div>
                        
                        <!-- Telepon / WA -->
                        <div class="flex items-start gap-6">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-white shadow-[0_8px_30px_rgba(255,45,117,0.06)] border border-[#ececec]/60 text-[#ff2d75] flex-shrink-0">
                                <i class="ri-phone-line text-xl"></i>
                            </div>
                            <div class="space-y-1 mt-1">
                                <h4 class="font-bold text-sm text-[#111827] uppercase tracking-wider">Telepon / WhatsApp</h4>
                                <p class="text-[#6b7280] text-sm">+62 877-8576-7395</p>
                                <a href="https://wa.me/6287785767395" target="_blank" class="inline-flex items-center text-[#ff2d75] text-sm hover:text-[#ff5d9e] transition-colors gap-1">
                                    <i class="ri-whatsapp-line"></i> Message us on WhatsApp
                                </a>
                            </div>
                        </div>
                        
                        <!-- Jam Operasional -->
                        <div class="flex items-start gap-6">
                            <div class="w-14 h-14 rounded-full flex items-center justify-center bg-white shadow-[0_8px_30px_rgba(255,45,117,0.06)] border border-[#ececec]/60 text-[#ff2d75] flex-shrink-0">
                                <i class="ri-time-line text-xl"></i>
                            </div>
                            <div class="space-y-1 mt-1">
                                <h4 class="font-bold text-sm text-[#111827] uppercase tracking-wider">Jam Operasional</h4>
                                <div class="text-[#6b7280] text-sm leading-relaxed">
                                    <p>Senin - Sabtu: 08.00 - 20.00 WIB</p>
                                    <p>Minggu: 08.00 - 15.00 WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Maps Component -->
                    <div class="relative w-full h-[320px] rounded-[32px] overflow-hidden shadow-[0_15px_40px_rgba(0,0,0,0.03)] border border-[#ececec] group">
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
                        
                        <!-- Floating Map Card -->
                        <div class="absolute top-4 left-4 bg-white rounded-2xl p-4 shadow-[0_12px_36px_rgba(0,0,0,0.06)] border border-[#ececec]/80 max-w-[280px] z-10 flex flex-col gap-2.5 transition-all duration-300">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center flex-shrink-0 mt-0.5 overflow-hidden border border-[#ececec]">
                                    <img src="{{ asset('images/LOGOGRAM PINK.png') }}" class="w-full h-full object-cover" alt="FTM Society Logo">
                                </div>
                                <div class="font-poppins">
                                    <h5 class="font-bold text-xs text-[#111827]">FTM Society</h5>
                                    <p class="text-[10px] text-[#6b7280] leading-normal mt-0.5 whitespace-pre-line">Jl. Wijaya 8 No.2, RT.6/RW.7, Melawai
Kec. Kby. Baru, Jakarta Selatan 12160</p>
                                </div>
                            </div>
                            <a href="https://maps.google.com/?q=FTM+Society+Jakarta+Selatan" target="_blank" class="text-[11px] text-[#ff2d75] font-bold hover:text-[#ff5d9e] flex items-center gap-1 transition-colors">
                                Lihat di Google Maps <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>

                        <!-- Absolute Map Pin in Center to Replicate Mockup -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="relative -top-4">
                                <i class="ri-map-pin-2-fill text-[#ff2d75] text-4xl drop-shadow-[0_4px_10px_rgba(255,45,117,0.35)] animate-bounce" style="animation-duration: 2s;"></i>
                                <div class="w-2.5 h-1 bg-black/15 rounded-full blur-[1px] mx-auto mt-0.5 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End Left Column -->

                <!-- Right Column: Floating Contact Form (w-full lg:w-[52%]) -->
                <div class="w-full lg:w-[52%] relative">
                    <!-- Mobile Building Image (shown only on mobile/tablet) -->
                    <div class="w-full rounded-[40px] overflow-hidden shadow-lg h-[360px] relative lg:hidden mb-8">
                        <img src="{{ asset('images/building_ftm.png') }}" class="w-full h-full object-cover" style="object-position: center 82%;" alt="FTM Society Building" />
                        <div class="absolute bottom-0 left-0 right-0 h-[100px] bg-gradient-to-t from-white to-transparent z-10"></div>
                    </div>
                    
                    <!-- Floating Contact Form Card -->
                    <div class="relative z-10 bg-white rounded-[36px] shadow-[0_25px_60px_rgba(0,0,0,0.03),0_15px_40px_rgba(255,45,117,0.015)] border border-[#ececec]/80 p-8 sm:p-10 lg:p-12 lg:mt-[180px]">
                        <!-- Email icon badge centered on top border -->
                        <div class="w-14 h-14 rounded-full bg-[#ff2d75]/10 flex items-center justify-center mx-auto -mt-16 sm:-mt-[60px] mb-6 shadow-[0_8px_20px_rgba(255,45,117,0.15)] border-4 border-white">
                            <i class="ri-mail-fill text-[#ff2d75] text-xl"></i>
                        </div>
                        
                        <h3 class="text-3xl font-medium font-instrument text-[#111827] text-center leading-tight">
                            Kirim Pesan untuk Kami
                        </h3>
                        <p class="text-sm text-[#6b7280] text-center mt-2 max-w-sm mx-auto leading-relaxed font-poppins">
                            Isi formulir di bawah ini dan tim kami akan segera merespons pesan Anda.
                        </p>
                        
                        <!-- Flash Message Alert -->
                        @if (session('success'))
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-start gap-3 shadow-[0_4px_12px_rgba(0,0,0,0.01)] animate-fadeIn">
                            <i class="ri-checkbox-circle-fill text-xl text-green-500 mt-0.5"></i>
                            <div class="font-poppins">
                                <h5 class="font-bold text-sm">Berhasil!</h5>
                                <p class="text-xs text-green-600 mt-0.5">{{ session('success') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <form class="space-y-5 mt-8 font-poppins" method="POST" action="{{ route('feedback.store') }}">
                            @csrf
                            
                            <!-- Nama Lengkap -->
                            <div class="space-y-2">
                                <label for="contact-name" class="block text-xs font-bold text-[#111827] uppercase tracking-wider pl-1">Nama Lengkap</label>
                                <input
                                    type="text"
                                    id="contact-name"
                                    name="name"
                                    class="w-full h-14 px-6 bg-white border border-[#ececec] rounded-[18px] text-sm text-[#111827] placeholder-[#a3a3a3] focus:border-[#ff2d75] focus:ring-4 focus:ring-[#ff2d75]/5 focus:shadow-[0_8px_25px_rgba(255,45,117,0.05)] focus:outline-none transition-all duration-300"
                                    placeholder="Masukkan nama lengkap Anda"
                                    required
                                />
                            </div>
                            
                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="contact-email" class="block text-xs font-bold text-[#111827] uppercase tracking-wider pl-1">Email</label>
                                <input
                                    type="email"
                                    id="contact-email"
                                    name="email"
                                    class="w-full h-14 px-6 bg-white border border-[#ececec] rounded-[18px] text-sm text-[#111827] placeholder-[#a3a3a3] focus:border-[#ff2d75] focus:ring-4 focus:ring-[#ff2d75]/5 focus:shadow-[0_8px_25px_rgba(255,45,117,0.05)] focus:outline-none transition-all duration-300"
                                    placeholder="Masukkan email aktif Anda"
                                    required
                                />
                            </div>
                            
                            <!-- Subjek -->
                            <div class="space-y-2">
                                <label for="subject" class="block text-xs font-bold text-[#111827] uppercase tracking-wider pl-1">Subjek</label>
                                <div class="relative">
                                    <select
                                        id="subject"
                                        name="subject"
                                        class="w-full h-14 px-6 bg-white border border-[#ececec] rounded-[18px] text-sm text-[#111827] focus:border-[#ff2d75] focus:ring-4 focus:ring-[#ff2d75]/5 focus:shadow-[0_8px_25px_rgba(255,45,117,0.05)] focus:outline-none transition-all duration-300 appearance-none cursor-pointer"
                                        required
                                    >
                                        <option value="" selected disabled>Pilih subjek pesan</option>
                                        <option value="membership">Membership Inquiry</option>
                                        <option value="classes">Class Information</option>
                                        <option value="trial">Free Trial</option>
                                        <option value="feedback">Feedback</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-6 pointer-events-none text-[#6b7280]">
                                        <i class="ri-arrow-down-s-line text-lg"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pesan -->
                            <div class="space-y-2">
                                <label for="message" class="block text-xs font-bold text-[#111827] uppercase tracking-wider pl-1">Pesan</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    class="w-full h-[180px] py-4 px-6 bg-white border border-[#ececec] rounded-[18px] text-sm text-[#111827] placeholder-[#a3a3a3] focus:border-[#ff2d75] focus:ring-4 focus:ring-[#ff2d75]/5 focus:shadow-[0_8px_25px_rgba(255,45,117,0.05)] focus:outline-none transition-all duration-300 resize-none"
                                    placeholder="Tulis pesan Anda di sini..."
                                    required
                                ></textarea>
                            </div>
                            
                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full h-14 bg-gradient-to-r from-[#ff2d75] to-[#ff5d9e] text-white font-bold rounded-full hover:shadow-[0_12px_30px_rgba(255,45,117,0.3)] hover:brightness-110 active:scale-[0.98] transition-all duration-300 flex items-center justify-center gap-2 group mt-2"
                            >
                                <span>Kirim Pesan</span>
                                <i class="ri-arrow-right-line text-lg transition-transform duration-300 group-hover:translate-x-1"></i>
                            </button>
                        </form>
                    </div>
                </div> <!-- End Right Column -->
            </div> <!-- End Columns Grid -->

            <!-- Bottom Section: Social Media & FAQ Cards -->
            <div class="mt-32 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
                <!-- Left Card: Social Media -->
                <div class="bg-white rounded-[32px] shadow-[0_15px_40px_rgba(0,0,0,0.02)] border border-[#ececec]/60 p-8 sm:p-10 lg:p-12 space-y-6">
                    <div>
                        <h3 class="text-3xl font-medium font-instrument text-[#111827]">
                            Terhubung Bersama Kami
                        </h3>
                        <p class="text-sm text-[#6b7280] mt-2 leading-relaxed font-poppins">
                            Ikuti kami di media sosial untuk mendapatkan update terbaru, inspirasi, dan kegiatan menarik.
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 font-poppins pt-2">
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/ftmsociety.id" target="_blank" class="flex items-center justify-between p-4 bg-white rounded-2xl border border-[#ececec] hover:shadow-[0_8px_25px_rgba(255,45,117,0.04)] hover:-translate-y-0.5 hover:border-[#ff2d75]/20 transition-all duration-300 group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gradient-to-tr from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] text-white">
                                    <i class="ri-instagram-line text-lg"></i>
                                </div>
                                <span class="font-semibold text-xs text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-300">@ftmsociety.id</span>
                            </div>
                            <i class="ri-arrow-right-line text-[#6b7280] group-hover:text-[#ff2d75] group-hover:translate-x-0.5 transition-all duration-300 text-sm"></i>
                        </a>
                        
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/share/129JRu5DDXa/" target="_blank" class="flex items-center justify-between p-4 bg-white rounded-2xl border border-[#ececec] hover:shadow-[0_8px_25px_rgba(255,45,117,0.04)] hover:-translate-y-0.5 hover:border-[#ff2d75]/20 transition-all duration-300 group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#1877f2] text-white">
                                    <i class="ri-facebook-fill text-lg"></i>
                                </div>
                                <span class="font-semibold text-xs text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-300">FTM Society</span>
                            </div>
                            <i class="ri-arrow-right-line text-[#6b7280] group-hover:text-[#ff2d75] group-hover:translate-x-0.5 transition-all duration-300 text-sm"></i>
                        </a>
                        
                        <!-- TikTok -->
                        <a href="https://www.tiktok.com/@ftm.society" target="_blank" class="flex items-center justify-between p-4 bg-white rounded-2xl border border-[#ececec] hover:shadow-[0_8px_25px_rgba(255,45,117,0.04)] hover:-translate-y-0.5 hover:border-[#ff2d75]/20 transition-all duration-300 group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#111827] text-white">
                                    <i class="ri-tiktok-line text-lg"></i>
                                </div>
                                <span class="font-semibold text-xs text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-300">@ftm.society</span>
                            </div>
                            <i class="ri-arrow-right-line text-[#6b7280] group-hover:text-[#ff2d75] group-hover:translate-x-0.5 transition-all duration-300 text-sm"></i>
                        </a>
                        
                        <!-- YouTube -->
                        <a href="https://www.youtube.com/@ftmsociety" target="_blank" class="flex items-center justify-between p-4 bg-white rounded-2xl border border-[#ececec] hover:shadow-[0_8px_25px_rgba(255,45,117,0.04)] hover:-translate-y-0.5 hover:border-[#ff2d75]/20 transition-all duration-300 group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#ff0000] text-white">
                                    <i class="ri-youtube-fill text-lg"></i>
                                </div>
                                <span class="font-semibold text-xs text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-300">FTM Society</span>
                            </div>
                            <i class="ri-arrow-right-line text-[#6b7280] group-hover:text-[#ff2d75] group-hover:translate-x-0.5 transition-all duration-300 text-sm"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Right Card: FAQ -->
                <div class="bg-white rounded-[32px] shadow-[0_15px_40px_rgba(0,0,0,0.02)] border border-[#ececec]/60 p-8 sm:p-10 lg:p-12 space-y-6">
                    <div>
                        <h3 class="text-3xl font-medium font-instrument text-[#111827]">
                            Pertanyaan yang Sering Diajukan
                        </h3>
                        
                        <div class="space-y-3 mt-6 font-poppins">
                            <!-- FAQ 1 -->
                            <div class="faq-item bg-white border border-[#ececec] rounded-[18px] overflow-hidden transition-all duration-300 shadow-[0_4px_12px_rgba(0,0,0,0.01)]">
                                <button class="faq-toggle w-full h-[60px] md:h-[64px] flex justify-between items-center text-left px-6 focus:outline-none group">
                                    <span class="font-semibold text-xs md:text-sm text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-200">Bagaimana cara bergabung dengan program di FTM Society?</span>
                                    <span class="faq-icon text-[#ff2d75] font-light text-2xl pl-4 transition-transform duration-300 select-none">+</span>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out px-6">
                                    <p class="text-xs md:text-sm text-[#6b7280] pb-4 leading-relaxed">
                                        Anda dapat bergabung dengan mengisi formulir pendaftaran online di halaman <a href="{{ route('join') }}" class="text-primary hover:text-secondary underline underline-offset-2">Daftar Sekarang</a>. Setelah mendaftar, Anda akan menerima kode OTP via WhatsApp untuk verifikasi nomor Anda. Atau Anda dapat menghubungi WhatsApp customer service kami untuk dibantu proses registrasinya.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- FAQ 2 -->
                            <div class="faq-item bg-white border border-[#ececec] rounded-[18px] overflow-hidden transition-all duration-300 shadow-[0_4px_12px_rgba(0,0,0,0.01)]">
                                <button class="faq-toggle w-full h-[60px] md:h-[64px] flex justify-between items-center text-left px-6 focus:outline-none group">
                                    <span class="font-semibold text-xs md:text-sm text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-200">Apakah program di FTM Society berbayar?</span>
                                    <span class="faq-icon text-[#ff2d75] font-light text-2xl pl-4 transition-transform duration-300 select-none">+</span>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out px-6">
                                    <p class="text-xs md:text-sm text-[#6b7280] pb-4 leading-relaxed">
                                        Ya, kami menyediakan berbagai program membership dan kelas berbayar. Namun, kami juga sering mengadakan program gratis atau berdonasi untuk komunitas.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- FAQ 3 -->
                            <div class="faq-item bg-white border border-[#ececec] rounded-[18px] overflow-hidden transition-all duration-300 shadow-[0_4px_12px_rgba(0,0,0,0.01)]">
                                <button class="faq-toggle w-full h-[60px] md:h-[64px] flex justify-between items-center text-left px-6 focus:outline-none group">
                                    <span class="font-semibold text-xs md:text-sm text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-200">Bagaimana jika saya ingin berkolaborasi atau menjadi donatur?</span>
                                    <span class="faq-icon text-[#ff2d75] font-light text-2xl pl-4 transition-transform duration-300 select-none">+</span>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out px-6">
                                    <p class="text-xs md:text-sm text-[#6b7280] pb-4 leading-relaxed">
                                        Kami sangat terbuka untuk kolaborasi dan donasi. Anda dapat menghubungi kami melalui formulir kontak di sebelah kanan atau langsung mengirim pesan ke email halo@ftmsociety.id.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- FAQ 4 -->
                            <div class="faq-item bg-white border border-[#ececec] rounded-[18px] overflow-hidden transition-all duration-300 shadow-[0_4px_12px_rgba(0,0,0,0.01)]">
                                <button class="faq-toggle w-full h-[60px] md:h-[64px] flex justify-between items-center text-left px-6 focus:outline-none group">
                                    <span class="font-semibold text-xs md:text-sm text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-200">Di mana lokasi kegiatan FTM Society?</span>
                                    <span class="faq-icon text-[#ff2d75] font-light text-2xl pl-4 transition-transform duration-300 select-none">+</span>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out px-6">
                                    <p class="text-xs md:text-sm text-[#6b7280] pb-4 leading-relaxed">
                                        Kegiatan kami berpusat di cabang Jakarta Selatan (Melawai, Kebayoran Baru) dan Jakarta Pusat (Cempaka Putih). Detail alamat lengkap dapat dilihat pada informasi kontak kami.
                                    </p>
                                </div>
                            </div>
                            
                            <!-- FAQ 5 -->
                            <div class="faq-item bg-white border border-[#ececec] rounded-[18px] overflow-hidden transition-all duration-300 shadow-[0_4px_12px_rgba(0,0,0,0.01)]">
                                <button class="faq-toggle w-full h-[60px] md:h-[64px] flex justify-between items-center text-left px-6 focus:outline-none group">
                                    <span class="font-semibold text-xs md:text-sm text-[#111827] group-hover:text-[#ff2d75] transition-colors duration-200">Bagaimana cara mendapatkan informasi terbaru?</span>
                                    <span class="faq-icon text-[#ff2d75] font-light text-2xl pl-4 transition-transform duration-300 select-none">+</span>
                                </button>
                                <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out px-6">
                                    <p class="text-xs md:text-sm text-[#6b7280] pb-4 leading-relaxed">
                                        Ikuti akun Instagram kami @ftmsociety dan berlangganan newsletter di bagian bawah halaman ini untuk mendapatkan informasi ter-update secara berkala.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-start pt-4 font-poppins">
                        <a href="#faq" class="px-8 py-3.5 border border-[#ff2d75] text-[#ff2d75] hover:bg-[#ff2d75]/5 text-sm font-bold rounded-full transition-all duration-300 flex items-center justify-center gap-2 group">
                            <span>Lihat Semua FAQ</span>
                            <i class="ri-arrow-right-line transition-transform duration-300 group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Accordion Toggle JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = document.querySelectorAll('.faq-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const content = this.nextElementSibling;
                const icon = this.querySelector('.faq-icon');
                
                // Close other items
                toggles.forEach(other => {
                    if (other !== toggle && other.classList.contains('active')) {
                        other.classList.remove('active');
                        other.nextElementSibling.style.maxHeight = '0px';
                        other.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
                    }
                });
                
                this.classList.toggle('active');
                if (this.classList.contains('active')) {
                    content.style.maxHeight = content.scrollHeight + 'px';
                    icon.style.transform = 'rotate(45deg)';
                } else {
                    content.style.maxHeight = '0px';
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });
    });
    </script>


<footer class="relative bg-gradient-to-br from-burnt-cherry via-secondary to-burnt-cherry text-white pt-16 pb-8 overflow-hidden">

  <!-- Footer decorative ornaments -->
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="brand-flower brand-flower-ivory absolute top-10 right-[8%] opacity-10" style="width: 120px; height: 120px;"></div>
    <div class="brand-asterisk brand-asterisk-ivory absolute bottom-20 left-[5%] opacity-10" style="width: 80px; height: 80px;"></div>
    <div class="brand-flower brand-flower-pink absolute -bottom-10 right-1/4 opacity-15" style="width: 160px; height: 160px;"></div>
  </div>

  <div class="container mx-auto px-4 relative z-10">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

      <!-- Column 1: Tentang Brand -->
      <div>
        <h4 class="text-lg font-semibold mb-6 text-white">Tentang FTM Society</h4>
        <p class="text-white text-opacity-80 text-sm leading-relaxed">
          FTM Society adalah pusat kebugaran eksklusif untuk muslimah yang mengedepankan kenyamanan, privasi, dan pendekatan islami. Bergabunglah dengan komunitas wanita kuat dan sehat yang saling mendukung dalam perjalanan hidup sehat dan seimbang.
        </p>
      </div>

      <!-- Column 2: Link Navigasi -->
      <div>
        <h4 class="text-lg font-semibold mb-6 text-white">Navigasi Cepat</h4>
        <ul class="space-y-3">
          <li><a href="#home" class="footer-link">Beranda</a></li>
          <li><a href="#about" class="footer-link">Tentang Kami</a></li>
          <li><a href="#classes" class="footer-link">Kelas</a></li>
          <li><a href="#Programs" class="footer-link">Program</a></li>
          <li><a href="#schedule" class="footer-link">Jadwal</a></li>
          <li><a href="#Facility" class="footer-link">Fasilitas</a></li>
          <li><a href="#contact" class="footer-link">Hubungi Kami</a></li>
        </ul>
      </div>

      <!-- Column 3: Program Unggulan -->
      <div>
        <h4 class="text-lg font-semibold mb-6 text-white">Program Unggulan</h4>
        <ul class="space-y-3">
          <li><a href="#Programs" class="footer-link">Private Group Class</a></li>
          <li><a href="#Programs" class="footer-link">Private Training</a></li>
          <li><a href="#Programs" class="footer-link">Single Visit Class</a></li>
          <li><a href="#Programs" class="footer-link">Reformer Pilates</a></li>
          <li><a href="#Programs" class="footer-link">Exclusive Class Program</a></li>
        </ul>
      </div>

      <!-- Column 4: Newsletter & Komunitas -->
      <div>
        <h4 class="text-lg font-semibold mb-6 text-white">Gabung Komunitas Kami</h4>
        <p class="text-white text-opacity-80 mb-4">
          Dapatkan update eksklusif, promo menarik, dan tips kebugaran langsung ke email Anda.
        </p>
        <form class="mb-4">
          <div class="flex">
            <input
              type="email"
              placeholder="Alamat email Anda"
              class="px-4 py-2 rounded-l text-dark w-full border-none"
            />
            <button
              type="submit"
              class="bg-power-pink text-white px-4 py-2 rounded-r hover:bg-patina-green transition-all whitespace-nowrap font-semibold"
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
          <a href="#" class="footer-link text-sm">Kebijakan Privasi</a>
          <a href="#" class="footer-link text-sm">Syarat & Ketentuan</a>
          <a href="#" class="footer-link text-sm">Kontak Bantuan</a>
        </div>
      </div>
    </div>
  </div>
</footer>

  {{-- ========================================================== --}}
  {{-- FTM SOCIETY — WhatsApp Floating Widget (draggable + form)   --}}
  {{-- ========================================================== --}}
  <div id="ftm-wa-widget" class="ftm-wa-widget"
       data-wa-number="6287785767395"
       aria-label="WhatsApp FTM Society">

    {{-- Floating Trigger Button (collapsed state) --}}
    <button type="button"
            id="ftm-wa-trigger"
            class="ftm-wa-trigger"
            aria-label="Hubungi FTM Society via WhatsApp">
      <span class="ftm-wa-pulse" aria-hidden="true"></span>
      <svg class="ftm-wa-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
      </svg>
    </button>

    {{-- Card Panel (open state) --}}
    <div id="ftm-wa-card" class="ftm-wa-card" role="dialog" aria-labelledby="ftm-wa-title" aria-hidden="true">

      {{-- Drag handle / Header --}}
      <div class="ftm-wa-header" id="ftm-wa-handle">
        <div class="ftm-wa-header-content">
          <div class="ftm-wa-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
            </svg>
          </div>
          <div class="ftm-wa-header-text">
            <p class="ftm-wa-title" id="ftm-wa-title">Halo, ada yang bisa kami bantu?</p>
            <p class="ftm-wa-subtitle">
              <span class="ftm-wa-online-dot"></span>
              Tim FTM biasanya membalas dalam beberapa menit
            </p>
          </div>
        </div>
        <button type="button" id="ftm-wa-close" class="ftm-wa-close" aria-label="Tutup">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
          </svg>
        </button>
      </div>

      {{-- Body --}}
      <div class="ftm-wa-body">
        <div class="ftm-wa-greeting">
          <p>Hai, <strong>FTM Society</strong> di sini.</p>
          <p>Tulis nama dan pertanyaanmu &mdash; kami akan respons via WhatsApp secepatnya.</p>
        </div>

        <form id="ftm-wa-form" class="ftm-wa-form" autocomplete="off">
          <div class="ftm-wa-field">
            <label for="ftm-wa-name">Nama</label>
            <input type="text"
                   id="ftm-wa-name"
                   name="name"
                   placeholder="Nama lengkap kamu"
                   required
                   maxlength="80">
          </div>
          <div class="ftm-wa-field">
            <label for="ftm-wa-message">Pertanyaan</label>
            <textarea id="ftm-wa-message"
                      name="message"
                      rows="4"
                      placeholder="Tulis pertanyaan kamu di sini..."
                      required
                      maxlength="500"></textarea>
            <span class="ftm-wa-counter" id="ftm-wa-counter">0 / 500</span>
          </div>

          <button type="submit" class="ftm-wa-submit">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.226 1.36.194 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
            </svg>
            <span>Kirim ke WhatsApp</span>
          </button>
        </form>

        <p class="ftm-wa-footnote">
          Pesan akan terkirim melalui WhatsApp — <strong>gratis</strong>, tidak perlu daftar.
        </p>
      </div>
    </div>
  </div>

  {{-- ========================================================== --}}
  {{-- WhatsApp Widget — Styles                                   --}}
  {{-- ========================================================== --}}
  <style>
    /* Container — fixed position, position will be overridden by JS via inline style */
    .ftm-wa-widget {
      position: fixed;
      right: 24px;
      bottom: 24px;
      z-index: 9998;
      font-family: 'Poppins', system-ui, sans-serif;
      pointer-events: none;
    }
    .ftm-wa-widget > * { pointer-events: auto; }

    /* ===== TRIGGER BUTTON (collapsed) ===== */
    .ftm-wa-trigger {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: #25D366;
      color: #FFFFFF;
      border: none;
      box-shadow: 0 8px 24px rgba(37, 211, 102, 0.45);
      cursor: grab;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      user-select: none;
      -webkit-tap-highlight-color: transparent;
    }
    .ftm-wa-trigger:hover {
      transform: scale(1.06);
      box-shadow: 0 10px 28px rgba(37, 211, 102, 0.55);
    }
    .ftm-wa-trigger:active,
    .ftm-wa-trigger.ftm-wa-dragging {
      cursor: grabbing;
      transform: scale(1.02);
    }
    .ftm-wa-icon {
      width: 30px;
      height: 30px;
      pointer-events: none;
    }
    .ftm-wa-pulse {
      position: absolute;
      top: 4px;
      right: 4px;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: #EE4E8B;
      border: 2px solid #FFFFFF;
      box-shadow: 0 0 0 0 rgba(238, 78, 139, 0.7);
      animation: ftmWaPulse 1.8s infinite;
      pointer-events: none;
    }
    @keyframes ftmWaPulse {
      0%   { box-shadow: 0 0 0 0 rgba(238, 78, 139, 0.7); }
      70%  { box-shadow: 0 0 0 10px rgba(238, 78, 139, 0); }
      100% { box-shadow: 0 0 0 0 rgba(238, 78, 139, 0); }
    }

    /* Hide trigger when card open */
    .ftm-wa-widget.is-open .ftm-wa-trigger {
      opacity: 0;
      transform: scale(0.5);
      pointer-events: none;
    }

    /* ===== CARD (open state) ===== */
    .ftm-wa-card {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 350px;
      max-width: calc(100vw - 32px);
      background: #FCF9F2;
      border-radius: 18px;
      box-shadow: 0 20px 50px rgba(28, 28, 28, 0.25),
                  0 0 0 1px rgba(244, 201, 223, 0.4);
      overflow: hidden;
      opacity: 0;
      transform: translateY(20px) scale(0.96);
      pointer-events: none;
      transition: opacity 0.25s ease, transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .ftm-wa-widget.is-open .ftm-wa-card {
      opacity: 1;
      transform: translateY(0) scale(1);
      pointer-events: auto;
    }

    /* ===== HEADER ===== */
    .ftm-wa-header {
      background: #25D366;
      color: #FFFFFF;
      padding: 0.95rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      cursor: grab;
      user-select: none;
      position: relative;
    }
    .ftm-wa-header.ftm-wa-dragging { cursor: grabbing; }
    .ftm-wa-header::before {
      content: '';
      position: absolute;
      bottom: -1px;
      left: 0;
      right: 0;
      height: 6px;
      background: linear-gradient(180deg, transparent, rgba(0, 0, 0, 0.04));
    }

    .ftm-wa-header-content {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      flex: 1;
      min-width: 0;
    }
    .ftm-wa-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.18);
      border: 2px solid rgba(255, 255, 255, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .ftm-wa-avatar svg {
      width: 22px;
      height: 22px;
      color: #FFFFFF;
    }
    .ftm-wa-header-text {
      flex: 1;
      min-width: 0;
    }
    .ftm-wa-title {
      font-family: 'Nord', 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 0.98rem;
      margin: 0;
      line-height: 1.2;
      color: #FFFFFF;
      letter-spacing: 0.01em;
    }
    .ftm-wa-subtitle {
      font-size: 0.72rem;
      margin: 0.15rem 0 0;
      color: rgba(255, 255, 255, 0.85);
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.4rem;
      line-height: 1.3;
    }
    .ftm-wa-online-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #C5D79B;
      box-shadow: 0 0 0 0 rgba(197, 215, 155, 0.7);
      animation: ftmWaOnline 1.6s infinite;
      flex-shrink: 0;
    }
    @keyframes ftmWaOnline {
      0%, 100% { opacity: 1; }
      50%      { opacity: 0.55; }
    }

    .ftm-wa-close {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.16);
      border: none;
      color: #FFFFFF;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      transition: background 0.18s ease, transform 0.18s ease;
    }
    .ftm-wa-close:hover {
      background: rgba(255, 255, 255, 0.28);
      transform: rotate(90deg);
    }

    /* ===== BODY ===== */
    .ftm-wa-body {
      padding: 1rem 1rem 1.1rem;
      max-height: calc(100vh - 200px);
      overflow-y: auto;
    }
    .ftm-wa-body::-webkit-scrollbar { width: 6px; }
    .ftm-wa-body::-webkit-scrollbar-thumb {
      background: #F4C9DF;
      border-radius: 3px;
    }

    .ftm-wa-greeting {
      background: #FFFFFF;
      border: 1px solid #F4C9DF;
      border-radius: 12px;
      padding: 0.75rem 0.9rem;
      margin-bottom: 0.85rem;
      box-shadow: 0 1px 3px rgba(122, 43, 74, 0.04);
      position: relative;
    }
    .ftm-wa-greeting::before {
      content: '';
      position: absolute;
      top: -7px;
      left: 18px;
      width: 14px;
      height: 14px;
      background: #FFFFFF;
      border-top: 1px solid #F4C9DF;
      border-left: 1px solid #F4C9DF;
      transform: rotate(45deg);
    }
    .ftm-wa-greeting p {
      margin: 0;
      font-size: 0.85rem;
      color: #1C1C1C;
      line-height: 1.55;
      font-weight: 500;
    }
    .ftm-wa-greeting p + p { margin-top: 0.25rem; }
    .ftm-wa-greeting strong { color: #7A2B4A; font-weight: 700; }

    /* ===== FORM ===== */
    .ftm-wa-form {
      display: flex;
      flex-direction: column;
      gap: 0.7rem;
    }
    .ftm-wa-field {
      display: flex;
      flex-direction: column;
      position: relative;
    }
    .ftm-wa-field label {
      font-size: 0.72rem;
      font-weight: 700;
      color: #7A2B4A;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      margin-bottom: 0.3rem;
      font-family: 'Nord', 'Poppins', sans-serif;
    }
    .ftm-wa-field input,
    .ftm-wa-field textarea {
      width: 100%;
      padding: 0.65rem 0.85rem;
      border: 1.5px solid #F4C9DF;
      border-radius: 10px;
      background: #FFFFFF;
      color: #1C1C1C;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      font-size: 0.88rem;
      line-height: 1.5;
      transition: border-color 0.18s ease, box-shadow 0.18s ease;
      resize: vertical;
    }
    .ftm-wa-field input::placeholder,
    .ftm-wa-field textarea::placeholder {
      color: rgba(28, 28, 28, 0.4);
    }
    .ftm-wa-field input:focus,
    .ftm-wa-field textarea:focus {
      outline: none;
      border-color: #25D366;
      box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.15);
    }
    .ftm-wa-field textarea {
      min-height: 84px;
      max-height: 180px;
    }
    .ftm-wa-counter {
      position: absolute;
      bottom: 6px;
      right: 10px;
      font-size: 0.7rem;
      color: rgba(28, 28, 28, 0.4);
      font-weight: 500;
      pointer-events: none;
      background: rgba(255, 255, 255, 0.85);
      padding: 1px 6px;
      border-radius: 4px;
    }

    /* ===== SUBMIT BUTTON ===== */
    .ftm-wa-submit {
      width: 100%;
      padding: 0.8rem 1rem;
      background: #25D366;
      color: #FFFFFF;
      border: none;
      border-radius: 10px;
      font-family: 'Nord', 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 0.88rem;
      letter-spacing: 0.04em;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      box-shadow: 0 6px 16px rgba(37, 211, 102, 0.32);
      transition: background 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
      margin-top: 0.25rem;
    }
    .ftm-wa-submit:hover {
      background: #128C7E;
      transform: translateY(-1px);
      box-shadow: 0 8px 20px rgba(18, 140, 126, 0.38);
    }
    .ftm-wa-submit:active { transform: translateY(0); }
    .ftm-wa-submit svg { flex-shrink: 0; }
    .ftm-wa-submit:disabled {
      background: #94a3b8;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .ftm-wa-footnote {
      font-size: 0.7rem;
      color: rgba(28, 28, 28, 0.5);
      text-align: center;
      margin: 0.7rem 0 0;
      line-height: 1.5;
    }
    .ftm-wa-footnote strong { color: #1A7A5E; font-weight: 700; }

    /* ===== RESPONSIVE — mobile ===== */
    @media (max-width: 640px) {
      .ftm-wa-widget {
        right: 16px !important;
        bottom: 16px !important;
        left: auto !important;
        top: auto !important;
      }
      .ftm-wa-card {
        width: calc(100vw - 32px);
        max-width: 360px;
      }
      .ftm-wa-header { cursor: default; }
      .ftm-wa-trigger { cursor: pointer; }
    }
  </style>

  {{-- ========================================================== --}}
  {{-- WhatsApp Widget — Logic                                     --}}
  {{-- ========================================================== --}}
  <script>
    (function() {
      const widget   = document.getElementById('ftm-wa-widget');
      const trigger  = document.getElementById('ftm-wa-trigger');
      const card     = document.getElementById('ftm-wa-card');
      const handle   = document.getElementById('ftm-wa-handle');
      const closeBtn = document.getElementById('ftm-wa-close');
      const form     = document.getElementById('ftm-wa-form');
      const nameIn   = document.getElementById('ftm-wa-name');
      const msgIn    = document.getElementById('ftm-wa-message');
      const counter  = document.getElementById('ftm-wa-counter');
      const waNumber = widget.dataset.waNumber || '6287785767395';

      if (!widget || !trigger || !card) return;

      const STORAGE_KEY = 'ftm_wa_widget_pos';
      const SNAP_PADDING = 16;
      const isMobile = () => window.matchMedia('(max-width: 640px)').matches;

      /* ====== Restore saved position ====== */
      function restorePosition() {
        if (isMobile()) return; // mobile selalu di pojok kanan-bawah
        try {
          const saved = JSON.parse(localStorage.getItem(STORAGE_KEY));
          if (!saved) return;
          // Validate within viewport
          const w = trigger.offsetWidth || 60;
          const h = trigger.offsetHeight || 60;
          const maxLeft = window.innerWidth - w - SNAP_PADDING;
          const maxTop  = window.innerHeight - h - SNAP_PADDING;
          const left = Math.max(SNAP_PADDING, Math.min(saved.left, maxLeft));
          const top  = Math.max(SNAP_PADDING, Math.min(saved.top, maxTop));
          applyPosition(left, top);
        } catch (e) { /* ignore */ }
      }

      function applyPosition(left, top) {
        widget.style.left   = left + 'px';
        widget.style.top    = top + 'px';
        widget.style.right  = 'auto';
        widget.style.bottom = 'auto';
        // Card alignment (so it opens toward viewport center)
        const isLeftHalf = (left + 30) < window.innerWidth / 2;
        const isTopHalf  = (top  + 30) < window.innerHeight / 2;
        card.style.left   = isLeftHalf ? '0' : 'auto';
        card.style.right  = isLeftHalf ? 'auto' : '0';
        card.style.top    = isTopHalf  ? '70px' : 'auto';
        card.style.bottom = isTopHalf  ? 'auto' : '70px';
      }

      function snapToEdge(left, top) {
        const w = widget.offsetWidth || 60;
        const h = widget.offsetHeight || 60;
        const vw = window.innerWidth;
        const vh = window.innerHeight;
        // Snap horizontally to nearest edge
        const centerX = left + w / 2;
        const snapLeft = (centerX < vw / 2)
          ? SNAP_PADDING
          : vw - w - SNAP_PADDING;
        // Constrain vertically
        const snapTop = Math.max(SNAP_PADDING, Math.min(top, vh - h - SNAP_PADDING));
        applyPosition(snapLeft, snapTop);
        try {
          localStorage.setItem(STORAGE_KEY, JSON.stringify({ left: snapLeft, top: snapTop }));
        } catch (e) {}
      }

      /* ====== Drag logic ====== */
      let dragState = null;

      function startDrag(e, source) {
        if (isMobile()) return;
        const evt = e.touches ? e.touches[0] : e;
        const rect = widget.getBoundingClientRect();
        dragState = {
          source: source,
          startX: evt.clientX,
          startY: evt.clientY,
          origLeft: rect.left,
          origTop:  rect.top,
          moved:    false,
        };
        widget.style.transition = 'none';
        source.classList.add('ftm-wa-dragging');
      }
      function onMove(e) {
        if (!dragState) return;
        const evt = e.touches ? e.touches[0] : e;
        const dx = evt.clientX - dragState.startX;
        const dy = evt.clientY - dragState.startY;
        if (!dragState.moved && (Math.abs(dx) > 4 || Math.abs(dy) > 4)) {
          dragState.moved = true;
        }
        if (dragState.moved) {
          e.preventDefault();
          applyPosition(dragState.origLeft + dx, dragState.origTop + dy);
        }
      }
      function endDrag(e) {
        if (!dragState) return;
        const wasMoved = dragState.moved;
        const source = dragState.source;
        source.classList.remove('ftm-wa-dragging');
        if (wasMoved) {
          const rect = widget.getBoundingClientRect();
          widget.style.transition = 'left 0.3s ease, top 0.3s ease';
          snapToEdge(rect.left, rect.top);
          // Prevent click after drag
          source._dragJustEnded = true;
          setTimeout(() => { source._dragJustEnded = false; }, 100);
        }
        dragState = null;
      }

      // Trigger drag (mouse + touch)
      trigger.addEventListener('mousedown',  (e) => startDrag(e, trigger));
      trigger.addEventListener('touchstart', (e) => startDrag(e, trigger), { passive: true });
      // Header drag (when card open)
      handle.addEventListener('mousedown',  (e) => {
        if (e.target.closest('.ftm-wa-close')) return;
        startDrag(e, handle);
      });
      handle.addEventListener('touchstart', (e) => {
        if (e.target.closest('.ftm-wa-close')) return;
        startDrag(e, handle);
      }, { passive: true });

      document.addEventListener('mousemove', onMove);
      document.addEventListener('touchmove', onMove, { passive: false });
      document.addEventListener('mouseup',   endDrag);
      document.addEventListener('touchend',  endDrag);

      /* ====== Open / Close ====== */
      function openCard() {
        widget.classList.add('is-open');
        card.setAttribute('aria-hidden', 'false');
        setTimeout(() => nameIn && nameIn.focus(), 280);
      }
      function closeCard() {
        widget.classList.remove('is-open');
        card.setAttribute('aria-hidden', 'true');
      }

      trigger.addEventListener('click', (e) => {
        if (trigger._dragJustEnded) return;
        openCard();
      });
      closeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        closeCard();
      });

      // Esc key to close
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && widget.classList.contains('is-open')) {
          closeCard();
        }
      });

      /* ====== Counter ====== */
      function updateCounter() {
        if (!msgIn || !counter) return;
        counter.textContent = msgIn.value.length + ' / ' + msgIn.maxLength;
      }
      msgIn && msgIn.addEventListener('input', updateCounter);
      updateCounter();

      /* ====== Submit -> WhatsApp ====== */
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = (nameIn.value || '').trim();
        const msg  = (msgIn.value  || '').trim();
        if (!name || !msg) return;

        // Pesan teks polos (tanpa emoji) — paling kompatibel di semua device
        const text = 'Halo FTM Society,\n\n' +
                     'Saya *' + name + '* ingin bertanya:\n' +
                     '"' + msg + '"\n\n' +
                     'Mohon informasinya. Terima kasih.';
        const url = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(text);
        window.open(url, '_blank', 'noopener,noreferrer');

        // Reset & give feedback
        const submitBtn = form.querySelector('.ftm-wa-submit');
        const orig = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg><span>Terkirim!</span>';
        setTimeout(() => {
          submitBtn.disabled = false;
          submitBtn.innerHTML = orig;
          form.reset();
          updateCounter();
          closeCard();
        }, 1400);
      });

      /* ====== Reposition on resize ====== */
      let resizeTimeout = null;
      window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
          if (isMobile()) {
            // Reset to mobile fixed position
            widget.style.left   = '';
            widget.style.top    = '';
            widget.style.right  = '';
            widget.style.bottom = '';
            card.style.left   = '';
            card.style.right  = '';
            card.style.top    = '';
            card.style.bottom = '';
          } else {
            restorePosition();
          }
        }, 120);
      });

      /* ====== Init ====== */
      restorePosition();
    })();
  </script>

  </body>
</html>



