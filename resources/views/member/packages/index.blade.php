<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paket Saya - FTM Society</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* ===============================================================
           FTM SOCIETY — FINAL MEMBERSHIP CARD PICKER
           =============================================================== */
        /* ===============================================================
           FTM SOCIETY — MEMBERSHIP PACKAGE PICKER
           Premium SaaS-style pricing modal
           =============================================================== */
        #availablePackagesModal { z-index: 9999 !important; }
        #availablePackagesModal .apm-backdrop {
            display: flex; align-items: center; justify-content: center;
            position: fixed; inset: 0;
            background: rgba(23, 10, 15, 0);
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            transition: opacity 0.35s ease, background 0.35s ease, backdrop-filter 0.35s ease;
            z-index: 9999; padding: 1.5rem; opacity: 0; pointer-events: none;
        }
        #availablePackagesModal.open-modal .apm-backdrop {
            opacity: 1; pointer-events: auto;
            background: rgba(23, 10, 15, 0.55);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        #availablePackagesModal .apm-container {
            width: 100%; max-width: 1400px; max-height: 90vh;
            border-radius: 32px;
            background: #FFFFFF;
            box-shadow: 0 40px 90px -24px rgba(143, 41, 87, 0.35), 0 12px 32px rgba(23, 10, 15, 0.12);
            overflow: hidden; display: flex; flex-direction: column;
            margin: auto; position: relative; contain: layout style;
            transform: translateY(36px) scale(0.96);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease;
            opacity: 0;
        }
        #availablePackagesModal.open-modal .apm-container {
            transform: translateY(0) scale(1); opacity: 1;
        }
        @media (max-width: 768px) {
            #availablePackagesModal .apm-backdrop { padding: 0; align-items: flex-end; }
            #availablePackagesModal .apm-container {
                max-width: 100%; max-height: 96vh;
                border-radius: 28px 28px 0 0;
                margin-bottom: 0; margin-top: auto;
            }
        }
        /* ---------- Header ---------- */
        #availablePackagesModal .apm-header {
            position: relative; flex-shrink: 0;
            min-height: 96px; padding: 1.4rem 2.25rem;
            display: flex; align-items: center; justify-content: space-between;
            background-image: linear-gradient(135deg, #D93D7A 0%, #8F2957 100%);
            overflow: hidden;
        }
        #availablePackagesModal .apm-header::before,
        #availablePackagesModal .apm-header::after {
            content: ''; position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.07); pointer-events: none;
        }
        #availablePackagesModal .apm-header::before {
            width: 240px; height: 240px; top: -120px; right: -60px;
        }
        #availablePackagesModal .apm-header::after {
            width: 150px; height: 150px; bottom: -90px; left: 32%;
            background: rgba(255,255,255,0.05);
        }
        #availablePackagesModal .apm-header-left {
            display: flex; align-items: center; gap: 1.15rem;
            position: relative; z-index: 1; min-width: 0;
        }
        #availablePackagesModal .apm-header-icon {
            width: 50px; height: 50px; border-radius: 16px;
            background: rgba(255,255,255,0.16);
            border: 1px solid rgba(255,255,255,0.25);
            display: flex; align-items: center; justify-content: center;
            color: #FFF; font-size: 1.3rem; flex-shrink: 0;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.22);
        }
        #availablePackagesModal .apm-header-text h2 {
            color: #FFF; font-family: 'Inter','Poppins',sans-serif;
            font-weight: 800; font-size: 1.5rem; letter-spacing: -0.02em; line-height: 1.2;
            margin: 0;
        }
        #availablePackagesModal .apm-header-sub {
            color: rgba(255,255,255,0.75); font-family: 'Inter',sans-serif;
            font-size: 0.875rem; margin-top: 4px; line-height: 1.5;
        }
        #availablePackagesModal .apm-close-btn {
            width: 44px; height: 44px; border-radius: 50%;
            background: rgba(255,255,255,0.16);
            border: 1px solid rgba(255,255,255,0.28);
            color: #FFF; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: transform 0.25s ease, background 0.25s ease;
            font-size: 1rem; flex-shrink: 0; position: relative; z-index: 1;
        }
        #availablePackagesModal .apm-close-btn:hover {
            background: rgba(255,255,255,0.32); transform: rotate(90deg);
        }
        #availablePackagesModal .apm-close-btn:active { transform: rotate(90deg) scale(0.92); }
        #availablePackagesModal .apm-close-btn:focus-visible {
            outline: 2px solid #FFF; outline-offset: 3px;
        }
        /* ---------- Body ---------- */
        #availablePackagesModal .apm-body {
            flex: 1; overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain; contain: layout style;
            background: #FFF8F8; padding: 32px;
        }
        #availablePackagesModal .apm-body::-webkit-scrollbar { width: 6px; }
        #availablePackagesModal .apm-body::-webkit-scrollbar-track { background: #FFF8F8; }
        #availablePackagesModal .apm-body::-webkit-scrollbar-thumb { background: #F0CBDC; border-radius: 10px; }
        #availablePackagesModal .apm-body::-webkit-scrollbar-thumb:hover { background: #E3A8C6; }

        /* ---------- Grid ---------- */
        #availablePackagesModal .apm-grid {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 32px; align-items: stretch;
        }
        @media (max-width: 1024px) { #availablePackagesModal .apm-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px) {
            #availablePackagesModal .apm-grid { grid-template-columns: 1fr; gap: 20px; }
            #availablePackagesModal .apm-body { padding: 20px; }
            #availablePackagesModal .apm-header { padding: 1.1rem 1.25rem; min-height: 0; }
            #availablePackagesModal .apm-header-icon { display: none; }
            #availablePackagesModal .apm-header-text h2 { font-size: 1.25rem; }
        }
        /* ---------- Cards ---------- */
        #availablePackagesModal .apm-card {
            background: #FFFFFF;
            border: 1px solid rgba(217, 61, 122, 0.12);
            border-radius: 28px;
            box-shadow: 0 2px 10px rgba(143, 41, 87, 0.05);
            padding: 32px; display: flex; flex-direction: column;
            height: 100%; position: relative;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        #availablePackagesModal .apm-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px -16px rgba(143, 41, 87, 0.18), 0 8px 20px rgba(143, 41, 87, 0.06);
            border-color: rgba(217, 61, 122, 0.28);
        }
        /* Stagger entrance */
        #availablePackagesModal.open-modal .apm-card {
            animation: apm-card-in 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        }
        #availablePackagesModal.open-modal .apm-card:nth-child(1) { animation-delay: 0.04s; }
        #availablePackagesModal.open-modal .apm-card:nth-child(2) { animation-delay: 0.09s; }
        #availablePackagesModal.open-modal .apm-card:nth-child(3) { animation-delay: 0.14s; }
        #availablePackagesModal.open-modal .apm-card:nth-child(4) { animation-delay: 0.19s; }
        #availablePackagesModal.open-modal .apm-card:nth-child(5) { animation-delay: 0.24s; }
        #availablePackagesModal.open-modal .apm-card:nth-child(6) { animation-delay: 0.29s; }
        @keyframes apm-card-in {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ---------- Exclusive card ---------- */
        #availablePackagesModal .apm-card.is-exclusive {
            border: 2px solid transparent;
            background:
                linear-gradient(#FFFFFF, #FFFFFF) padding-box,
                linear-gradient(135deg, #D93D7A 0%, #C2185B 55%, #8F2957 100%) border-box;
            box-shadow: 0 4px 24px rgba(217, 61, 122, 0.16);
            padding-top: 44px;
        }
        #availablePackagesModal .apm-card.is-exclusive:hover {
            box-shadow: 0 28px 56px -16px rgba(217, 61, 122, 0.35), 0 10px 24px rgba(217, 61, 122, 0.12);
            border-color: transparent;
        }
        #availablePackagesModal .apm-ribbon {
            position: absolute; top: -15px; left: 50%; transform: translateX(-50%);
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 18px; border-radius: 999px;
            background: linear-gradient(135deg, #D93D7A, #8F2957);
            color: #FFF; font-family: 'Inter',sans-serif;
            font-size: 11px; font-weight: 700; letter-spacing: 0.1em;
            text-transform: uppercase; white-space: nowrap;
            box-shadow: 0 6px 18px rgba(217, 61, 122, 0.4);
            z-index: 2;
        }

        /* ---------- Badge ---------- */
        #availablePackagesModal .apm-badge {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 6px 16px; border-radius: 999px;
            font-family: 'Inter',sans-serif; font-size: 11px;
            font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase;
            align-self: flex-start; min-height: 30px;
        }
        #availablePackagesModal .apm-badge-exclusive {
            background: linear-gradient(135deg, #D93D7A, #8F2957); color: #FFF;
            box-shadow: 0 4px 12px rgba(217, 61, 122, 0.3);
        }
        #availablePackagesModal .apm-badge-regular { background: #FBE8F0; color: #8F2957; }

        /* ---------- Name ---------- */
        #availablePackagesModal .apm-card-name {
            font-family: 'Inter','Poppins',sans-serif;
            font-size: 34px; font-weight: 700; color: #2B2B2B;
            line-height: 1.15; letter-spacing: -0.02em;
            margin: 20px 0 6px;
            border: none !important; outline: none !important;
            background: transparent !important; box-shadow: none !important; padding: 0 !important;
        }

        /* ---------- Price ---------- */
        .apm-card-price,
        #availablePackagesModal .apm-card-price,
        .apm-card [class*="price"],
        .apm-card [class*="harga"],
        .apm-card [class*="Price"],
        .apm-card [class*="Harga"] {
            display: flex; align-items: baseline; gap: 4px;
            padding: 0 !important; margin: 8px 0 24px;
            border: none !important; outline: none !important;
            background: transparent !important; box-shadow: none !important;
            border-radius: 0 !important;
        }
        #availablePackagesModal .apm-card-price-currency {
            font-family: 'Inter',sans-serif; font-size: 20px;
            font-weight: 600; color: #D93D7A; line-height: 1;
            border: none; outline: none; background: transparent; box-shadow: none;
        }
        #availablePackagesModal .apm-card-price-amount {
            font-family: 'Inter',sans-serif; font-weight: 800;
            font-size: 60px; letter-spacing: -0.04em; line-height: 1; color: #D93D7A;
            border: none; outline: none; background: transparent; box-shadow: none;
        }

        /* ---------- Chips ---------- */
        #availablePackagesModal .apm-chips {
            display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 24px;
        }
        #availablePackagesModal .apm-chip {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 16px; border-radius: 16px;
            background: #FBEDF3; border: 1px solid rgba(217, 61, 122, 0.1);
            font-family: 'Inter',sans-serif; font-size: 14px;
            font-weight: 600; color: #8F2957; min-height: 40px;
        }
        #availablePackagesModal .apm-chip i { color: #D93D7A; font-size: 14px; }

        /* ---------- Description ---------- */
        #availablePackagesModal .apm-desc {
            margin: 0 0 24px; padding: 0;
            font-family: 'Inter',sans-serif; font-size: 16px;
            font-weight: 400; color: #6B6B6B; line-height: 1.6;
        }

        /* ---------- Benefits ---------- */
        #availablePackagesModal .apm-features { flex: 1; margin-bottom: 28px; }
        #availablePackagesModal .apm-feature-item {
            display: flex; align-items: center; gap: 14px; margin-bottom: 16px;
        }
        #availablePackagesModal .apm-feature-item:last-child { margin-bottom: 0; }
        #availablePackagesModal .apm-feature-icon {
            width: 20px; height: 20px; border-radius: 50%;
            background: rgba(16, 185, 129, 0.12);
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        #availablePackagesModal .apm-feature-icon i { color: #10B981; font-size: 11px; }
        #availablePackagesModal .apm-feature-text {
            font-family: 'Inter',sans-serif; font-size: 15px;
            font-weight: 400; color: #2B2B2B; line-height: 1.4;
        }

        /* ---------- CTA ---------- */
        #availablePackagesModal .apm-cta {
            display: flex; align-items: center; justify-content: center;
            gap: 10px; width: 100%; height: 58px; padding: 0 24px;
            border-radius: 18px; border: none; cursor: pointer;
            font-family: 'Inter',sans-serif; font-size: 15px;
            font-weight: 700; letter-spacing: 0.02em; text-decoration: none;
            background: linear-gradient(135deg, #D93D7A, #8F2957);
            color: #FFF; margin-top: auto;
            box-shadow: 0 8px 20px rgba(217, 61, 122, 0.3);
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }
        #availablePackagesModal .apm-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(217, 61, 122, 0.42);
            filter: brightness(1.05);
        }
        #availablePackagesModal .apm-cta:active { transform: translateY(0) scale(0.98); }
        #availablePackagesModal .apm-cta:focus-visible {
            outline: 3px solid rgba(217, 61, 122, 0.45); outline-offset: 2px;
        }
        #availablePackagesModal .apm-cta i { transition: transform 0.25s ease; }
        #availablePackagesModal .apm-cta:hover i.fa-arrow-right { transform: translateX(4px); }

        /* ---------- Trust footer ---------- */
        #availablePackagesModal .apm-footer {
            display: flex; align-items: center; justify-content: center;
            gap: 10px; padding: 18px 32px 30px;
            background: #FFF8F8; flex-shrink: 0;
        }
        #availablePackagesModal .apm-footer-icon { color: #10B981; font-size: 13px; }
        #availablePackagesModal .apm-footer-text {
            font-family: 'Inter',sans-serif; font-size: 13px;
            color: #6B6B6B; font-weight: 500;
        }
        #availablePackagesModal .apm-footer-text strong { color: #2B2B2B; font-weight: 700; }

        /* ---------- Small screens ---------- */
        @media (max-width: 640px) {
            #availablePackagesModal .apm-card-name { font-size: 28px; }
            #availablePackagesModal .apm-card-price-amount { font-size: 44px; }
            #availablePackagesModal .apm-card-price-currency { font-size: 16px; }
            #availablePackagesModal .apm-card { padding: 24px; }
            #availablePackagesModal .apm-card.is-exclusive { padding-top: 44px; }
            #availablePackagesModal .apm-footer { padding: 16px 20px 24px; }
        }

        /* ===============================================================
           DESKTOP REFINEMENT (lg / xl / 2xl) — ~20-25% lebih ringkas
           Tablet & mobile tidak terpengaruh.
           =============================================================== */
        @media (min-width: 1024px) {
            /* Modal — lebih kecil & proporsional di tengah layar */
            #availablePackagesModal .apm-container {
                max-width: 1280px;
                max-height: 82vh;
                box-shadow: 0 34px 80px -24px rgba(143, 41, 87, 0.32), 0 10px 28px rgba(23, 10, 15, 0.10);
            }

            /* Header — lebih ramping */
            #availablePackagesModal .apm-header {
                min-height: 88px;
                padding: 1.1rem 2rem;
            }
            #availablePackagesModal .apm-header-left { gap: 0.9rem; }
            #availablePackagesModal .apm-header-icon {
                width: 42px; height: 42px; border-radius: 13px; font-size: 1.05rem;
            }
            #availablePackagesModal .apm-header-text h2 { font-size: 1.3rem; }
            #availablePackagesModal .apm-header-sub { font-size: 0.8rem; margin-top: 2px; }
            #availablePackagesModal .apm-close-btn { width: 38px; height: 38px; font-size: 0.9rem; }

            /* Body & grid — ruang napas di sisi kanan-kiri */
            #availablePackagesModal .apm-body { padding: 28px 32px; }
            #availablePackagesModal .apm-grid { gap: 26px; }

            /* Card — lebih ramping */
            #availablePackagesModal .apm-card { padding: 26px; border-radius: 24px; }
            #availablePackagesModal .apm-card.is-exclusive { padding-top: 38px; }

            /* Badge & ribbon */
            #availablePackagesModal .apm-badge {
                padding: 4px 14px; font-size: 10px; min-height: 26px; gap: 5px;
            }
            #availablePackagesModal .apm-badge i { font-size: 10px; }
            #availablePackagesModal .apm-ribbon {
                top: -12px; padding: 5px 15px; font-size: 10px; gap: 6px;
            }

            /* Nama paket */
            #availablePackagesModal .apm-card-name { font-size: 31px; margin: 16px 0 4px; }

            /* Harga */
            #availablePackagesModal .apm-card-price { margin: 6px 0 18px; gap: 3px; }
            #availablePackagesModal .apm-card-price-amount { font-size: 52px; }
            #availablePackagesModal .apm-card-price-currency { font-size: 18px; }

            /* Chip info */
            #availablePackagesModal .apm-chips { gap: 10px; margin-bottom: 18px; }
            #availablePackagesModal .apm-chip {
                min-height: 40px; padding: 8px 14px; font-size: 13px; border-radius: 14px;
            }

            /* Deskripsi — maksimal 2 baris */
            #availablePackagesModal .apm-desc { font-size: 15px; margin: 0 0 16px; line-height: 1.6; }

            /* Benefit */
            #availablePackagesModal .apm-features { margin-bottom: 18px; }
            #availablePackagesModal .apm-feature-item { gap: 11px; margin-bottom: 12px; }
            #availablePackagesModal .apm-feature-icon { width: 18px; height: 18px; }
            #availablePackagesModal .apm-feature-icon i { font-size: 10px; }
            #availablePackagesModal .apm-feature-text { font-size: 14px; }

            /* Tombol */
            #availablePackagesModal .apm-cta { height: 52px; border-radius: 16px; font-size: 14px; }

            /* Footer trust */
            #availablePackagesModal .apm-footer { padding: 14px 28px 24px; }
        }

        /* ---------- Reduced motion ---------- */
        @media (prefers-reduced-motion: reduce) {
            #availablePackagesModal *,
            #availablePackagesModal *::before,
            #availablePackagesModal *::after {
                transition: none !important; animation: none !important;
            }
        }

        .progress-ring {
            transform: rotate(-90deg);
        }
        
        .progress-ring-circle {
            transition: stroke-dashoffset 0.5s ease;
        }

        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .gradient-border {
            position: relative;
            background: white;
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 0.75rem;
            padding: 2px;
            background: linear-gradient(135deg, #EE4E8B 0%, #7A2B4A 100%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .btn-ftm-pink {
            background: #EE4E8B !important; color: #FFFFFF !important;
            border: none !important; border-radius: 12px !important;
            font-family: 'Poppins', sans-serif !important; font-weight: 600 !important;
            box-shadow: 0 4px 14px rgba(238, 78, 139, 0.3) !important;
            transition: all 0.3s ease !important; cursor: pointer;
        }
        .btn-ftm-pink:hover {
            background: #7A2B4A !important; transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(122, 43, 74, 0.4) !important;
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
        
        /* Hide page content when modal is open */
        body.modal-open main > *:not(#packageModal) {
            filter: blur(4px);
            pointer-events: none;
        }
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

        /* ═══════════════════════════════════════════ RESPONSIVE SIDEBAR ═══════════════════════════════════════════ */
        .sidebar {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 20;
            backdrop-filter: blur(4px);
        }

        .hamburger-btn {
            display: none !important;
            position: fixed !important;
            top: 1rem !important;
            left: 1rem !important;
            z-index: 9999 !important;
            width: 3rem !important;
            height: 3rem !important;
            background: linear-gradient(135deg, #7A2B4A 0%, #EE4E8B 100%) !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 4px 12px rgba(122, 43, 74, 0.35) !important;
            cursor: pointer !important;
            transition: all 0.2s !important;
            font-size: 1.25rem !important;
        }

        .hamburger-btn:hover {
            background: linear-gradient(135deg, #5A1F3A 0%, #B83863 100%) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(122, 43, 74, 0.45) !important;
        }

        .hamburger-btn:active {
            transform: translateY(0) !important;
        }

        @media (max-width: 768px) {
            .hamburger-btn {
                display: flex !important;
            }

            .sidebar-overlay.active {
                display: block !important;
            }
        }
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

    {{-- ================= HEADER (Greeting) ================= --}}
    <div class="bg-white rounded-2xl shadow-[0_2px_12px_rgba(122,43,74,0.06)] border border-light-pink/20 p-5 md:p-7 mb-8 mt-14 md:mt-0">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-baseline gap-1.5 mb-1">
                    <span class="font-nord font-black text-primary text-xl md:text-2xl">Assalamu'alaikum</span>
                    <span class="font-poppins text-dark font-semibold text-base md:text-lg">, {{ auth('customer')->user()->name ?? 'Member' }}</span>
                </div>
                <p class="font-poppins text-dark/45 text-sm leading-relaxed">"Setiap langkah kecil membawamu lebih dekat ke versi terbaik dirimu."</p>
                <p class="font-poppins text-dark/25 text-xs mt-1">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="flex items-center gap-4">

                @php $initial = strtoupper(substr(auth('customer')->user()->name ?? 'M', 0, 1)); @endphp
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-nord font-bold text-sm shadow-md border-2 border-white flex-shrink-0">
                    {{ $initial }}
                </div>
            </div>
        </div>
    </div>

    {{-- ================= PAGE TITLE ================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="font-nord font-bold text-[30px] md:text-[32px] text-dark leading-tight">Paket Saya</h1>
            <p class="font-poppins text-dark/45 text-[15px] mt-1.5">Kelola dan pantau paket membership Anda</p>
        </div>
        <button type="button" onclick="openAvailablePackagesModal()"
           class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl font-poppins font-medium text-[14px] text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
           style="background: linear-gradient(135deg, #EE4E8B, #C2185B, #7A2B4A); box-shadow: 0 4px 16px rgba(238,78,139,0.3);">
            <i class="fas fa-plus-circle text-sm"></i>
            Beli Paket Baru
        </button>
    </div>

    {{-- ================= ACTIVE PACKAGES ================= --}}
    @if($activePackages?->count())
        <div class="grid grid-cols-1 {{ $activePackages->count() >= 2 ? 'md:grid-cols-2' : 'md:grid-cols-1' }} gap-6">
        @foreach($activePackages as $order)
            @php
                $pkg = $order->package;
                $isExpired = method_exists($order, 'isExpired') ? $order->isExpired() : false;
                $remainingDays = method_exists($order, 'getRemainingDays') ? $order->getRemainingDays() : 0;
                $remainingTime = method_exists($order, 'getRemainingTime') ? $order->getRemainingTime() : '-';
                $totalDays = $pkg->duration_days ?? 30;
                $progressPercentage = $remainingDays > 0 ? (($totalDays - $remainingDays) / $totalDays) * 100 : 100;
                $statusColor = $remainingDays > 10 ? 'green' : ($remainingDays > 3 ? 'yellow' : 'red');
            @endphp

            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] overflow-hidden relative">
                {{-- Top accent bar --}}
                <div class="h-1 w-full bg-gradient-to-r from-primary to-secondary"></div>

                <div class="p-6 md:p-7">
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between mb-5">
                        <div>
                            <div class="flex items-center gap-3 mb-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full font-poppins font-semibold text-[11px] uppercase tracking-wider {{ $isExpired ? 'bg-[rgba(238,78,139,0.1)] text-secondary' : 'bg-[rgba(26,122,94,0.1)] text-accent' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $isExpired ? 'bg-secondary' : 'bg-accent' }}"></span>
                                    {{ $isExpired ? 'Kedaluwarsa' : 'Aktif' }}
                                </span>
                                <span class="font-poppins text-dark/30 text-[12px] font-mono tracking-wider">#{{ $order->order_code }}</span>
                            </div>
                            <h3 class="font-nord font-bold text-[22px] md:text-[24px] text-dark leading-tight">{{ $pkg->name ?? 'Package' }}</h3>
                        </div>
                    </div>

                    {{-- Main Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        {{-- Days Left --}}
                        <div class="bg-cream rounded-xl p-5">
                            <p class="font-poppins font-semibold text-[11px] uppercase tracking-widest text-dark/45 mb-2">Sisa Hari</p>
                            <p class="font-nord font-bold text-[36px] md:text-[40px] leading-none" style="color: {{ $statusColor === 'green' ? '#1A7A5E' : ($statusColor === 'yellow' ? '#1D5A4B' : '#7A2B4A') }}">
                                {{ $isExpired ? 0 : max($remainingDays, 0) }}
                            </p>
                            <p class="font-poppins text-dark/35 text-[13px] mt-1.5">{{ $remainingTime }}</p>
                        </div>

                        {{-- Progress --}}
                        <div class="bg-cream rounded-xl p-5">
                            <p class="font-poppins font-semibold text-[11px] uppercase tracking-widest text-dark/45 mb-2">Progress Pemakaian</p>
                            @php
                                $totalQuota = $pkg->quota ?? 0;
                                $classesLeft = $order->remaining_classes ?? $order->remaining_sessions ?? $totalQuota;
                                $used = max(0, $totalQuota - $classesLeft);
                                $usagePercent = $totalQuota > 0 ? ($used / $totalQuota) * 100 : 0;
                            @endphp
                            <p class="font-nord font-bold text-[36px] md:text-[40px] leading-none text-dark">{{ $used }}<span class="font-poppins text-lg text-dark/35 font-normal">/{{ $totalQuota }}</span></p>
                            <div class="mt-3 h-2.5 bg-white/80 rounded-full overflow-hidden shadow-inner">
                                <div class="h-full rounded-full bg-gradient-to-r from-primary to-secondary transition-all duration-700 shadow-[inset_0_1px_2px_rgba(255,255,255,0.3)]"
                                     style="width: {{ min($usagePercent, 100) }}%"></div>
                            </div>
                            <p class="font-poppins text-dark/35 text-[13px] mt-1.5">{{ $classesLeft }} kelas tersisa</p>
                        </div>
                    </div>

                    {{-- Start / Expire Info --}}
                    <div class="flex flex-wrap gap-x-8 gap-y-2 font-poppins text-[14px] text-dark/50 mb-5">
                        <span><span class="font-medium text-dark">Mulai:</span> {{ $order->created_at?->format('d M Y') ?? '-' }}</span>
                        <span><span class="font-medium text-dark">Berakhir:</span> {{ $order->expired_at ? \Carbon\Carbon::parse($order->expired_at)->format('d M Y') : 'Tak Terbatas' }}</span>
                    </div>

                    {{-- Warning for expiring --}}
                    @if(!$isExpired && $remainingDays <= 7 && $remainingDays > 0)
                        <div class="mb-5 p-3.5 bg-[rgba(238,78,139,0.06)] border border-[rgba(238,78,139,0.15)] rounded-xl flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-secondary mt-0.5 text-sm"></i>
                            <div>
                                <p class="font-poppins font-semibold text-[13px] text-secondary">Paket segera berakhir!</p>
                                <p class="font-poppins text-[12px] text-secondary/60 mt-0.5">Perpanjang sekarang untuk terus menikmati akses</p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgba(122,43,74,0.06)] border border-[rgba(238,78,139,0.1)] p-10 md:p-14 text-center">
            <div class="w-20 h-20 rounded-full bg-[rgba(238,78,139,0.08)] flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-box-open text-3xl text-secondary"></i>
            </div>
            <h3 class="font-nord font-bold text-[22px] text-dark mb-2">Belum Ada Paket Aktif</h3>
            <p class="font-poppins text-dark/45 text-[15px] mb-7">Mulai perjalanan fitness-mu dengan membeli paket pertama</p>
            <button type="button" onclick="openAvailablePackagesModal()"
               class="inline-flex items-center gap-2.5 px-8 py-3.5 rounded-xl font-poppins font-medium text-[14px] text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
               style="background: linear-gradient(135deg, #EE4E8B, #C2185B, #7A2B4A); box-shadow: 0 4px 16px rgba(238,78,139,0.3);">
                <i class="fas fa-plus-circle text-sm"></i>
                Lihat Paket
            </button>
        </div>
    @endif

    {{-- ================= RECENT HISTORY ================= --}}
    @if($pastPackages?->count())
    <div class="mt-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-nord font-semibold text-[22px] text-dark">Riwayat</h2>
            <span class="font-poppins bg-[rgba(238,78,139,0.1)] text-secondary text-[12px] font-semibold px-3.5 py-1.5 rounded-full">{{ $pastPackages->count() }} item</span>
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
        <div class="text-center mt-5">
            <button class="font-poppins font-medium text-[14px] text-primary hover:text-secondary transition-colors duration-150 inline-flex items-center gap-1.5">
                Lihat Semua Riwayat
                <i class="fas fa-arrow-right text-[12px]"></i>
            </button>
        </div>
        @endif
    </div>
    @endif

</main>
</div>

@if(isset($availablePackages) && $availablePackages->count() > 0)
<div id="availablePackagesModal" class="fixed inset-0 z-[9999] hidden">
    <div class="apm-backdrop" onclick="closeAvailablePackagesModal(event)">
        <div class="apm-container" onclick="event.stopPropagation()" role="dialog" aria-modal="true" aria-label="Pilih Paket Membership">
            <header class="apm-header">
                <div class="apm-header-left">
                    <div class="apm-header-icon" aria-hidden="true"><i class="fas fa-crown"></i></div>
                    <div class="apm-header-text">
                        <h2>Membership Packages</h2>
                        <p class="apm-header-sub">Pilih paket membership yang paling sesuai untuk perjalanan fitness Anda.</p>
                    </div>
                </div>
                <button onclick="closeAvailablePackagesModal()" class="apm-close-btn" title="Tutup" aria-label="Tutup">
                    <i class="fas fa-times"></i>
                </button>
            </header>
            <div class="apm-body">
                <div class="apm-grid">
                    @foreach($availablePackages as $package)
                    <article class="apm-card {{ $package->is_exclusive ? 'is-exclusive' : '' }}">
                        @if($package->is_exclusive)
                        <span class="apm-ribbon" aria-hidden="true"><i class="fas fa-star"></i> Most Popular</span>
                        <span class="apm-badge apm-badge-exclusive"><i class="fas fa-crown" aria-hidden="true"></i> Eksklusif</span>
                        @else
                        <span class="apm-badge apm-badge-regular"><i class="fas fa-heart" aria-hidden="true"></i> Reguler</span>
                        @endif
                        <h3 class="apm-card-name">{{ $package->name }}</h3>
                        <div class="apm-card-price">
                            <span class="apm-card-price-currency">Rp</span>
                            <span class="apm-card-price-amount">{{ number_format($package->price, 0, ',', '.') }}</span>
                        </div>
                        @if($package->duration_days || $package->quota)
                        <div class="apm-chips">
                            @if($package->duration_days)
                            <span class="apm-chip"><i class="fas fa-calendar" aria-hidden="true"></i> {{ $package->duration_days }} Hari</span>
                            @endif
                            @if($package->quota)
                            <span class="apm-chip"><i class="fas fa-dumbbell" aria-hidden="true"></i> {{ $package->quota }} Sesi</span>
                            @endif
                        </div>
                        @endif
                        @if($package->description)
                        <p class="apm-desc">{{ $package->description }}</p>
                        @endif
                        <div class="apm-features">
                            @if($package->quota)
                            <div class="apm-feature-item">
                                <span class="apm-feature-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                                <span class="apm-feature-text">{{ $package->quota }} sesi tersedia</span>
                            </div>
                            @endif
                            @if($package->duration_days)
                            <div class="apm-feature-item">
                                <span class="apm-feature-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                                <span class="apm-feature-text">Valid {{ $package->duration_days }} hari</span>
                            </div>
                            @endif
                            <div class="apm-feature-item">
                                <span class="apm-feature-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                                <span class="apm-feature-text">Akses ke semua fasilitas</span>
                            </div>
                        </div>
                        <a href="{{ route('join.package', ['package' => $package->slug ?? $package->id]) }}" class="apm-cta">
                            <span>Beli Sekarang</span>
                            <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </article>
                    @endforeach
                </div>
            </div>
            <footer class="apm-footer">
                <i class="fas fa-lock apm-footer-icon" aria-hidden="true"></i>
                <span class="apm-footer-text"><strong>Pembayaran Aman</strong> · Didukung Midtrans</span>
            </footer>
        </div>
    </div>
</div>
@endif

<!-- ========================================
     PACKAGE DETAIL MODAL - PROFESSIONAL DESIGN
======================================== -->
<div id="packageModal" class="fixed inset-0 z-50 hidden">
    <div class="modal-backdrop absolute inset-0 bg-black/60 flex items-end md:items-center justify-center" onclick="closePackageModal(event)">
        <div class="modal-content bg-white w-full md:max-w-2xl md:rounded-2xl rounded-t-3xl shadow-2xl max-h-[95vh] md:max-h-[90vh] flex flex-col" onclick="event.stopPropagation()">
            
            <!-- Modal Header - Gradient -->
            <div class="bg-gradient-to-r from-primary-dark to-primary p-4 md:p-6 shrink-0 rounded-t-3xl md:rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <button onclick="closePackageModal()" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                        <i class="fas fa-arrow-left text-white"></i>
                    </button>
                    <h2 class="text-white font-bold text-lg">Detail Paket</h2>
                    <button class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition">
                        <i class="fas fa-share-alt text-white"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body - Scrollable -->
            <div class="flex-1 overflow-y-auto bg-cream">
                <!-- Loading State -->
                <div id="modalLoading" class="flex items-center justify-center min-h-[400px]">
                    <div class="text-center">
                        <div class="spinner mx-auto mb-3"></div>
                        <p class="text-sm text-cream0">Memuat detail paket...</p>
                    </div>
                </div>

                <!-- Content Container -->
                <div id="modalContent" class="hidden p-4 md:p-6 space-y-4">
                    
                    <!-- Package Info Card -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h3 id="modalPackageName" class="text-xl font-bold text-primary-dark mb-2">Loading...</h3>
                        <p id="modalOrderCode" class="text-sm text-cream0 mb-4"></p>
                        
                        <!-- Info Boxes Grid -->
                        <div class="grid grid-cols-3 gap-3" id="infoBoxes">
                            <!-- Will be populated by JS -->
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div id="warningMessage" class="hidden bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-amber-900" id="warningTitle"></p>
                                <p class="text-xs text-amber-700 mt-1" id="warningText"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Usage Section -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-dark">Kelas digunakan</h4>
                            <span id="usageText" class="text-sm font-bold text-primary-dark"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-cream0">Kelas tersedia</span>
                            <span id="availableBadge" class="px-3 py-1 rounded-full text-xs font-bold"></span>
                        </div>
                    </div>

                    <!-- Detail Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- HARGA -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">HARGA</p>
                            <p id="priceValue" class="text-2xl font-bold mb-1"></p>
                            <p class="text-xs text-white/70">Sudah termasuk pajak</p>
                        </div>

                        <!-- METODE BAYAR -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">METODE BAYAR</p>
                            <p id="paymentMethod" class="text-lg font-bold mb-1"></p>
                            <p id="paymentDate" class="text-xs text-white/70"></p>
                        </div>

                        <!-- SISA KELAS -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">SISA KELAS</p>
                            <p id="remainingClasses" class="text-2xl font-bold mb-1"></p>
                            <p id="classType" class="text-xs text-white/70"></p>
                        </div>

                        <!-- STATUS -->
                        <div class="bg-dark rounded-2xl p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-wider text-white/70 mb-2">STATUS</p>
                            <p id="statusValue" class="text-lg font-bold mb-2"></p>
                            <span id="statusBadge" class="inline-block px-3 py-1 rounded-full text-xs font-bold"></span>
                        </div>
                    </div>

                    <!-- Timeline Section -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h4 class="font-bold text-dark mb-4 flex items-center">
                            <i class="fas fa-clock mr-2 text-primary-dark"></i>
                            RIWAYAT WAKTU
                        </h4>
                        
                        <div class="space-y-4">
                            <!-- Purchase Date -->
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-grounded-green/40 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-calendar-plus text-accent text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">TANGGAL PEMBELIAN</p>
                                    <p id="purchaseDate" class="text-sm font-bold text-dark mb-1"></p>
                                    <span id="purchaseBadge" class="inline-block px-2.5 py-1 rounded-full text-xs font-bold bg-grounded-green/40 text-springs-ivy">Paket berhasil</span>
                                </div>
                            </div>

                            <!-- Expiry Date -->
                            <div class="flex items-start">
                                <div class="w-10 h-10 rounded-full bg-light-pink/50 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="fas fa-calendar-times text-secondary text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">TANGGAL BERAKHIR</p>
                                    <p id="expiryDate" class="text-sm font-bold text-dark mb-1"></p>
                                    <span id="expiryBadge" class="inline-block px-2.5 py-1 rounded-full text-xs font-bold"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits Section -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm">
                        <h4 class="font-bold text-dark mb-3">Yang kamu dapatkan</h4>
                        <div id="benefitsList" class="space-y-3">
                            <!-- Will be populated by JS -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
// ===== AVAILABLE PACKAGES MODAL =====
function openAvailablePackagesModal() {
    const modal = document.getElementById('availablePackagesModal');
    if (!modal) return;
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
    setTimeout(() => { modal.classList.add('hidden'); document.body.style.overflow = ''; }, 350);
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
        document.getElementById('modalLoading').innerHTML = `
            <div class="text-center">
                <div class="w-16 h-16 bg-light-pink/50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-secondary text-2xl"></i>
                </div>
                <p class="text-sm text-secondary font-medium">Gagal memuat detail paket</p>
                button onclick="fetchPackageDetail(${orderId})" class="mt-3 text-sm font-medium" style="color: #7A2B4A;">
                    <i class="fas fa-redo mr-1"></i>Coba Lagi
                </button>
            </div>
        `;
    }
}

function populateModal(data) {
    const { order, package: pkg, usage, booked_schedules, attendance_history, stats } = data;
    
    // Package Name & Order Code
    document.getElementById('modalPackageName').textContent = pkg.name;
    document.getElementById('modalOrderCode').innerHTML = `<i class="fas fa-barcode mr-1"></i>${order.order_code}`;
    
    // Info Boxes (DIBELI, BERAKHIR, DURASI)
    const infoBoxes = document.getElementById('infoBoxes');
    infoBoxes.innerHTML = `
        <div class="bg-cream rounded-xl p-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">DIBELI</p>
            <p class="text-sm font-bold text-dark">${order.created_at_short || order.created_at}</p>
        </div>
        <div class="bg-cream rounded-xl p-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">BERAKHIR</p>
            <p class="text-sm font-bold text-dark">${order.expired_at_short || order.expired_at_full || '-'}</p>
        </div>
        <div class="bg-cream rounded-xl p-3">
            <p class="text-xs font-semibold uppercase tracking-wider text-cream0 mb-1">DURASI</p>
            <p class="text-sm font-bold text-dark">${pkg.duration_days ? pkg.duration_days + ' hari' : 'Unlimited'}</p>
        </div>
    `;
    
    // Warning Message (if applicable)
    const warningMsg = document.getElementById('warningMessage');
    if (!order.is_expired && order.remaining_days <= 7 && order.remaining_days > 0) {
        warningMsg.classList.remove('hidden');
        document.getElementById('warningTitle').textContent = 'Belum dimulai';
        document.getElementById('warningText').textContent = 'Masa aktif dihitung sejak booking pertama dilakukan.';
    } else if (order.is_expired) {
        warningMsg.classList.remove('hidden');
        warningMsg.className = 'bg-red-50 border-l-4 border-red-400 p-4 rounded-lg';
        document.getElementById('warningTitle').textContent = 'Paket Expired';
        document.getElementById('warningText').textContent = 'Paket ini sudah tidak aktif. Perpanjang untuk melanjutkan.';
    } else {
        warningMsg.classList.add('hidden');
    }
    
    // Usage Section
    document.getElementById('usageText').textContent = `${usage.used}/${usage.total_quota} kelas`;
    const availableBadge = document.getElementById('availableBadge');
    if (usage.remaining <= 0) {
        availableBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700';
        availableBadge.textContent = 'Habis';
    } else if (usage.remaining <= 2) {
        availableBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700';
        availableBadge.textContent = 'Hampir habis';
    } else {
        availableBadge.className = 'px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700';
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
    
    // Timeline
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
    
    // Benefits List
    const benefitsList = document.getElementById('benefitsList');
    const benefits = [
        { icon: 'fa-dumbbell', text: `${pkg.quota} kelas ${pkg.is_exclusive ? 'eksklusif' : 'reguler'}` },
        { icon: 'fa-calendar-check', text: `Durasi ${pkg.duration_days ? pkg.duration_days + ' hari' : 'unlimited'}` },
        { icon: 'fa-user-check', text: '1 kelas / 1 personal' },
        { icon: 'fa-certificate', text: 'Sertifikat kehadiran' }
    ];
    
    benefitsList.innerHTML = benefits.map(b => `
        <div class="flex items-center">
            <div class="w-8 h-8 bg-light-pink/30 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                <i class="fas ${b.icon} text-primary-dark text-sm"></i>
            </div>
            <p class="text-sm text-dark">${b.text}</p>
        </div>
    `).join('');
    
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