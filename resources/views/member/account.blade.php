@extends('layouts.app')

@section('content')

@php
    $member = auth('customer')->user();
    // Auto-generate QR jika belum ada
    $qrData = $member->getQRData();
    $activeOrder = $member->orders()
        ->where('status', 'paid')
        ->where(function($q) {
            $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
        })->first();
    $memberId = str_pad($member->id, 4, '0', STR_PAD_LEFT);
@endphp

<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }

    /* ── MAIN CONTENT ── */
    .acct-main { background: #f8fafc; min-height: 100vh; }
    .acct-container { max-width: 1100px; margin: 0 auto; }

    /* ── PAGE HEADER ── */
    .acct-header { margin-bottom: 2rem; }
    .acct-header h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; }
    .acct-header p { font-size: 0.875rem; color: #64748b; margin-top: 4px; }

    /* ── MEMBER CARD (Hero) ── */
    .member-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        border-radius: 20px; padding: 2rem; margin-bottom: 1.5rem;
        display: grid; grid-template-columns: auto 1fr; gap: 2.5rem; align-items: center;
        position: relative; overflow: hidden;
        box-shadow: 0 10px 40px rgba(15,23,42,0.3);
    }
    .member-hero::before {
        content: ''; position: absolute; top: -80px; right: -80px;
        width: 250px; height: 250px; border-radius: 50%;
        background: rgba(234,105,147,0.08);
    }
    .member-hero::after {
        content: ''; position: absolute; bottom: -60px; left: -60px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(121,52,81,0.06);
    }

    /* QR Card */
    .qr-card {
        background: white; border-radius: 16px; padding: 1.25rem;
        text-align: center; position: relative; z-index: 1;
        box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    }
    .qr-card img { display: block; margin: 0 auto; border-radius: 8px; }
    .qr-card-id {
        margin-top: 0.75rem; padding-top: 0.75rem;
        border-top: 1px dashed #e2e8f0;
    }
    .qr-card-id .label { font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
    .qr-card-id .value { font-family: 'JetBrains Mono', monospace; font-size: 1.1rem; font-weight: 800; color: #0f172a; }

    /* Member Info in hero */
    .member-info { position: relative; z-index: 1; }
    .member-info .avatar {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, #793451, #EA6993);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: 800; color: white;
        margin-bottom: 1rem; box-shadow: 0 4px 14px rgba(121,52,81,0.30);
    }
    .member-info .name { font-size: 1.5rem; font-weight: 800; color: white; margin-bottom: 0.25rem; }
    .member-info .email { font-size: 0.85rem; color: #94a3b8; margin-bottom: 1.25rem; }
    .member-info .detail-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;
    }
    .member-info .detail-item {
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px; padding: 0.85rem 1rem;
    }
    .member-info .detail-item .dlabel {
        font-size: 0.65rem; font-weight: 600; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 4px;
    }
    .member-info .detail-item .dvalue { font-size: 0.9rem; font-weight: 700; color: white; }
    .member-info .qr-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 14px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;
        margin-top: 1rem;
    }
    .member-info .qr-badge.active { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
    .member-info .qr-badge.inactive { background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.25); }
    .member-info .qr-badge .dot {
        width: 7px; height: 7px; border-radius: 50%; display: inline-block;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    .member-info .qr-badge.active .dot { background: #4ade80; }
    .member-info .qr-badge.inactive .dot { background: #f87171; }
    @keyframes pulse-dot { 0%,100% { opacity: 1; } 50% { opacity: 0.3; } }

    /* ── STATS GRID ── */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card {
        background: white; border-radius: 16px; padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0; position: relative; overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
    .stat-card.sc-indigo::before { background: linear-gradient(90deg, #793451, #EA6993); }
    .stat-card.sc-emerald::before { background: linear-gradient(90deg, #059669, #34d399); }
    .stat-card.sc-amber::before { background: linear-gradient(90deg, #d97706, #fbbf24); }
    .stat-card .sc-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; margin-bottom: 0.75rem;
    }
    .stat-card.sc-indigo .sc-icon { background: rgba(234,105,147,0.12); color: #793451; }
    .stat-card.sc-emerald .sc-icon { background: #ecfdf5; color: #059669; }
    .stat-card.sc-amber .sc-icon { background: #fffbeb; color: #d97706; }
    .stat-card .sc-label { font-size: 0.7rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.3rem; }
    .stat-card .sc-value { font-size: 1.75rem; font-weight: 900; color: #0f172a; line-height: 1; }
    .stat-card .sc-sub { font-size: 0.75rem; color: #94a3b8; margin-top: 4px; }
    .stat-card .quota-bar { height: 4px; background: #f1f5f9; border-radius: 999px; margin-top: 0.6rem; overflow: hidden; }
    .stat-card .quota-bar-fill { height: 100%; border-radius: 999px; transition: width 0.5s; }

    /* ── CONTENT GRID (QR Actions + Check-in) ── */
    .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
    .content-card {
        background: white; border-radius: 16px; padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    .content-card .cc-title {
        font-size: 0.95rem; font-weight: 700; color: #0f172a;
        display: flex; align-items: center; gap: 8px; margin-bottom: 1.25rem;
    }
    .content-card .cc-title i { font-size: 1rem; color: #793451; }

    /* QR ACTION BUTTONS */
    .qr-actions { display: flex; flex-direction: column; gap: 0.5rem; }
    .qr-action-btn {
        display: flex; align-items: center; gap: 10px;
        padding: 0.85rem 1rem; border-radius: 12px; border: none; cursor: pointer;
        font-size: 0.85rem; font-weight: 600; transition: all 0.2s;
        text-decoration: none; width: 100%;
    }
    .qr-action-btn:hover { transform: translateY(-1px); }
    .qr-action-btn i { font-size: 1rem; width: 20px; text-align: center; }
    .qr-action-btn.btn-download {
        background: #0f172a; color: white;
        box-shadow: 0 2px 8px rgba(15,23,42,0.2);
    }
    .qr-action-btn.btn-download:hover { background: #1e293b; }
    .qr-action-btn.btn-print {
        background: linear-gradient(135deg, #793451, #EA6993); color: white;
        box-shadow: 0 2px 8px rgba(121,52,81,0.25);
    }
    .qr-action-btn.btn-print:hover { background: linear-gradient(135deg, #5A1F3A, #B83863); }
    .qr-action-btn.btn-regen {
        background: #f8fafc; color: #334155; border: 1px solid #e2e8f0;
    }
    .qr-action-btn.btn-regen:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .qr-action-btn.btn-disable {
        background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
    }
    .qr-action-btn.btn-disable:hover { background: #fee2e2; }
    .qr-action-btn.btn-generate {
        background: linear-gradient(135deg, #793451, #EA6993); color: white;
        box-shadow: 0 4px 14px rgba(121,52,81,0.30); padding: 1rem;
        justify-content: center;
    }

    /* STATUS PANELS */
    .status-card {
        background: #f8fafc; border-radius: 12px; padding: 1rem 1.1rem;
        border: 1px solid #e2e8f0; min-height: 80px;
    }
    .status-label {
        font-size: 0.65rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.5rem;
    }

    /* INFO ALERT */
    .info-tip {
        background: rgba(241,204,227,0.30); border-left: 3px solid #EA6993;
        border-radius: 0 10px 10px 0; padding: 0.85rem 1rem;
        font-size: 0.8rem; color: #793451; display: flex; gap: 8px; align-items: flex-start;
        margin-top: 0.75rem;
    }
    .info-tip i { margin-top: 1px; flex-shrink: 0; }

    /* NOTIFICATIONS */
    .notif-item {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 0.75rem 0.9rem; border-radius: 10px; font-size: 0.82rem;
        background: #f8fafc; border: 1px solid #e2e8f0;
    }
    .notif-item i { color: #793451; margin-top: 1px; flex-shrink: 0; }

    /* ── QR PREVIEW MODAL ── */
    .qr-preview-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.85);
        backdrop-filter: blur(12px);
        display: none; align-items: center; justify-content: center;
        z-index: 100; padding: 1rem;
    }
    .qr-preview-overlay.open { display: flex; }
    .qr-preview-overlay .qp-backdrop { position: absolute; inset: 0; }
    .qr-preview-card {
        position: relative; z-index: 10;
        width: 100%; max-width: 420px;
        animation: qpScaleIn 0.3s cubic-bezier(0.16,1,0.3,1) forwards;
    }
    @keyframes qpScaleIn {
        0% { opacity: 0; transform: scale(0.9) translateY(10px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    .qp-close {
        position: absolute; top: -14px; right: -14px;
        width: 40px; height: 40px;
        background: rgba(30,41,59,0.6); border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50%; cursor: pointer;
        color: white; font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; z-index: 20;
        backdrop-filter: blur(4px);
    }
    .qp-close:hover { background: rgba(30,41,59,0.8); }
    .qp-inner {
        background: #F3C3C3;
        border-radius: 24px; padding: 2rem; overflow: hidden; position: relative;
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
    }
    .qp-inner::before {
        content: ''; position: absolute; top: -60px; right: -60px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(200,120,140,0.35);
    }
    .qp-inner::after {
        content: ''; position: absolute; bottom: -40px; left: -40px;
        width: 130px; height: 130px; border-radius: 50%;
        background: rgba(180,100,130,0.25);
    }
    .qp-header { text-align: center; margin-bottom: 1.5rem; position: relative; z-index: 1; }
    .qp-header .qp-brand { font-size: 0.6rem; font-weight: 800; letter-spacing: 0.3em; color: #793451; text-transform: uppercase; margin-bottom: 2px; }
    .qp-header .qp-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; letter-spacing: 0.1em; }
    .qp-qr-wrap {
        display: flex; justify-content: center; margin-bottom: 1.5rem; position: relative; z-index: 1;
    }
    .qp-qr-box {
        background: white; border-radius: 16px; padding: 1.25rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }
    .qp-qr-box img { display: block; width: 240px; height: 240px; }
    .qp-member-info { text-align: center; position: relative; z-index: 1; margin-bottom: 1.25rem; }
    .qp-avatar {
        width: 48px; height: 48px; border-radius: 14px;
        background: linear-gradient(135deg, #793451, #EA6993);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; font-weight: 800; color: white;
        margin: 0 auto 0.75rem; box-shadow: 0 4px 14px rgba(121,52,81,0.30);
    }
    .qp-name { font-size: 1.1rem; font-weight: 700; color: #0f172a; }
    .qp-id { font-size: 0.85rem; color: #64748b; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-top: 2px; }
    .qp-status {
        text-align: center; position: relative; z-index: 1; margin-bottom: 1rem;
    }
    .qp-status-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(0,116,95,0.12); border: 1px solid rgba(0,116,95,0.25);
        color: #00745F; padding: 6px 16px; border-radius: 999px;
        font-size: 0.72rem; font-weight: 700;
    }
    .qp-status-badge .qp-dot {
        width: 7px; height: 7px; border-radius: 50%; background: #00745F;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    .qp-divider {
        border-top: 1px solid rgba(0,0,0,0.08);
        padding-top: 1rem; text-align: center; position: relative; z-index: 1;
    }
    .qp-divider p { font-size: 0.7rem; color: #64748b; }
    .qp-actions {
        display: flex; gap: 0.75rem; margin-top: 1rem;
    }
    .qp-actions button {
        flex: 1; padding: 0.75rem; border-radius: 12px; border: none; cursor: pointer;
        font-size: 0.85rem; font-weight: 600; transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .qp-btn-download {
        background: rgba(255,255,255,0.1); color: white;
        border: 1px solid rgba(255,255,255,0.1) !important;
        backdrop-filter: blur(4px);
    }
    .qp-btn-download:hover { background: rgba(255,255,255,0.15); }
    .qp-btn-done {
        background: linear-gradient(135deg, #793451, #EA6993); color: white;
        box-shadow: 0 4px 14px rgba(121,52,81,0.35);
    }
    .qp-btn-done:hover { background: linear-gradient(135deg, #5A1F3A, #B83863); transform: translateY(-1px); }

    /* ── PRINT MODAL ── */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(15,23,42,0.6);
        backdrop-filter: blur(6px);
        display: none; align-items: center; justify-content: center; z-index: 50; padding: 1rem;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: white; border-radius: 20px; padding: 2rem;
        max-width: 420px; width: 100%; position: relative;
        box-shadow: 0 25px 60px rgba(15,23,42,0.25);
    }
    .modal-close {
        position: absolute; top: 1rem; right: 1rem;
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        background: #f1f5f9; color: #64748b; font-size: 1.1rem;
        display: flex; align-items: center; justify-content: center; transition: all 0.15s;
    }
    .modal-close:hover { background: #e2e8f0; color: #0f172a; }

    .print-member-card {
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 16px; padding: 2rem; text-align: center; margin-bottom: 1.25rem;
        position: relative; overflow: hidden;
    }
    .print-member-card::before {
        content: ''; position: absolute; top: -40px; right: -40px;
        width: 120px; height: 120px; border-radius: 50%;
        background: rgba(234,105,147,0.10);
    }
    .print-member-card .brand {
        font-size: 0.65rem; font-weight: 800; letter-spacing: 0.25em;
        color: #F1CCE3; margin-bottom: 0.15rem; position: relative;
    }
    .print-member-card .card-type {
        font-size: 0.95rem; font-weight: 800; color: white;
        letter-spacing: 0.08em; margin-bottom: 1.25rem; position: relative;
    }
    .print-member-card .qr-wrap {
        background: white; border-radius: 12px; padding: 0.75rem;
        display: inline-block; margin-bottom: 1rem; position: relative;
    }
    .print-member-card .member-name {
        font-size: 0.95rem; font-weight: 700; color: white; margin-bottom: 2px; position: relative;
    }
    .print-member-card .member-id-text {
        font-size: 0.75rem; color: #94a3b8; font-family: monospace; position: relative;
    }

    .btn-print-now {
        width: 100%; padding: 0.85rem; border-radius: 12px; border: none; cursor: pointer;
        background: linear-gradient(135deg, #793451, #EA6993); color: white; font-size: 0.88rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: all 0.2s; box-shadow: 0 4px 14px rgba(121,52,81,0.30);
    }
    .btn-print-now:hover { background: linear-gradient(135deg, #5A1F3A, #B83863); transform: translateY(-1px); }

    @keyframes spin { to { transform: rotate(360deg); } }
    .spin { animation: spin 1s linear infinite; }

    @media print {
        body > *:not(#print-modal) { display: none; }
        #print-modal { display: block !important; position: static; background: none; }
        .modal-box { box-shadow: none; border: none; }
        .modal-close, button { display: none !important; }
    }
</style>

<!-- Font Awesome + Google Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@600;700;800&display=swap" rel="stylesheet">

<div style="display:flex; height:100vh;">

<!-- ========================================
     SIDEBAR
======================================== -->
<aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
    <a href="{{ route('member.profile') }}" class="px-6 py-5 text-xl font-bold border-b border-white/20 hover:bg-slate-800 transition inline-block w-full">
        FTM SOCIETY
    </a>

    <nav class="flex-1 px-4 py-6 space-y-1 text-sm">
        <a href="{{ route('member.dashboard') }}" 
           class="block px-4 py-2 rounded hover:bg-white/10 transition">
            <i class="fas fa-home mr-2"></i>Dashboard
        </a>
        <a href="{{ route('member.packages.index') }}" 
           class="block px-4 py-2 rounded hover:bg-white/10 transition">
            <i class="fas fa-box mr-2"></i>My Packages
        </a>
        <a href="{{ route('member.book') }}"
           class="block px-4 py-2 rounded hover:bg-white/10 transition">
            <i class="fas fa-calendar-plus mr-2"></i>Book Class
        </a>
        <a href="{{ route('member.my-classes') }}"
           class="block px-4 py-2 rounded hover:bg-white/10 transition">
            <i class="fas fa-dumbbell mr-2"></i>My Classes
        </a>
        <a href="{{ route('member.transactions') }}" 
           class="block px-4 py-2 rounded hover:bg-white/10 transition">
            <i class="fas fa-receipt mr-2"></i>Transactions
        </a>
        <a href="{{ route('member.attendance') }}" 
           class="block px-4 py-2 rounded hover:bg-white/10 transition">
            <i class="fas fa-calendar-check mr-2"></i>Attendance
        </a>
        <a href="{{ route('member.account') }}" 
           class="block px-4 py-2 rounded text-white font-medium" style="background: linear-gradient(90deg, #793451 0%, #EA6993 100%); border-left: 3px solid #F1CCE3;">
            <i class="fas fa-user mr-2"></i>Profile
        </a>
    </nav>

    <div class="px-6 py-4 border-t border-white/20 text-xs text-white/60">
        &copy; {{ date('Y') }} FTM Society
    </div>
</aside>

<!-- ========================================
     MAIN CONTENT
======================================== -->
<main class="acct-main" style="flex:1; overflow-y:auto; padding:2rem 2.5rem;">
<div class="acct-container">

    {{-- ── PAGE HEADER ── --}}
    <div class="acct-header">
        <h1><i class="fas fa-user-circle" style="color:#793451; margin-right:8px;"></i>My Profile</h1>
        <p>Manage your account information and QR code</p>
    </div>

    {{-- ══════════════════════════════════════
         MEMBER HERO CARD (Profile + QR)
    ══════════════════════════════════════ --}}
    <div class="member-hero">
        {{-- QR Code Card --}}
        <div class="qr-card" onclick="openQRPreview()" style="cursor:pointer;" title="Click to enlarge QR Code">
            <div id="qr-container">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrData) }}&bgcolor=ffffff&color=0f172a"
                     alt="QR Code" style="width:200px; height:200px; transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            </div>
            <div class="qr-card-id">
                <div class="label">Member ID</div>
                <div class="value">#{{ $memberId }}</div>
            </div>
            <div style="margin-top:0.5rem; font-size:0.65rem; color:#94a3b8;"><i class="fas fa-expand-alt" style="margin-right:3px;"></i>Tap to enlarge</div>
        </div>

        {{-- Member Info --}}
        <div class="member-info">
            <div class="avatar">{{ substr($member->name, 0, 1) }}</div>
            <div class="name">{{ $member->name }}</div>
            <div class="email">{{ $member->email }}</div>

            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dlabel"><i class="fas fa-phone" style="margin-right:4px; font-size:0.6rem;"></i> Phone</div>
                    <div class="dvalue">{{ $member->phone_number ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dlabel"><i class="fas fa-calendar" style="margin-right:4px; font-size:0.6rem;"></i> Member Since</div>
                    <div class="dvalue">{{ $member->created_at->format('d M Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="dlabel"><i class="fas fa-id-badge" style="margin-right:4px; font-size:0.6rem;"></i> Member ID</div>
                    <div class="dvalue" style="font-family:'JetBrains Mono',monospace;">#{{ $memberId }}</div>
                </div>
                <div class="detail-item">
                    <div class="dlabel"><i class="fas fa-qrcode" style="margin-right:4px; font-size:0.6rem;"></i> QR Status</div>
                    <div class="dvalue">
                        @if($member->qr_active)
                            <span style="color:#4ade80;">Active</span>
                        @else
                            <span style="color:#f87171;">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($member->qr_active)
                <span class="qr-badge active"><span class="dot"></span> QR Code Active</span>
            @else
                <span class="qr-badge inactive"><span class="dot"></span> QR Code Inactive</span>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════
         STATS ROW
    ══════════════════════════════════════ --}}
    <div class="stats-grid">
        {{-- Active Packages --}}
        <div class="stat-card sc-indigo">
            <div class="sc-icon"><i class="fas fa-box-open"></i></div>
            <div class="sc-label">Active Packages</div>
            <div class="sc-value">{{ $member->orders()->whereIn('status', ['active', 'paid'])->count() }}</div>
        </div>

        {{-- Total Attendances --}}
        <div class="stat-card sc-emerald">
            <div class="sc-icon"><i class="fas fa-calendar-check"></i></div>
            <div class="sc-label">Total Attendances</div>
            <div class="sc-value">{{ $member->attendances()->count() }}</div>
        </div>

        {{-- Remaining Quota --}}
        <div class="stat-card sc-amber">
            <div class="sc-icon"><i class="fas fa-ticket-alt"></i></div>
            <div class="sc-label">Remaining Quota</div>
            @if($activeOrder)
                @php
                    $remaining = $activeOrder->remaining_quota ?? 0;
                    $total = $activeOrder->package->quota ?? 0;
                    $pct = $total > 0 ? round(($remaining / $total) * 100) : 0;
                    $barColor = $pct > 50 ? '#059669' : ($pct > 20 ? '#d97706' : '#dc2626');
                @endphp
                <div class="sc-value">{{ $remaining }}</div>
                <div class="sc-sub">of {{ $total }} total</div>
                <div class="quota-bar">
                    <div class="quota-bar-fill" style="width:{{ $pct }}%; background:{{ $barColor }};"></div>
                </div>
            @elseif($member->quota > 0)
                <div class="sc-value">{{ $member->quota }}</div>
                <div class="sc-sub">from customer quota</div>
            @else
                <div class="sc-value" style="font-size:1rem; color:#94a3b8;">No package</div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════
         QR ACTIONS + CHECK-IN STATUS
    ══════════════════════════════════════ --}}
    <div class="content-grid">

        {{-- QR Actions Card --}}
        <div class="content-card">
            <div class="cc-title"><i class="fas fa-qrcode"></i> QR Code Actions</div>

            <div class="qr-actions">
                @if($member->qr_token && $member->qr_active)
                    <button onclick="downloadQR()" class="qr-action-btn btn-download">
                        <i class="fas fa-download"></i> Download QR Code
                    </button>
                    <button onclick="printQR()" class="qr-action-btn btn-print">
                        <i class="fas fa-print"></i> Print Member Card
                    </button>
                    <button onclick="regenerateQR()" class="qr-action-btn btn-regen">
                        <i class="fas fa-sync-alt"></i> Regenerate QR Code
                    </button>
                    <button onclick="disableQR()" class="qr-action-btn btn-disable">
                        <i class="fas fa-ban"></i> Disable QR Code
                    </button>
                @else
                    <button onclick="generateQR()" class="qr-action-btn btn-generate">
                        <i class="fas fa-qrcode"></i> Generate QR Code
                    </button>
                @endif
            </div>

            <div class="info-tip">
                <i class="fas fa-info-circle"></i>
                <span>Show this QR code at the gym entrance for check-in. Staff will scan your code automatically.</span>
            </div>
        </div>

      
    </div>

</div>
</main>
</div>
<!-- END LAYOUT -->

{{-- ══════════════════════════════════════
     QR PREVIEW MODAL
══════════════════════════════════════ --}}
<div id="qr-preview-overlay" class="qr-preview-overlay">
    <div class="qp-backdrop" onclick="closeQRPreview()"></div>
    <div class="qr-preview-card">
        <button class="qp-close" onclick="closeQRPreview()">&times;</button>
        <div class="qp-inner">
            <div class="qp-header">
                <div class="qp-brand">FTM Society</div>
                <div class="qp-title">MEMBER CARD</div>
            </div>
            <div class="qp-qr-wrap">
                <div class="qp-qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($qrData) }}&bgcolor=ffffff&color=0f172a" alt="QR Code">
                </div>
            </div>
            <div class="qp-member-info">
                <div class="qp-avatar">{{ substr($member->name, 0, 1) }}</div>
                <div class="qp-name">{{ $member->name }}</div>
                <div class="qp-id">Member ID: #{{ $memberId }}</div>
            </div>
            <div class="qp-status">
                <span class="qp-status-badge">
                    <span class="qp-dot"></span>
                    QR Active &mdash; Ready to Scan
                </span>
            </div>
            <div class="qp-divider">
                <p>Show this code to staff for check-in</p>
            </div>
        </div>
        
    </div>
</div>

{{-- ══════════════════════════════════════
     PRINT MODAL
══════════════════════════════════════ --}}
<div id="print-modal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closePrintModal()">&times;</button>
        <h3 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin-bottom:1.25rem; display:flex; align-items:center; gap:8px;">
            <i class="fas fa-print" style="color:#793451;"></i> Print Member Card
        </h3>
        <div class="print-member-card">
            <div class="brand">FTM SOCIETY</div>
            <div class="card-type">MEMBER CARD</div>
            <div class="qr-wrap">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qrData) }}&color=0f172a"
                     alt="QR Code" style="width:180px; height:180px; display:block;">
            </div>
            <div class="member-name">{{ $member->name }}</div>
            <div class="member-id-text">Member ID: #{{ $memberId }}</div>
        </div>
        <button onclick="window.print()" class="btn-print-now">
            <i class="fas fa-print"></i> Print Now
        </button>
    </div>
</div>

<script>
const token = '{{ csrf_token() }}';

function closePrintModal() { document.getElementById('print-modal').classList.remove('open'); }
function printQR() { document.getElementById('print-modal').classList.add('open'); }

function openQRPreview() {
    document.getElementById('qr-preview-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeQRPreview() {
    document.getElementById('qr-preview-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQRPreview();
        closePrintModal();
    }
});

async function generateQR() {
    if (!confirm('Generate a new QR code for your account?')) return;
    try {
        setLoading(event, 'Generating...');
        const res = await fetch('{{ route("member.api.qr.generate") }}', {
            method:'POST',
            headers:{ 'X-CSRF-TOKEN':token, 'Content-Type':'application/json', 'Accept':'application/json' }
        });
        const data = await res.json();
        if (res.ok && data.success) location.reload();
        else alert('Failed: ' + (data.message || 'Unknown error'));
    } catch(e) { alert('Error: ' + e.message); }
}

async function regenerateQR() {
    if (!confirm('This will invalidate your old QR code. Continue?')) return;
    try {
        setLoading(event, 'Regenerating...');
        const res = await fetch('{{ route("member.api.qr.regenerate") }}', {
            method:'POST',
            headers:{ 'X-CSRF-TOKEN':token, 'Content-Type':'application/json', 'Accept':'application/json' }
        });
        const data = await res.json();
        if (res.ok && data.success) location.reload();
        else alert('Failed: ' + (data.message || 'Unknown error'));
    } catch(e) { alert('Error: ' + e.message); }
}

async function disableQR() {
    if (!confirm("Disable your QR code? You won't be able to check in until re-enabled.")) return;
    try {
        setLoading(event, 'Disabling...');
        const res = await fetch('{{ route("member.api.qr.disable") }}', {
            method:'POST',
            headers:{ 'X-CSRF-TOKEN':token, 'Content-Type':'application/json', 'Accept':'application/json' }
        });
        const data = await res.json();
        if (res.ok && data.success) location.reload();
        else alert('Failed: ' + (data.message || 'Unknown error'));
    } catch(e) { alert('Error: ' + e.message); }
}

function downloadQR() {
    const img = document.querySelector('#qr-container img');
    if (!img) return alert('QR image not found');
    const link = document.createElement('a');
    link.href = img.src;
    link.download = 'ftm-society-qr-{{ $memberId }}.png';
    link.click();
}

function setLoading(ev, msg) {
    const btn = ev?.target?.closest('button');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner spin"></i> ' + msg; }
}

/* ── Real-time status ── */
async function loadQuotaInfo() {
    try {
        const res  = await fetch('/api/member/quota-info', { headers:{ 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' } });
        const data = await res.json();
        const el   = document.getElementById('quota-status');
        if (data.success && data.data.packages.length > 0) {
            const p = data.data.packages[0];
            const pct = p.usage_percentage || 0;
            const color = pct > 80 ? '#dc2626' : pct > 50 ? '#d97706' : '#059669';
            el.innerHTML = `
                <div style="margin-bottom:.5rem;">
                    <span style="font-size:.7rem;color:#94a3b8;">Quota remaining</span>
                    <div style="font-size:1.3rem;font-weight:900;color:#0f172a;">${p.remaining_quota}<span style="font-size:.8rem;font-weight:500;color:#94a3b8;"> / ${p.total_quota}</span></div>
                </div>
                <div style="height:4px;background:#f1f5f9;border-radius:999px;overflow:hidden;">
                    <div style="height:100%;width:${pct}%;background:${color};border-radius:999px;transition:width .5s;"></div>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.68rem;color:#94a3b8;margin-top:.3rem;">
                    <span>Used: ${p.used_quota}</span><span>${pct}%</span>
                </div>`;
        }
    } catch(e) { console.error(e); }
}

async function loadCheckinStatus() {
    try {
        const res  = await fetch('/api/member/recent-checkin', { headers:{ 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' } });
        const data = await res.json();
        const el   = document.getElementById('checkin-status');
        if (data.success) {
            const d = data.data;
            const dot = d.status === 'present' ? '#059669' : '#d97706';
            el.innerHTML = `
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:.5rem;">
                    <span style="width:9px;height:9px;border-radius:50%;background:${dot};display:inline-block;"></span>
                    <span style="font-weight:700;font-size:.85rem;color:#0f172a;">${d.status === 'present' ? 'Checked In' : 'Other'}</span>
                </div>
                <div style="font-size:.78rem;color:#94a3b8;"><strong style="color:#0f172a;">${d.check_in_date}</strong> at <strong style="color:#0f172a;">${d.check_in_time}</strong></div>
                <div style="font-size:.72rem;color:#94a3b8;margin-top:2px;">${d.program}</div>
                <div style="font-size:.7rem;color:#94a3b8;margin-top:1px;">${d.checked_in_ago}</div>`;
        } else {
            el.innerHTML = `<p style="font-size:.82rem;color:#94a3b8;">No check-in today yet.</p>`;
        }
    } catch(e) { console.error(e); }
}

function showNotification(title, message, type = 'info') {
    const area = document.getElementById('notification-area');
    const el   = document.createElement('div');
    el.className = 'notif-item';
    el.innerHTML = `<i class="fas fa-check-circle"></i><div><p style="font-weight:700;font-size:.82rem;color:#0f172a;">${title}</p><p style="font-size:.76rem;color:#94a3b8;">${message}</p></div>`;
    area.prepend(el);
    setTimeout(() => el.remove(), 10000);
}

document.addEventListener('DOMContentLoaded', () => {
    loadQuotaInfo();
    loadCheckinStatus();
    setInterval(loadQuotaInfo, 10000);
    setInterval(loadCheckinStatus, 10000);
});
</script>
@endsection