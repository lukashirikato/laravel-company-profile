@php
    use Illuminate\Support\Facades\Auth;
    
    $user = Auth::user();
    $currentDate = now()->format('l, d F Y');
    $currentTime = now()->format('H:i');
    
    // Mock data - ganti dengan data real dari database
    $stats = [
        'totalMembers' => 156,
        'activeMembers' => 142,
        'bookingsToday' => 8,
        'revenue' => 45250000,
        'newMembers' => 12,
        'expiredMemberships' => 5,
    ];
@endphp

<style>
    .dashboard-container {
        background: linear-gradient(135deg, #fafaf9 0%, #f5f3f0 100%);
        min-height: 100vh;
        padding: 2rem;
    }
    
    .dashboard-header {
        background: linear-gradient(135deg, #3d1f1f 0%, #4d3333 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(61, 31, 31, 0.15);
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(248, 215, 201, 0.05);
        border-radius: 50%;
    }
    
    .dashboard-header-content {
        position: relative;
        z-index: 1;
    }
    
    .dashboard-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
        color: white;
    }
    
    .dashboard-header p {
        font-size: 1rem;
        color: rgba(255, 245, 238, 0.8);
        margin-top: 0.5rem;
    }
    
    .time-display {
        font-size: 0.95rem;
        color: rgba(255, 245, 238, 0.7);
        margin-top: 1rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 2rem;
        border: 1px solid #f0d5d5;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(61, 31, 31, 0.05);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3d1f1f, #a87575);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.35s ease;
    }
    
    .stat-card:hover {
        border-color: #a87575;
        box-shadow: 0 12px 28px rgba(168, 117, 117, 0.15);
        transform: translateY(-6px);
    }
    
    .stat-card:hover::before {
        transform: scaleX(1);
    }
    
    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, rgba(61, 31, 31, 0.1), rgba(168, 117, 117, 0.1));
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, rgba(168, 117, 117, 0.15), rgba(200, 154, 154, 0.1));
        color: #a87575;
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, rgba(61, 31, 31, 0.15), rgba(77, 51, 51, 0.1));
        color: #3d1f1f;
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, rgba(200, 154, 154, 0.15), rgba(209, 181, 165, 0.1));
        color: #c89a9a;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #3d1f1f;
        line-height: 1;
    }
    
    .stat-change {
        font-size: 0.8rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f0d5d5;
        color: #6b7280;
    }
    
    .stat-change.positive {
        color: #10b981;
    }
    
    /* CONTENT SECTION */
    .content-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 1024px) {
        .content-section {
            grid-template-columns: 1fr;
        }
    }
    
    .card {
        background: white;
        border-radius: 14px;
        border: 1px solid #f0d5d5;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(61, 31, 31, 0.05);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0d5d5;
    }
    
    .card-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #3d1f1f;
        margin: 0;
    }
    
    .card-header-action {
        font-size: 0.875rem;
        color: #a87575;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }
    
    .card-header-action:hover {
        color: #3d1f1f;
    }
    
    /* TABLE STYLING */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table thead {
        background: linear-gradient(90deg, #faf8f7 0%, #f5f1f0 100%);
    }
    
    .data-table thead th {
        padding: 1rem;
        text-align: left;
        font-size: 0.8rem;
        font-weight: 700;
        color: #3d1f1f;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #f0d5d5;
    }
    
    .data-table tbody tr {
        border-bottom: 1px solid #f0d5d5;
        transition: background-color 0.25s ease;
    }
    
    .data-table tbody tr:hover {
        background-color: rgba(240, 213, 213, 0.4);
    }
    
    .data-table tbody tr:last-child {
        border-bottom: none;
    }
    
    .data-table tbody td {
        padding: 1rem;
        font-size: 0.9rem;
        color: #374151;
    }
    
    .data-table tbody td a {
        color: #a87575;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }
    
    .data-table tbody td a:hover {
        color: #3d1f1f;
        text-decoration: underline;
    }
    
    /* BADGE */
    .badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-verified {
        background-color: rgba(16, 185, 129, 0.15);
        color: #10b981;
    }
    
    .badge-pending {
        background-color: rgba(200, 154, 154, 0.15);
        color: #c89a9a;
    }
    
    .badge-active {
        background-color: rgba(61, 31, 31, 0.1);
        color: #3d1f1f;
    }
    
    /* QUICK ACTIONS */
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .action-btn {
        padding: 1rem;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    
    .action-btn.primary {
        background: linear-gradient(135deg, #3d1f1f 0%, #4d3333 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(61, 31, 31, 0.2);
    }
    
    .action-btn.primary:hover {
        box-shadow: 0 6px 16px rgba(61, 31, 31, 0.3);
        transform: translateY(-2px);
    }
    
    .action-btn.secondary {
        background-color: #f0d5d5;
        color: #3d1f1f;
        border: 2px solid #c89a9a;
    }
    
    .action-btn.secondary:hover {
        background-color: #c89a9a;
        color: white;
    }
    
    /* EMPTY STATE */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6b7280;
    }
    
    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.6;
    }
    
    .empty-state p {
        margin: 0;
        font-size: 0.95rem;
    }
    
    /* RESPONSIVE */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }
        
        .dashboard-header h1 {
            font-size: 1.75rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .card {
            padding: 1.5rem;
        }
    }
</style>

<div class="dashboard-container">
    {{-- HEADER SECTION --}}
    <div class="dashboard-header">
        <div class="dashboard-header-content">
            <h1>Dashboard Admin FTM</h1>
            <p>Selamat datang kembali, {{ $user->name ?? 'Admin' }}</p>
            <div class="time-display">
                📅 {{ $currentDate }} • ⏰ {{ $currentTime }}
            </div>
        </div>
    </div>

    {{-- STATISTICS SECTION --}}
    <div class="stats-grid">
        {{-- Total Members --}}
        <div class="stat-card">
            <div class="stat-icon primary">👥</div>
            <div class="stat-label">Total Member Terdaftar</div>
            <div class="stat-value">{{ $stats['totalMembers'] }}</div>
            <div class="stat-change positive">↑ 12 member baru bulan ini</div>
        </div>

        {{-- Active Members --}}
        <div class="stat-card">
            <div class="stat-icon success">✓</div>
            <div class="stat-label">Member Aktif</div>
            <div class="stat-value">{{ $stats['activeMembers'] }}</div>
            <div class="stat-change positive">{{ round(($stats['activeMembers'] / $stats['totalMembers']) * 100, 1) }}% aktif</div>
        </div>

        {{-- Today's Bookings --}}
        <div class="stat-card">
            <div class="stat-icon warning">📅</div>
            <div class="stat-label">Booking Hari Ini</div>
            <div class="stat-value">{{ $stats['bookingsToday'] }}</div>
            <div class="stat-change">Sesi gym terjadwal</div>
        </div>

        {{-- Revenue --}}
        <div class="stat-card">
            <div class="stat-icon primary">💰</div>
            <div class="stat-label">Revenue Bulan Ini</div>
            <div class="stat-value">Rp {{ number_format($stats['revenue'] / 1000000, 1) }}M</div>
            <div class="stat-change positive">↑ 8% dari bulan lalu</div>
        </div>
    </div>

    {{-- MAIN CONTENT SECTION --}}
    <div class="content-section">
        {{-- LATEST MEMBERS --}}
        <div class="card">
            <div class="card-header">
                <h3>Member Terbaru</h3>
                <a href="#" class="card-header-action">Lihat Semua →</a>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Dino Maulana</strong></td>
                            <td>dino@gmail.com</td>
                            <td><span class="badge badge-verified">Verified</span></td>
                            <td style="text-align: center;">
                                <a href="#" style="color: #a87575; font-weight: 700;">✓</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Qoni Pratama</strong></td>
                            <td>qoni@gmail.com</td>
                            <td><span class="badge badge-pending">Pending</span></td>
                            <td style="text-align: center;">
                                <a href="#" style="color: #a87575; font-weight: 700;">✓</a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Farhan Aziz</strong></td>
                            <td>farhan@gmail.com</td>
                            <td><span class="badge badge-verified">Verified</span></td>
                            <td style="text-align: center;">
                                <a href="#" style="color: #a87575; font-weight: 700;">✓</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- QUICK ACTIONS SIDEBAR --}}
        <div class="card">
            <div class="card-header">
                <h3>Menu Cepat</h3>
            </div>
            
            <div class="quick-actions">
                <button class="action-btn primary">
                    ➕ Member Baru
                </button>
                <button class="action-btn secondary">
                    📅 Jadwal Kelas
                </button>
                <button class="action-btn secondary">
                    💳 Kelola Pembayaran
                </button>
                <button class="action-btn secondary">
                    📊 Lihat Laporan
                </button>
                <button class="action-btn secondary">
                    ⚙️ Pengaturan
                </button>
            </div>
        </div>
    </div>

    {{-- BOTTOM SECTION --}}
    <div class="content-section" style="grid-template-columns: 1fr 1fr;">
        {{-- MEMBERSHIP STATUS --}}
        <div class="card">
            <div class="card-header">
                <h3>Status Membership</h3>
                <a href="#" class="card-header-action">Detail →</a>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="text-align: center; padding: 1.5rem; background: rgba(16, 185, 129, 0.08); border-radius: 10px;">
                    <div style="font-size: 1.75rem; font-weight: 800; color: #10b981;">{{ $stats['activeMembers'] }}</div>
                    <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">Aktif</div>
                </div>
                <div style="text-align: center; padding: 1.5rem; background: rgba(239, 68, 68, 0.08); border-radius: 10px;">
                    <div style="font-size: 1.75rem; font-weight: 800; color: #ef4444;">{{ $stats['expiredMemberships'] }}</div>
                    <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.5rem;">Kadaluarsa</div>
                </div>
            </div>
        </div>

        {{-- CLASS SCHEDULE --}}
        <div class="card">
            <div class="card-header">
                <h3>Jadwal Kelas Hari Ini</h3>
                <a href="#" class="card-header-action">Semua →</a>
            </div>
            
            <div style="space-y: 1rem;">
                <div style="padding: 0.75rem; border-left: 4px solid #a87575; background: rgba(168, 117, 117, 0.08); border-radius: 6px; margin-bottom: 0.75rem;">
                    <div style="font-weight: 700; color: #3d1f1f;">Gym Class - Morning</div>
                    <div style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">06:00 - 07:30 • 12 member</div>
                </div>
                <div style="padding: 0.75rem; border-left: 4px solid #c89a9a; background: rgba(200, 154, 154, 0.08); border-radius: 6px; margin-bottom: 0.75rem;">
                    <div style="font-weight: 700; color: #3d1f1f;">Yoga & Stretching</div>
                    <div style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">14:00 - 15:30 • 8 member</div>
                </div>
                <div style="padding: 0.75rem; border-left: 4px solid #d1b5a5; background: rgba(209, 181, 165, 0.08); border-radius: 6px;">
                    <div style="font-weight: 700; color: #3d1f1f;">Personal Training</div>
                    <div style="font-size: 0.8rem; color: #6b7280; margin-top: 0.25rem;">18:00 - 19:00 • 4 member</div>
                </div>
            </div>
        </div>
    </div>
</div>
