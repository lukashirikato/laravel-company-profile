<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paket Saya - FTM Society</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/ftm-typography.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <style>
        /* ===============================================================
   FTM SOCIETY — MEMBERSHIP PACKAGE PICKER (Tailwind-based shell)
   Warna: maroon #4a041f / brand-800 #7A2B4A / rose accents
   =============================================================== */
#availablePackagesModal { z-index: 9999 !important; }
#availablePackagesModal .apm-backdrop {
    display: flex; align-items: center; justify-content: center;
    position: fixed; inset: 0;
    background: rgba(15, 5, 10, 0);
    transition: opacity 0.3s ease, background 0.3s ease;
    z-index: 9999; padding: 1rem; opacity: 0; pointer-events: none;
}
#availablePackagesModal.open-modal .apm-backdrop {
    opacity: 1; pointer-events: auto;
    background: rgba(15, 5, 10, 0.55);
}
#availablePackagesModal .apm-shell {
    width: 100%; max-width: 64rem; /* max-w-5xl */
    height: auto; max-height: min(92vh, 960px);
    display: flex; flex-direction: column;
    background: #faf7f8;
    border: 1px solid #ffe4e6; /* rose-100 */
    border-radius: 1.5rem;
    box-shadow: 0 25px 50px -12px rgba(15, 5, 10, 0.35);
    overflow: hidden; position: relative; margin: auto;
    opacity: 0;
    /* Tanpa scale — scale menyebabkan text buram (GPU raster) */
    transform: translateY(12px);
    transition: transform 0.3s ease, opacity 0.3s ease;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}
#availablePackagesModal.open-modal .apm-shell {
    transform: none; /* none, bukan scale(1) — sharp text */
    opacity: 1;
}
#availablePackagesModal .apm-handle {
    display: none; position: absolute; top: 8px; left: 50%;
    transform: translateX(-50%);
    width: 40px; height: 4px; border-radius: 999px;
    background: rgba(255,255,255,0.45); z-index: 5; pointer-events: none;
}
#availablePackagesModal .apm-scroll {
    flex: 1; min-height: 0; overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    overscroll-behavior: contain;
    padding: 1rem; padding-bottom: 5.5rem; /* space for sticky footer */
}
#availablePackagesModal .apm-scroll::-webkit-scrollbar { width: 6px; }
#availablePackagesModal .apm-scroll::-webkit-scrollbar-track { background: transparent; }
#availablePackagesModal .apm-scroll::-webkit-scrollbar-thumb {
    background-color: #fbcfe8; border-radius: 9999px;
}
#availablePackagesModal .pb-safe {
    padding-bottom: calc(0.875rem + env(safe-area-inset-bottom, 0px));
}
/* Mobile: full-height sheet */
@media (max-width: 639px) {
    #availablePackagesModal .apm-backdrop { padding: 0; align-items: flex-end; justify-content: stretch; }
    #availablePackagesModal .apm-shell {
        max-width: 100%;
        height: 100dvh; max-height: 100dvh;
        border-radius: 0; border: none;
        margin: 0; margin-top: auto;
    }
    #availablePackagesModal .apm-handle { display: block; }
    #availablePackagesModal .apm-scroll { padding: 1rem; padding-bottom: 5.5rem; }
}
@media (min-width: 640px) {
    #availablePackagesModal .apm-backdrop { padding: 1rem; }
    #availablePackagesModal .apm-scroll { padding: 1.5rem; padding-bottom: 5.5rem; }
}
@media (min-width: 1024px) {
    #availablePackagesModal .apm-scroll { padding: 2rem; padding-bottom: 5.5rem; }
}
/* ---------- Reduced motion ---------- */
@media (prefers-reduced-motion: reduce) {
    #availablePackagesModal *,
    #availablePackagesModal *::before,
    #availablePackagesModal *::after {
        transition: none !important; animation: none !important;
    }
}

        /* ===============================================================
           PAKET SAYOUT — masthead, kartu pass, riwayat
           Bahasa desain mengikuti member/book-class.blade.php
           Signature: kartu pass dengan perforasi + meter sesi berblok
           =============================================================== */
        .pk-masthead {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.25rem;
            flex-wrap: wrap;
            padding: .25rem 0 1.05rem;
            position: relative;
            margin-bottom: 1.75rem;
            animation: pkIn .45s ease both;
        }

        .pk-masthead::after {
            content: '';
            position: absolute;
            left: 0; right: 0; bottom: 0;
            height: 1px;
            background: linear-gradient(90deg, rgba(122, 43, 74, .25), rgba(238, 78, 139, .12), transparent 70%);
        }

        .pk-eyebrow {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #7A2B4A;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .pk-eyebrow i { color: #EE4E8B; font-size: .85rem; }

        .pk-masthead h1 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.65rem;
            font-weight: 800;
            color: #7A2B4A;
            letter-spacing: -0.01em;
            line-height: 1.2;
            margin-top: .35rem;
        }

        .pk-sub {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: #64748b;
            line-height: 1.5;
            margin-top: 4px;
        }

        .pk-masthead-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: .55rem;
        }

        .pk-chip {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .5rem .95rem;
            border-radius: 999px;
            border: 1px solid rgba(122, 43, 74, .16);
            background: rgba(255, 255, 255, .85);
            box-shadow: 0 8px 20px rgba(122, 43, 74, .06);
            white-space: nowrap;
            font-family: 'Poppins', sans-serif;
            font-size: .78rem;
            color: rgba(28, 28, 28, .55);
        }

        .pk-chip strong { color: #1C1C1C; font-weight: 600; }

        .pk-chip-dot {
            width: .6rem; height: .6rem;
            border-radius: 999px;
            background: #EE4E8B;
            box-shadow: 0 0 0 4px rgba(238, 78, 139, .15);
        }

        .pk-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            padding: .78rem 1.4rem;
            border-radius: 12px;
            background: #EE4E8B;
            color: #fff;
            border: none;
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: .85rem;
            letter-spacing: .03em;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(238, 78, 139, .3);
            transition: background .2s ease, transform .18s ease, box-shadow .2s ease;
        }

        .pk-cta:hover {
            background: #7A2B4A;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(122, 43, 74, .35);
        }

        .pk-cta:active { transform: translateY(0); }

        .pk-cta:focus-visible,
        .pk-btn:focus-visible,
        .pk-link:focus-visible {
            outline: 2px solid #EE4E8B;
            outline-offset: 3px;
        }

        /* ── SIGNATURE: KARTU PASS (tiket akses membership) ── */
        .pk-section-head {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .pk-sec-label {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #7A2B4A;
            flex-shrink: 0;
        }

        .pk-section-rule {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(122, 43, 74, .22), transparent 85%);
        }

        .pk-count {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #7A2B4A;
            background: rgba(238, 78, 139, .1);
            border-radius: 999px;
            padding: .28rem .75rem;
            flex-shrink: 0;
        }

        .pk-pass-stack { display: flex; flex-direction: column; gap: 1.25rem; }

        .pk-pass {
            position: relative;
            display: grid;
            grid-template-columns: auto 1px 1fr;
            background: #fff;
            border: 1px solid rgba(238, 78, 139, .14);
            border-radius: 18px;
            box-shadow: 0 2px 14px rgba(122, 43, 74, .06);
            overflow: hidden;
            transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease;
            animation: pkIn .5s ease both;
        }

        .pk-pass:nth-child(1) { animation-delay: .05s; }
        .pk-pass:nth-child(2) { animation-delay: .12s; }
        .pk-pass:nth-child(3) { animation-delay: .19s; }

        .pk-pass:hover {
            transform: translateY(-2px);
            border-color: rgba(238, 78, 139, .32);
            box-shadow: 0 12px 30px rgba(122, 43, 74, .1);
        }

        .pk-pass--expired:hover { transform: none; }

        /* Stub kiri — sisa hari */
        .pk-pass-stub {
            background: linear-gradient(165deg, #7A2B4A 0%, #5C1F38 100%);
            color: #FCF9F2;
            padding: 1.3rem 1.5rem 1.25rem;
            min-width: 11.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.4rem;
        }

        .pk-pass--expired .pk-pass-stub {
            background: linear-gradient(165deg, #4A3A40 0%, #3A2C31 100%);
        }

        .pk-status {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .pk-status-dot { width: .5rem; height: .5rem; border-radius: 999px; }

        .pk-status--calm { color: #F4C9DF; }
        .pk-status--calm .pk-status-dot { background: #F4C9DF; }
        .pk-status--soon { color: #FFC97A; }
        .pk-status--soon .pk-status-dot { background: #FFC97A; animation: pkPulse 2s ease infinite; }
        .pk-status--expired { color: rgba(252, 249, 242, .55); }
        .pk-status--expired .pk-status-dot { background: rgba(252, 249, 242, .45); }

        .pk-stub-label {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: rgba(252, 249, 242, .55);
            margin-top: 1rem;
        }

        .pk-days {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 900;
            font-size: 3.4rem;
            line-height: .95;
            letter-spacing: -0.02em;
            margin-top: .2rem;
        }

        .pk-days-note {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: rgba(252, 249, 242, .6);
            margin-top: .3rem;
        }

        .pk-pass-no {
            font-family: 'Poppins', sans-serif;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .14em;
            color: rgba(252, 249, 242, .45);
        }

        /* Perforasi antar stub dan badan pass */
        .pk-perf {
            position: relative;
            width: 1px;
            background: repeating-linear-gradient(to bottom, rgba(122, 43, 74, .32) 0 6px, transparent 6px 13px);
        }

        .pk-notch {
            position: absolute;
            width: 14px;
            height: 14px;
            border-radius: 999px;
            background: #FCF9F2;
            border: 1px solid rgba(122, 43, 74, .12);
        }

        .pk-notch--top { top: -7px; left: 50%; transform: translateX(-50%); }
        .pk-notch--bottom { bottom: -7px; left: 50%; transform: translateX(-50%); }

        /* Badan pass */
        .pk-pass-body {
            padding: 1.3rem 1.6rem 1.35rem 1.7rem;
            display: flex;
            flex-direction: column;
            gap: .95rem;
            min-width: 0;
        }

        .pk-pass-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .pk-pass-title {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.3rem;
            font-weight: 800;
            color: #7A2B4A;
            line-height: 1.2;
            letter-spacing: -0.01em;
            margin-top: .2rem;
        }

        .pk-pass-actions {
            display: flex;
            align-items: center;
            gap: .9rem;
            flex-shrink: 0;
        }

        .pk-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #EE4E8B;
            text-decoration: none;
            transition: color .18s ease, gap .18s ease;
        }

        .pk-link:hover { color: #7A2B4A; gap: .55rem; }
        .pk-link i { font-size: 11px; }

        .pk-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .6rem 1.15rem;
            border-radius: 10px;
            background: transparent;
            color: #7A2B4A;
            border: 1px solid rgba(122, 43, 74, .28);
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: .02em;
            cursor: pointer;
            transition: background .18s ease, border-color .18s ease, color .18s ease;
        }

        .pk-btn:hover { background: #7A2B4A; border-color: #7A2B4A; color: #FCF9F2; }

        /* Meter sesi berblok — 1 blok = 1 sesi, dibentuk mini-tiket
           (coakan sobek kiri-kanan, echo dari perforasi kartu pass) */
        .pk-meter { display: flex; gap: 5px; flex-wrap: wrap; }

        .pk-seg {
            height: 16px;
            flex: 1 1 14px;
            min-width: 10px;
            max-width: 28px;
            border-radius: 3px;
            background: rgba(244, 201, 223, .45);
            border: 1px solid rgba(238, 78, 139, .18);
            -webkit-mask-image: radial-gradient(circle 2.5px at 0 50%, transparent 98%, #000), radial-gradient(circle 2.5px at 100% 50%, transparent 98%, #000);
            -webkit-mask-size: 51% 100%;
            -webkit-mask-position: left, right;
            -webkit-mask-repeat: no-repeat;
            mask-image: radial-gradient(circle 2.5px at 0 50%, transparent 98%, #000), radial-gradient(circle 2.5px at 100% 50%, transparent 98%, #000);
            mask-size: 51% 100%;
            mask-position: left, right;
            mask-repeat: no-repeat;
        }

        .pk-seg.is-left {
            background: #EE4E8B;
            border-color: #EE4E8B;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .25);
        }

        .pk-meter-note {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: rgba(28, 28, 28, .55);
            margin-top: .5rem;
        }

        .pk-meter-note strong { color: #7A2B4A; font-weight: 700; }

        .pk-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem 1.4rem;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: rgba(28, 28, 28, .55);
        }

        .pk-meta i { color: #EE4E8B; margin-right: .35rem; font-size: 12px; }
        .pk-meta strong { color: #1C1C1C; font-weight: 600; }

        .pk-warning {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            padding: .8rem 1rem;
            border-radius: 12px;
            background: rgba(238, 78, 139, .06);
            border: 1px solid rgba(238, 78, 139, .16);
        }

        .pk-warning i { color: #EE4E8B; margin-top: 2px; font-size: 13px; }

        .pk-warning p {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: #7A2B4A;
            font-weight: 600;
        }

        .pk-warning span {
            display: block;
            font-size: 12px;
            font-weight: 400;
            color: rgba(122, 43, 74, .65);
            margin-top: .15rem;
        }

        /* Empty state */
        .pk-empty {
            background: #fff;
            border: 1px solid rgba(238, 78, 139, .14);
            border-radius: 18px;
            box-shadow: 0 2px 14px rgba(122, 43, 74, .06);
            padding: 3rem 1.5rem 3.2rem;
            text-align: center;
            animation: pkIn .5s ease both;
        }

        .pk-empty-mark {
            width: 4.5rem;
            height: 4.5rem;
            margin: 0 auto 1.2rem;
            border-radius: 999px;
            background: rgba(238, 78, 139, .08);
            border: 1px dashed rgba(238, 78, 139, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7A2B4A;
            font-size: 1.4rem;
        }

        .pk-empty h3 {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-weight: 800;
            color: #7A2B4A;
            margin-bottom: .35rem;
        }

        .pk-empty .pk-empty-note {
            font-family: 'Instrument Serif', Georgia, serif;
            font-style: italic;
            font-size: 1.05rem;
            color: rgba(28, 28, 28, .55);
            margin-bottom: 1.5rem;
        }

        /* Riwayat */
        .pk-history-head { margin: 2.5rem 0 1rem; }

        .pk-history-note {
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            color: rgba(28, 28, 28, .45);
            text-align: center;
            margin-top: .9rem;
        }

        .pk-history-note a { color: #EE4E8B; font-weight: 600; text-decoration: none; }
        .pk-history-note a:hover { color: #7A2B4A; text-decoration: underline; }

        @keyframes pkIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
        @keyframes pkPulse { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }

        @media (prefers-reduced-motion: reduce) {
            .pk-masthead, .pk-pass, .pk-empty, .pk-status--soon .pk-status-dot {
                animation: none !important;
            }
            .pk-pass, .pk-cta, .pk-btn, .pk-link { transition: none !important; }
            .pk-pass:hover, .pk-cta:hover { transform: none !important; }
        }

        /* Mobile: pass menumpuk vertikal */
        @media (max-width: 860px) {
            .pk-masthead { align-items: flex-start; flex-direction: column; gap: .9rem; }
            .pk-masthead h1 { font-size: 1.4rem; }
            .pk-sub { font-size: 13px; }
            .pk-masthead-actions { align-items: flex-start; width: 100%; }
            .pk-cta { width: 100%; }
            .pk-pass { grid-template-columns: 1fr; }
            .pk-pass-stub {
                min-width: 0;
                flex-direction: row;
                align-items: flex-end;
                justify-content: space-between;
                padding: 1.15rem 1.25rem 1.05rem;
                gap: 1rem;
            }
            .pk-stub-label { margin-top: 0; }
            .pk-days { font-size: 2.7rem; }
            .pk-pass-no { text-align: right; }
            .pk-perf {
                width: auto;
                height: 1px;
                margin: 0 1.1rem;
                background: repeating-linear-gradient(to right, rgba(122, 43, 74, .32) 0 6px, transparent 6px 13px);
            }
            .pk-notch--top { top: 50%; left: -7px; transform: translateY(-50%); }
            .pk-notch--bottom { top: 50%; bottom: auto; left: auto; right: -7px; transform: translateY(-50%); }
            .pk-pass-body { padding: 1.15rem 1.25rem 1.3rem; }
            .pk-pass-head { flex-direction: column; gap: .75rem; }
            .pk-pass-actions { width: 100%; justify-content: space-between; }
        }

        /* ===============================================================
           MODAL DETAIL PASS (#packageModal)
           Berada di luar <main> — semua warna ditulis eksplisit
           (utility kustom seperti bg-dark/cream0 tidak terdefinisi)
           =============================================================== */
        #packageModal { z-index: 9998 !important; }

        .pkd-shell {
            background: #FCF9F2;
            border: 1px solid rgba(238, 78, 139, .16);
        }

        .pkd-header {
            background: linear-gradient(135deg, #7A2B4A 0%, #5C1F38 100%);
            color: #FCF9F2;
            padding: 1rem 1.15rem;
            display: flex;
            align-items: center;
            gap: .9rem;
            flex-shrink: 0;
        }

        .pkd-back {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .18);
            color: #FCF9F2;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: background .18s ease;
        }

        .pkd-back:hover { background: rgba(255, 255, 255, .28); }
        .pkd-back:focus-visible { outline: 2px solid #F4C9DF; outline-offset: 2px; }

        .pkd-header-text { flex: 1; min-width: 0; }

        .pkd-eyebrow {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #F4C9DF;
        }

        .pkd-header-title {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            color: #FCF9F2;
            line-height: 1.25;
            margin-top: .1rem;
        }

        .pkd-header-mark {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .16);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #F4C9DF;
            flex-shrink: 0;
        }

        .pkd-body {
            background: #FCF9F2;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        .pkd-card {
            background: #fff;
            border: 1px solid rgba(238, 78, 139, .14);
            border-radius: 14px;
            padding: 1rem 1.1rem;
        }

        .pkd-card-title {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: #7A2B4A;
            margin-bottom: .7rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .pkd-card-title i { color: #EE4E8B; font-size: .8rem; }

        .pkd-name {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: #7A2B4A;
            line-height: 1.25;
        }

        .pkd-code {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1em;
            color: rgba(28, 28, 28, .45);
            margin-top: .2rem;
        }

        /* Kartu statistik gelap — echo stub pass */
        .pkd-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .6rem;
        }

        .pkd-stat {
            background: linear-gradient(165deg, #7A2B4A 0%, #5C1F38 100%);
            border-radius: 14px;
            padding: .85rem .95rem;
            color: #FCF9F2;
            min-width: 0;
        }

        .pkd-stat-label {
            font-family: 'Poppins', sans-serif;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(252, 249, 242, .55);
            margin-bottom: .3rem;
        }

        .pkd-stat-value {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #FCF9F2;
            line-height: 1.2;
            word-break: break-word;
        }

        .pkd-stat-note {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            color: rgba(252, 249, 242, .55);
            margin-top: .25rem;
        }

        .pkd-stat-badge {
            display: inline-block;
            margin-top: .45rem;
            padding: .2rem .6rem;
            border-radius: 999px;
            font-family: 'Poppins', sans-serif;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .04em;
        }

        /* Kuota */
        .pkd-usage-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .pkd-usage-side {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: rgba(28, 28, 28, .55);
        }

        .pkd-usage-main {
            font-family: 'Nord', 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 800;
            color: #7A2B4A;
        }

        /* Peringatan */
        .pkd-warning {
            display: flex;
            gap: .7rem;
            align-items: flex-start;
            padding: .8rem .95rem;
            border-radius: 12px;
            background: rgba(176, 125, 0, .08);
            border: 1px solid rgba(176, 125, 0, .25);
            border-left: 3px solid #B07D00;
        }

        .pkd-warning i { color: #B07D00; margin-top: 2px; font-size: 13px; }

        .pkd-warning-title {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #7A4A00;
        }

        .pkd-warning-text {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: rgba(28, 28, 28, .6);
            margin-top: .15rem;
        }

        .pkd-warning--danger { background: rgba(192, 51, 79, .08); border-color: rgba(192, 51, 79, .25); border-left-color: #C0334F; }
        .pkd-warning--danger i { color: #C0334F; }
        .pkd-warning--danger .pkd-warning-title { color: #C0334F; }
        .pkd-warning.hidden { display: none; }

        /* Timeline */
        .pkd-tl { display: flex; flex-direction: column; gap: .9rem; }

        .pkd-tl-item { display: flex; gap: .75rem; align-items: flex-start; }

        .pkd-tl-icon {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .8rem;
        }

        .pkd-tl-icon--in { background: rgba(26, 122, 94, .12); color: #1A7A5E; }
        .pkd-tl-icon--out { background: rgba(238, 78, 139, .12); color: #EE4E8B; }

        .pkd-tl-label {
            font-family: 'Poppins', sans-serif;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(28, 28, 28, .45);
        }

        .pkd-tl-value {
            font-family: 'Poppins', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            color: #1C1C1C;
            margin-top: .1rem;
        }

        .pkd-tl-badge {
            display: inline-block;
            margin-top: .3rem;
            padding: .18rem .55rem;
            border-radius: 999px;
            font-family: 'Poppins', sans-serif;
            font-size: 10.5px;
            font-weight: 700;
        }

        /* Loading */
        .pkd-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 320px;
        }

        #modalContent.pkd-content {
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        #modalContent.pkd-content.hidden {
            display: none;
        }

        .pkd-loading-text {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: rgba(28, 28, 28, .5);
        }

        @media (max-width: 640px) {
            .pkd-stats { grid-template-columns: 1fr; }
        }

        /* ===== MODAL STYLES ===== */
        .modal-backdrop {
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(0px);
            background: rgba(0, 0, 0, 0);
        }
        .modal-backdrop.active {
            opacity: 1;
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.75) !important;
        }
        
        /* Page dimming is handled by each modal backdrop; do NOT blur main content
           (previously caused the whole page to go blurry when a modal opened). */
        .modal-content {
            transform: translateY(40px) scale(0.95);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .modal-backdrop.active .modal-content {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
        .modal-tab {
            position: relative;
            color: #6b7280;
            padding: 0.75rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: color 0.2s;
            border-bottom: 2px solid transparent;
        }
        .modal-tab:hover { color: #7A2B4A; }
        .modal-tab.active {
            color: #7A2B4A;
            border-bottom-color: #EE4E8B;
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .modal-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        .spinner {
            width: 40px; height: 40px;
            border: 3px solid #F4C9DF;
            border-top-color: #EE4E8B;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.625rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #6b7280; font-size: 0.875rem; }
        .info-value { color: #111827; font-size: 0.875rem; font-weight: 600; }
        .status-badge {
            display: inline-flex; align-items: center; gap: 0.25rem;
            padding: 0.25rem 0.75rem; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
        }
        .status-active { background: #dcfce7; color: #166534; }
        .status-expired { background: #fee2e2; color: #991b1b; }

        /* Sidebar & hamburger ditangani ftm-member-portal.css */
    </style>
    <link rel="stylesheet" href="{{ asset('css/ftm-member-portal.css') }}?v={{ filemtime(public_path('css/ftm-member-portal.css')) }}">
</head>

<body class="bg-cream h-screen overflow-hidden">

<div class="flex h-screen">

    @include('partials.member-sidebar')

    <!-- Mobile Sidebar Overlay removed to avoid dark backdrop -->

{{-- ================= MAIN ================= --}}
<main class="flex-1 p-6 md:p-10 overflow-y-auto bg-cream">

    <!-- Mobile Hamburger Button -->
    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    {{-- ================= MASTHEAD ================= --}}
    <header class="pk-masthead mt-14 md:mt-0">
        <div>
            <p class="pk-eyebrow"><i class="fas fa-ticket"></i> Member Area &middot; Paket</p>
            <h1 class="font-nord">Paket Saya</h1>
            <p class="pk-sub">Satu pass untuk tiap paketmu — sisa hari dan sesi terlihat jelas di sini.</p>
        </div>
        <div class="pk-masthead-actions">
            <span class="pk-chip">
                <span class="pk-chip-dot"></span>
                <span><strong>{{ $activePackages->count() }}</strong> aktif &middot; <strong>{{ $pastPackages->count() }}</strong> riwayat</span>
            </span>
            <a href="#" class="pk-cta" onclick="openAvailablePackagesModal(); return false;">
                <i class="fas fa-plus"></i>
                Beli Paket Baru
            </a>
        </div>
    </header>

    {{-- ================= PASS AKTIF ================= --}}
    @if($activePackages?->count())
    <section aria-labelledby="pk-active-label">
        <div class="pk-section-head">
            <span class="pk-sec-label" id="pk-active-label">Pass Aktif</span>
            <span class="pk-section-rule" aria-hidden="true"></span>
            <span class="pk-count">{{ $activePackages->count() }} pass</span>
        </div>

        <div class="pk-pass-stack">
        @foreach($activePackages as $order)
            @php
                $pkg = $order->package;
                $isExpired = method_exists($order, 'isExpired') ? $order->isExpired() : false;
                $remainingDays = method_exists($order, 'getRemainingDays') ? $order->getRemainingDays() : 0;
                $remainingTime = method_exists($order, 'getRemainingTime') ? $order->getRemainingTime() : '-';
                $totalQuota = (int) ($pkg->quota ?? 0);
                $classesLeft = max(0, (int) ($order->remaining_classes ?? $order->remaining_sessions ?? $totalQuota));
                $daysLeft = $isExpired ? 0 : max(0, (int) $remainingDays);
                $urgency = $isExpired ? 'expired' : ($daysLeft <= 7 ? 'soon' : 'calm');
                $segTotal = $totalQuota > 0 ? min($totalQuota, 24) : 0;
                $segRemaining = $segTotal > 0 ? (int) round(($classesLeft / max(1, $totalQuota)) * $segTotal) : 0;
                $segRemaining = max(0, min($segRemaining, $segTotal));
            @endphp

            <article class="pk-pass{{ $urgency === 'expired' ? ' pk-pass--expired' : '' }}">
                {{-- Stub: sisa hari --}}
                <div class="pk-pass-stub">
                    <div>
                        <span class="pk-status pk-status--{{ $urgency }}">
                            <span class="pk-status-dot" aria-hidden="true"></span>
                            {{ $urgency === 'expired' ? 'Kedaluwarsa' : ($urgency === 'soon' ? 'Segera berakhir' : 'Aktif') }}
                        </span>
                        <p class="pk-stub-label">Sisa hari</p>
                        <p class="pk-days">{{ $daysLeft }}</p>
                        <p class="pk-days-note">{{ $isExpired ? 'Masa aktif sudah berakhir' : $remainingTime }}</p>
                    </div>
                    <span class="pk-pass-no">#{{ $order->order_code }}</span>
                </div>

                {{-- Perforasi --}}
                <div class="pk-perf" aria-hidden="true">
                    <span class="pk-notch pk-notch--top"></span>
                    <span class="pk-notch pk-notch--bottom"></span>
                </div>

                {{-- Badan pass --}}
                <div class="pk-pass-body">
                    <div class="pk-pass-head">
                        <div>
                            <p class="pk-sec-label">Pass Membership</p>
                            <h3 class="pk-pass-title">{{ $pkg->name ?? 'Paket' }}</h3>
                        </div>
                        <div class="pk-pass-actions">
                            @if(!$isExpired && $classesLeft > 0)
                            <a href="{{ route('member.book') }}" class="pk-link">
                                Booking kelas <i class="fas fa-arrow-right"></i>
                            </a>
                            @endif
                            <button type="button" class="pk-btn" onclick="openPackageModal({{ $order->id }})">
                                <i class="fas fa-receipt"></i> Lihat Detail
                            </button>
                        </div>
                    </div>

                    @if($segTotal > 0)
                    <div>
                        <div class="pk-meter" role="img" aria-label="{{ $classesLeft }} dari {{ $totalQuota }} sesi tersisa">
                            @for($i = 0; $i < $segTotal; $i++)
                                <span class="pk-seg{{ $i < $segRemaining ? ' is-left' : '' }}"></span>
                            @endfor
                        </div>
                        <p class="pk-meter-note"><strong>{{ $classesLeft }}</strong> dari {{ $totalQuota }} sesi masih bisa dipakai</p>
                    </div>
                    @else
                    <p class="pk-meter-note">Jumlah sesi mengikuti ketentuan paket ini.</p>
                    @endif

                    <div class="pk-meta">
                        <span><i class="fas fa-calendar-plus"></i> Mulai <strong>{{ $order->created_at?->format('d M Y') ?? '-' }}</strong></span>
                        <span><i class="fas fa-calendar-check"></i> Berakhir <strong>{{ $order->expired_at ? \Carbon\Carbon::parse($order->expired_at)->format('d M Y') : 'Tak Terbatas' }}</strong></span>
                        <span><i class="fas fa-dumbbell"></i> Tipe <strong>{{ $pkg->is_exclusive ? 'Eksklusif' : 'Reguler' }}</strong></span>
                    </div>

                    @if($urgency === 'soon')
                    <div class="pk-warning">
                        <i class="fas fa-hourglass-half"></i>
                        <p>Pass ini segera berakhir
                            <span>Perpanjang sekarang agar jadwalmu tidak terganggu.</span>
                        </p>
                    </div>
                    @endif
                </div>
            </article>
        @endforeach
        </div>
    </section>
    @else
        {{-- Empty state --}}
        <div class="pk-empty">
            <div class="pk-empty-mark"><i class="fas fa-ticket"></i></div>
            <h3>Belum ada pass aktif</h3>
            <p class="pk-empty-note">Setiap rutinitas dimulai dari satu sesi pertama.</p>
            <a href="#" class="pk-cta" onclick="openAvailablePackagesModal(); return false;">
                <i class="fas fa-plus"></i>
                Lihat Paket Tersedia
            </a>
        </div>
    @endif

    {{-- ================= RECENT HISTORY ================= --}}
    @if($pastPackages?->count())
    <div class="mt-10">
        <div class="pk-section-head pk-history-head">
            <span class="pk-sec-label">Riwayat Paket</span>
            <span class="pk-section-rule" aria-hidden="true"></span>
            <span class="pk-count">{{ $pastPackages->count() }} berlalu</span>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-2xl shadow-[0_4px_20px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="bg-[rgba(238,78,139,0.06)]">
                        <th class="px-6 py-4 text-left font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Nama Paket</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Tipe</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Tanggal</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Status</th>
                        <th class="px-6 py-4 text-center font-poppins font-semibold text-[12px] uppercase tracking-wider text-secondary">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[rgba(238,78,139,0.06)]">
                    @foreach($pastPackages->take(5) as $order)
                        @php $pkg = $order->package; @endphp
                        <tr class="hover:bg-[rgba(244,201,223,0.12)] transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-[rgba(238,78,139,0.08)] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-box text-secondary text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-poppins font-semibold text-[14px] text-dark">{{ $pkg->name ?? 'Package' }}</p>
                                        <p class="font-poppins text-[12px] text-dark/35 font-mono">{{ $order->order_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-poppins text-[14px] text-dark">{{ $pkg->is_exclusive ? 'Eksklusif' : 'Reguler' }}</td>
                            <td class="px-6 py-4 text-center font-poppins text-[14px] text-dark">{{ $order->created_at?->format('d M Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full font-poppins font-semibold text-[11px] bg-[rgba(238,78,139,0.1)] text-secondary">
                                    <i class="fas fa-times-circle text-[10px]"></i>
                                    Kedaluwarsa
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openPackageModal({{ $order->id }})"
                                   class="font-poppins font-medium text-[13px] text-primary hover:text-secondary transition-colors duration-150 inline-flex items-center gap-1.5">
                                    <i class="fas fa-eye text-[12px]"></i>
                                    Lihat Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3.5">
            @foreach($pastPackages->take(5) as $order)
                @php $pkg = $order->package; @endphp
                <div class="bg-white rounded-xl shadow-sm border border-[rgba(238,78,139,0.1)] p-4">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-poppins font-semibold text-[14px] text-dark">{{ $pkg->name ?? 'Package' }}</p>
                            <p class="font-poppins text-[12px] text-dark/35 font-mono">{{ $order->order_code }}</p>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-poppins font-semibold text-[11px] bg-[rgba(238,78,139,0.1)] text-secondary">
                            Kedaluwarsa
                        </span>
                    </div>
                    <div class="flex items-center justify-between font-poppins text-[12px] text-dark/50 mt-2.5 pt-2.5 border-t border-[rgba(238,78,139,0.06)]">
                        <span>{{ $order->created_at?->format('d M Y') }}</span>
                        <button onclick="openPackageModal({{ $order->id }})" class="text-primary font-medium hover:text-secondary transition-colors">
                            <i class="fas fa-eye mr-1"></i>Lihat Paket
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($pastPackages->count() > 5)
        <p class="pk-history-note">
            Menampilkan 5 dari {{ $pastPackages->count() }} paket — riwayat lengkap ada di
            <a href="{{ route('member.transactions') }}">halaman Transaksi</a>.
        </p>
        @endif
    </div>
    @endif

</main>
</div>

<div id="availablePackagesModal" class="fixed inset-0 z-[9999] hidden">
    <div class="apm-backdrop" onclick="closeAvailablePackagesModal(event)">
        <div class="apm-shell" onclick="event.stopPropagation()" role="dialog" aria-modal="true" aria-label="Pilih Paket Membership">
            <span class="apm-handle" aria-hidden="true"></span>

            <!-- ===== HEADER ===== -->
            <header class="relative flex-shrink-0 bg-gradient-to-r from-[#9d174d] via-[#831843] to-[#4a041f] text-white px-5 py-4 sm:px-8 sm:py-6 shadow-md overflow-hidden">
                <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-white/5 blur-xl pointer-events-none"></div>
                <div class="absolute right-32 -bottom-8 w-36 h-36 rounded-full bg-rose-400/10 blur-lg pointer-events-none"></div>
                <div class="relative flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-white/15 flex items-center justify-center border border-white/20">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-rose-100" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M2.5 19h19a1 1 0 0 0 1-1V9a1 1 0 0 0-1.6-.8l-4.4 3.3-3.9-6.5a1 1 0 0 0-1.7 0l-3.9 6.5-4.4-3.3A1 1 0 0 0 2.5 9v9a1 1 0 0 0 1 1zM4.5 17v-5.2l3 2.25a1 1 0 0 0 1.4-.25L12 8.3l3.1 5.2a1 1 0 0 0 1.4.25l3-2.25V17h-15zM3 21h18a1 1 0 0 0 0-2H3a1 1 0 0 0 0 2z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-base sm:text-2xl font-bold tracking-tight text-white leading-tight">Pilih Paket Membership</h2>
                            <p class="text-xs sm:text-sm text-rose-100/90 mt-0.5 font-normal leading-snug">Pilih paket sesuai kebutuhanmu.</p>
                        </div>
                    </div>
                    <button onclick="closeAvailablePackagesModal()" type="button" title="Tutup" aria-label="Tutup"
                        class="apm-close-btn flex-shrink-0 w-11 h-11 rounded-full flex items-center justify-center bg-white/10 hover:bg-white/20 active:scale-95 transition-all text-white border border-white/15 focus:outline-none focus:ring-2 focus:ring-rose-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- ===== SCROLLABLE BODY ===== -->
            <main class="apm-scroll" id="apmBody">
                @if(isset($availablePackages) && $availablePackages->count() > 0)
                @php
                    $sortedPackages = $availablePackages->sortByDesc('is_exclusive')->values();
                    $featuredPackage = $sortedPackages->first(fn ($p) => !empty($p->is_exclusive));
                    $regularPackages = $sortedPackages->filter(fn ($p) => empty($p->is_exclusive))->values();
                @endphp

                @if($featuredPackage)
                @php
                    $fp = $featuredPackage;
                    $fpPrice = (int) ($fp->price ?? 0);
                @endphp
                <!-- EXCLUSIVE PACKAGE -->
                <section class="relative bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-7 border-2 border-rose-300/80 shadow-lg shadow-rose-900/5 transition-all duration-200" aria-label="Paket unggulan">
                    <div class="flex flex-wrap items-center gap-2 mb-3 sm:mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-[#831843] text-white shadow-sm">
                            <svg class="w-3.5 h-3.5 fill-amber-300" viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Paling Populer
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-rose-50 text-[#9d174d] border border-rose-200">
                            Eksklusif
                        </span>
                    </div>

                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <!-- Left -->
                        <div class="flex-1 space-y-3.5">
                            <h3 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-[#4a041f] tracking-tight leading-tight">{{ $fp->name }}</h3>
                            @if($fp->description)
                            <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl">{{ $fp->description }}</p>
                            @endif
                            @if($fp->duration_days || $fp->quota)
                            <div class="flex flex-wrap items-center gap-2.5 pt-1">
                                @if($fp->duration_days)
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-50/80 border border-rose-100 text-xs sm:text-sm font-medium text-[#831843]">
                                    <i class="fas fa-calendar-days" aria-hidden="true"></i> {{ $fp->duration_days }} Hari
                                </span>
                                @endif
                                @if($fp->quota)
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-50/80 border border-rose-100 text-xs sm:text-sm font-medium text-[#831843]">
                                    <i class="fas fa-ticket" aria-hidden="true"></i> {{ $fp->quota }} Sesi
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Right: price + CTA -->
                        <div class="flex flex-col sm:flex-row lg:flex-col items-start sm:items-center lg:items-end justify-between lg:justify-center gap-4 pt-4 lg:pt-0 border-t lg:border-t-0 border-rose-100 lg:min-w-[270px]">
                            <div class="w-full sm:w-auto lg:w-full bg-[#fdf8fa] p-4 sm:p-5 rounded-2xl border border-rose-100 text-left lg:text-right">
                                <span class="text-xs font-semibold tracking-wider uppercase text-slate-500 block mb-0.5">Mulai Dari</span>
                                @if($fpPrice > 0)
                                <div class="flex items-baseline gap-1 lg:justify-end">
                                    <span class="text-sm font-semibold text-[#831843]">Rp</span>
                                    <span class="text-3xl sm:text-4xl font-extrabold text-[#9d174d] tracking-tight">{{ number_format($fpPrice, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Sudah termasuk pajak</span>
                                @else
                                <div class="flex items-baseline gap-1 lg:justify-end">
                                    <span class="text-2xl sm:text-3xl font-extrabold text-[#9d174d] tracking-tight">Hubungi Kami</span>
                                </div>
                                <span class="text-[11px] text-slate-400 block mt-0.5">Harga menyesuaikan kebutuhanmu</span>
                                @endif
                            </div>
                            @if($fpPrice > 0)
                            <a href="{{ route('join.package', ['package' => $fp->slug ?? $fp->id]) }}"
                               class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#9d174d] to-[#831843] hover:from-[#b01e5a] hover:to-[#9d174d] active:scale-[0.98] text-white font-semibold text-sm sm:text-base tracking-wide shadow-md shadow-[#500724]/20 hover:shadow-lg transition duration-200 min-h-[48px]">
                                <span>Beli Sekarang</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                </svg>
                            </a>
                            @else
                            <a href="{{ $fp->whatsapp_inquiry_url }}" target="_blank" rel="noopener"
                               class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#128C7E] to-[#075E54] hover:from-[#1DAE9E] hover:to-[#128C7E] active:scale-[0.98] text-white font-semibold text-sm sm:text-base tracking-wide shadow-md shadow-[#075E54]/20 hover:shadow-lg transition duration-200 min-h-[48px]">
                                <i class="fab fa-whatsapp text-lg" aria-hidden="true"></i>
                                <span>Hubungi Kami via WhatsApp</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </section>
                @endif

                @if($regularPackages->count())
                <!-- REGULAR SECTION -->
                <div class="pt-2 mt-6" role="region" aria-labelledby="apm-regular-label">
                    <div class="flex items-center gap-3 mb-4">
                        <h3 class="text-base sm:text-lg font-extrabold uppercase tracking-wider text-[#4a041f] flex-shrink-0" id="apm-regular-label">Paket Reguler</h3>
                        <div class="h-px bg-rose-200 flex-1" aria-hidden="true"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                        @foreach($regularPackages as $package)
                            @include('member.packages.partials.modal-card', ['package' => $package])
                        @endforeach
                    </div>
                </div>
                @endif

                @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-4 border border-rose-100">
                        <i class="fas fa-box-open text-2xl text-[#9d174d]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#4a041f] mb-2">Belum Ada Paket Tersedia</h3>
                    <p class="text-slate-500 text-sm">Saat ini belum ada paket membership yang dapat dibeli.</p>
                </div>
                @endif
            </main>

            <!-- ===== STICKY SECURITY FOOTER ===== -->
            <footer class="absolute inset-x-0 bottom-0 bg-white border-t border-rose-100/80 py-3.5 px-4 pb-safe flex items-center justify-center shadow-lg z-10">
                <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-600 font-medium">
                    <span class="text-emerald-600 text-sm" aria-hidden="true">🔒</span>
                    <span><strong class="font-semibold text-slate-700">Pembayaran Aman</strong></span>
                    <span class="w-1 h-1 rounded-full bg-slate-300" aria-hidden="true"></span>
                    <span class="text-slate-500 font-normal">Didukung Midtrans</span>
                </div>
            </footer>
        </div>
    </div>
</div>

<!-- ========================================
     PACKAGE DETAIL MODAL — gaya pass (warna eksplisit)
======================================== -->
<div id="packageModal" class="fixed inset-0 z-[9998] hidden">
    <div class="modal-backdrop absolute inset-0 bg-black/60 flex items-end md:items-center justify-center" onclick="closePackageModal(event)">
        <div class="modal-content pkd-shell w-full md:max-w-2xl md:rounded-2xl rounded-t-3xl shadow-2xl max-h-[95vh] md:max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">

            <!-- Header -->
            <div class="pkd-header">
                <button onclick="closePackageModal()" class="pkd-back" aria-label="Tutup detail">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="pkd-header-text">
                    <p class="pkd-eyebrow">Member Area &middot; Pass</p>
                    <p class="pkd-header-title">Detail Paket</p>
                </div>
                <div class="pkd-header-mark" aria-hidden="true"><i class="fas fa-receipt"></i></div>
            </div>

            <!-- Modal Body - Scrollable -->
            <div class="flex-1 overflow-y-auto pkd-body">
                <!-- Loading State -->
                <div id="modalLoading" class="pkd-loading">
                    <div class="text-center">
                        <div class="spinner mx-auto mb-3"></div>
                        <p class="pkd-loading-text">Memuat detail paket...</p>
                    </div>
                </div>

                <!-- Content Container -->
                <div id="modalContent" class="hidden pkd-content">

                    <!-- Ringkasan pass (info waktu digabung, tidak duplikat) -->
                    <div class="pkd-card">
                        <h3 id="modalPackageName" class="pkd-name">Loading...</h3>
                        <p id="modalOrderCode" class="pkd-code"></p>

                        <div class="pkd-tl" style="margin-top:.9rem;">
                            <!-- Tanggal Pembelian -->
                            <div class="pkd-tl-item">
                                <div class="pkd-tl-icon pkd-tl-icon--in"><i class="fas fa-calendar-plus"></i></div>
                                <div>
                                    <p class="pkd-tl-label">TANGGAL PEMBELIAN</p>
                                    <p id="purchaseDate" class="pkd-tl-value"></p>
                                    <span id="purchaseBadge" class="pkd-tl-badge" style="background:rgba(26,122,94,.12);color:#1A7A5E;">Paket berhasil</span>
                                </div>
                            </div>

                            <!-- Tanggal Berakhir -->
                            <div class="pkd-tl-item">
                                <div class="pkd-tl-icon pkd-tl-icon--out"><i class="fas fa-calendar-times"></i></div>
                                <div>
                                    <p class="pkd-tl-label">TANGGAL BERAKHIR</p>
                                    <p id="expiryDate" class="pkd-tl-value"></p>
                                    <span id="expiryBadge" class="pkd-tl-badge"></span>
                                </div>
                            </div>

                            <!-- Durasi -->
                            <div class="pkd-tl-item">
                                <div class="pkd-tl-icon" style="background:rgba(122,43,74,.1);color:#7A2B4A;"><i class="fas fa-hourglass-half"></i></div>
                                <div>
                                    <p class="pkd-tl-label">DURASI PAKET</p>
                                    <p id="durationValue" class="pkd-tl-value"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div id="warningMessage" class="pkd-warning hidden">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div class="flex-1">
                            <p class="pkd-warning-title" id="warningTitle"></p>
                            <p class="pkd-warning-text" id="warningText"></p>
                        </div>
                    </div>

                    <!-- Kuota -->
                    <div class="pkd-card">
                        <p class="pkd-card-title"><i class="fas fa-dumbbell"></i> Kuota Kelas</p>
                        <div class="pkd-usage-row">
                            <span class="pkd-usage-side">Kelas digunakan</span>
                            <span id="usageText" class="pkd-usage-main"></span>
                        </div>
                        <div class="pk-meter" id="usageMeter" style="margin-top:.7rem;"></div>
                        <div class="pkd-usage-row" style="margin-top:.7rem;">
                            <span class="pkd-usage-side">Kelas tersedia</span>
                            <span id="availableBadge" class="pkd-stat-badge" style="margin-top:0;"></span>
                        </div>
                    </div>

                    <!-- Detail Cards Grid -->
                    <div class="pkd-stats">
                        <!-- HARGA -->
                        <div class="pkd-stat">
                            <p class="pkd-stat-label">HARGA</p>
                            <p id="priceValue" class="pkd-stat-value"></p>
                            <p class="pkd-stat-note">Sudah termasuk pajak</p>
                        </div>

                        <!-- METODE BAYAR -->
                        <div class="pkd-stat">
                            <p class="pkd-stat-label">METODE BAYAR</p>
                            <p id="paymentMethod" class="pkd-stat-value"></p>
                            <p id="paymentDate" class="pkd-stat-note"></p>
                        </div>

                        <!-- SISA KELAS -->
                        <div class="pkd-stat">
                            <p class="pkd-stat-label">SISA KELAS</p>
                            <p id="remainingClasses" class="pkd-stat-value"></p>
                            <p id="classType" class="pkd-stat-note"></p>
                        </div>

                        <!-- STATUS -->
                        <div class="pkd-stat">
                            <p class="pkd-stat-label">STATUS</p>
                            <p id="statusValue" class="pkd-stat-value"></p>
                            <span id="statusBadge" class="pkd-stat-badge"></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
// ===== AVAILABLE PACKAGES MODAL =====
let apmTriggerEl = null;
function openAvailablePackagesModal() {
    const modal = document.getElementById('availablePackagesModal');
    if (!modal) return;
    apmTriggerEl = document.activeElement;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('modal-open');
    requestAnimationFrame(() => { modal.classList.add('open-modal'); });
    const closeBtn = modal.querySelector('.apm-close-btn');
    if (closeBtn) closeBtn.focus();
}
function closeAvailablePackagesModal(event) {
    if (event && event.target !== event.currentTarget) return;
    const modal = document.getElementById('availablePackagesModal');
    if (!modal) return;
    modal.classList.remove('open-modal');
    document.body.classList.remove('modal-open');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (apmTriggerEl && typeof apmTriggerEl.focus === 'function') {
            apmTriggerEl.focus();
        }
        apmTriggerEl = null;
    }, 350);
}

// ===== MODAL LOGIC =====
let currentOrderId = null;

function openPackageModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('packageModal');
    const backdrop = modal.querySelector('.modal-backdrop');
    
    // Show modal and disable page interaction
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    document.body.classList.add('modal-open');
    
    // Show loading, hide content
    document.getElementById('modalLoading').style.display = 'flex';
    document.querySelectorAll('.tab-panel').forEach(p => p.style.display = 'none');
    
    // Animate in
    requestAnimationFrame(() => {
        backdrop.classList.add('active');
    });
    
    // Fetch data
    fetchPackageDetail(orderId);
}

function closePackageModal(event) {
    if (event && event.target !== event.currentTarget) return;
    
    const modal = document.getElementById('packageModal');
    const backdrop = modal.querySelector('.modal-backdrop');
    
    backdrop.classList.remove('active');
    document.body.classList.remove('modal-open');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Close on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closePackageModal();
        closeAvailablePackagesModal();
    }
});

function switchTab(tabName) {
    document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => {
        p.classList.remove('active');
        p.style.display = 'none';
    });
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
    const activePanel = document.getElementById('tab' + tabName.charAt(0).toUpperCase() + tabName.slice(1));
    activePanel.classList.add('active');
    activePanel.style.display = 'block';
}

async function fetchPackageDetail(orderId) {
    const loadingEl = document.getElementById('modalLoading');
    const contentEl = document.getElementById('modalContent');
    loadingEl.style.display = 'flex';
    loadingEl.innerHTML = '<div class="text-center"><div class="spinner mx-auto mb-3"></div><p class="pkd-loading-text">Memuat detail paket...</p></div>';
    contentEl.classList.add('hidden');
    try {
        const response = await fetch(`/member/packages/${orderId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('Failed to load');
        
        const data = await response.json();
        
        if (!data.success) throw new Error(data.message || 'Error');
        
        populateModal(data);
        
    } catch (error) {
        console.error('Error fetching package detail:', error);
        const loadingEl = document.getElementById('modalLoading');
        loadingEl.style.display = 'flex';
        loadingEl.innerHTML = `
            <div class="text-center">
                <div style="width:64px;height:64px;border-radius:999px;background:rgba(238,78,139,.1);border:1px dashed rgba(238,78,139,.4);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                    <i class="fas fa-exclamation-triangle" style="font-size:1.5rem;color:#7A2B4A;"></i>
                </div>
                <p style="font-family:Poppins,sans-serif;font-size:13.5px;font-weight:600;color:#7A2B4A;">Gagal memuat detail paket</p>
                <p style="font-family:Poppins,sans-serif;font-size:12px;color:rgba(28,28,28,.5);margin-top:4px;">Periksa koneksi lalu coba lagi.</p>
                <button onclick="openPackageModal(${orderId})" style="margin-top:14px;padding:.6rem 1.4rem;border-radius:10px;border:1px solid rgba(122,43,74,.3);background:#fff;color:#7A2B4A;font-family:Nord,Poppins,sans-serif;font-weight:700;font-size:13px;cursor:pointer;">
                    <i class="fas fa-redo" style="margin-right:6px;"></i>Coba Lagi
                </button>
            </div>
        `;
        document.getElementById('modalContent').classList.add('hidden');
    }
}

function populateModal(data) {
    const { order, package: pkg, usage, booked_schedules, attendance_history, stats } = data;
    
    // Package Name & Order Code
    document.getElementById('modalPackageName').textContent = pkg.name;
    document.getElementById('modalOrderCode').innerHTML = `<i class="fas fa-barcode mr-1"></i>${order.order_code}`;
    
    // Durasi (info waktu digabung di ringkasan)
    document.getElementById('durationValue').textContent = pkg.duration_days ? pkg.duration_days + ' hari' : 'Tak terbatas';
    
    // Warning Message (if applicable)
    const warningMsg = document.getElementById('warningMessage');
    if (!order.is_expired && order.remaining_days <= 7 && order.remaining_days > 0) {
        warningMsg.className = 'pkd-warning';
        document.getElementById('warningTitle').textContent = 'Belum dimulai';
        document.getElementById('warningText').textContent = 'Masa aktif dihitung sejak booking pertama dilakukan.';
    } else if (order.is_expired) {
        warningMsg.className = 'pkd-warning pkd-warning--danger';
        document.getElementById('warningTitle').textContent = 'Paket sudah berakhir';
        document.getElementById('warningText').textContent = 'Paket ini sudah tidak aktif. Beli paket baru untuk melanjutkan.';
    } else {
        warningMsg.className = 'pkd-warning hidden';
    }
    
    // Usage Section
    document.getElementById('usageText').textContent = `${usage.used}/${usage.total_quota} kelas`;
    // Meter berblok — sama dengan kartu pass di halaman
    const usageMeter = document.getElementById('usageMeter');
    if (usageMeter) {
        const segTotal = usage.total_quota > 0 ? Math.min(usage.total_quota, 24) : 0;
        const segLeft = segTotal > 0 ? Math.round((usage.remaining / Math.max(1, usage.total_quota)) * segTotal) : 0;
        let meterHtml = '';
        for (let i = 0; i < segTotal; i++) {
            meterHtml += `<span class="pk-seg${i < segLeft ? ' is-left' : ''}"></span>`;
        }
        usageMeter.innerHTML = meterHtml;
        usageMeter.style.display = segTotal > 0 ? 'flex' : 'none';
    }
    const availableBadge = document.getElementById('availableBadge');
    if (usage.remaining <= 0) {
        availableBadge.className = 'pkd-stat-badge';
        availableBadge.style.cssText = 'margin-top:0;background:rgba(192,51,79,.12);color:#C0334F;';
        availableBadge.textContent = 'Habis';
    } else if (usage.remaining <= 2) {
        availableBadge.className = 'pkd-stat-badge';
        availableBadge.style.cssText = 'margin-top:0;background:rgba(176,125,0,.14);color:#8A6000;';
        availableBadge.textContent = 'Hampir habis';
    } else {
        availableBadge.className = 'pkd-stat-badge';
        availableBadge.style.cssText = 'margin-top:0;background:rgba(26,122,94,.12);color:#1A7A5E;';
        availableBadge.textContent = 'Tersedia';
    }
    
    // Detail Cards
    document.getElementById('priceValue').textContent = pkg.price_formatted || order.amount_formatted;
    document.getElementById('paymentMethod').textContent = order.payment_method || 'Menunggu Pembayaran';
    document.getElementById('paymentDate').textContent = order.created_at_short || order.created_at;
    document.getElementById('remainingClasses').textContent = usage.remaining;
    document.getElementById('classType').textContent = pkg.is_exclusive ? 'Eksklusif' : 'Reguler';
    
    // Status
    document.getElementById('statusValue').textContent = order.is_expired ? 'Expired' : 'Aktif';
    const statusBadge = document.getElementById('statusBadge');
    if (order.is_expired) {
        statusBadge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white';
        statusBadge.textContent = 'Tidak aktif';
    } else if (!order.expired_at) {
        statusBadge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-500 text-white';
        statusBadge.textContent = 'Belum dimulai';
    } else {
        statusBadge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white';
        statusBadge.textContent = 'Aktif';
    }
    
    // Info waktu di ringkasan (tanggal beli + berakhir + badge)
    document.getElementById('purchaseDate').textContent = order.created_at;
    document.getElementById('expiryDate').textContent = order.expired_at_full || 'Belum dimulai';
    const expiryBadge = document.getElementById('expiryBadge');
    if (order.is_expired) {
        expiryBadge.className = 'inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700';
        expiryBadge.textContent = 'Paket berakhir';
    } else if (!order.expired_at) {
        expiryBadge.className = 'inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700';
        expiryBadge.textContent = 'Belum dimulai';
    } else {
        expiryBadge.className = 'inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
        expiryBadge.textContent = `${order.remaining_days} hari lagi`;
    }
    
    // Hide loading, show content
    document.getElementById('modalLoading').style.display = 'none';
    document.getElementById('modalContent').classList.remove('hidden');
}

// ===== SIDEBAR TOGGLE FUNCTION =====
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger-btn');
        if (!sidebar) return;

        const willOpen = !sidebar.classList.contains('active') && !sidebar.classList.contains('open');
        // toggle both class names to support pages using either 'active' or 'open'
        sidebar.classList.toggle('active');
        sidebar.classList.toggle('open');

        if (willOpen) {
            document.body.classList.add('sidebar-open');
            document.body.style.overflow = 'hidden';
            if (hamburger) hamburger.style.display = 'none';
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = 'none');
        } else {
            document.body.classList.remove('sidebar-open');
            document.body.style.overflow = '';
            if (hamburger) { hamburger.style.display = ''; hamburger.innerHTML = '<i class="fas fa-bars"></i>'; }
            document.querySelectorAll('.hamburger-btn, .more-btn, .dots-btn, .three-dots, .more-menu-btn').forEach(el => el.style.display = '');
        }
}

// Close sidebar when clicking on a nav link
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up sidebar');
    
    const navLinks = document.querySelectorAll('#sidebar nav a');
    console.log('Found nav links:', navLinks.length);
    
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            }
        });
    });
});

// Reset sidebar on window resize
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger-btn');
    
    if (window.innerWidth > 768 && sidebar) {
        sidebar.classList.remove('active');
        if (hamburger) hamburger.style.display = '';
        if (hamburger) hamburger.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.style.overflow = '';
    }
});

</script>

</body>
</html>