@php
    $formatPaymentMethod = function ($paymentType) {
        if (function_exists('formatPaymentMethod')) {
            return formatPaymentMethod($paymentType);
        }

        if (!$paymentType) {
            return 'Menunggu Pembayaran';
        }

        $paymentMethodMap = [
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'bank_transfer' => 'Virtual Account',
            'bca_va' => 'Virtual Account BCA',
            'bni_va' => 'Virtual Account BNI',
            'bri_va' => 'Virtual Account BRI',
            'permata_va' => 'Virtual Account Permata',
            'other_va' => 'Virtual Account',
            'echannel' => 'Mandiri Bill Payment',
            'cstore' => 'Convenience Store',
            'alfamart' => 'Alfamart',
            'indomaret' => 'Indomaret',
            'akulaku' => 'Akulaku',
            'credit_card' => 'Kartu Kredit',
            'debit_card' => 'Kartu Debit',
        ];

        return $paymentMethodMap[strtolower($paymentType)] ?? ucwords(str_replace('_', ' ', $paymentType));
    };
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History | FTM Society</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --ftm-pink: #EE4E8B;
            --ftm-cherry: #7A2B4A;
            --ftm-petal: #F4C9DF;
            --ftm-green: #1A7A5E;
            --ftm-ivy: #1D5A4B;
            --ftm-sage: #C5D79B;
            --ftm-layl: #1C1C1C;
            --ftm-rising: #FCF9F2;
            --card-bg: #FFFDF9;
            --border-color: rgba(122, 43, 74, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            background: var(--ftm-rising);
            color: var(--ftm-layl);
            -webkit-font-smoothing: antialiased;
        }

        .main-content {
            padding: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2.5rem;
        }

        .page-header h1 {
            font-family: 'Nord', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--ftm-cherry);
            margin-bottom: 0.25rem;
            line-height: 1.2;
        }

        .page-header p {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            color: #6B7280;
            line-height: 1.5;
        }

        /* Transactions list container */
        .transactions-grid {
            display: grid;
            gap: 1.5rem;
        }

        /* 🎟️ Distinctive "Class Ticket/Receipt" Card design */
        .transaction-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(122, 43, 74, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .transaction-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(122, 43, 74, 0.08);
            border-color: var(--ftm-pink);
        }

        /* Ticket side-cutout circles for the receipt aesthetic */
        .transaction-card::before,
        .transaction-card::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            background: var(--ftm-rising);
            border-radius: 50%;
            top: 55px; /* aligned right at the dashed line */
            z-index: 10;
            border: 1px solid var(--border-color);
            box-sizing: border-box;
        }
        .transaction-card::before {
            left: -8px;
            clip-path: polygon(50% 0, 100% 0, 100% 100%, 50% 100%);
        }
        .transaction-card::after {
            right: -8px;
            clip-path: polygon(0 0, 50% 0, 50% 100%, 0 100%);
        }

        /* Header of the ticket card */
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            background: rgba(244, 201, 223, 0.1);
            border-bottom: 1px dashed var(--border-color);
            min-width: 0;
            gap: 1rem;
        }

        .ticket-package-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }

        .ticket-package-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.9rem;
            flex-shrink: 0;
            background: var(--ftm-cherry);
        }

        .ticket-package-name {
            font-family: 'Nord', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--ftm-cherry);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ticket-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border-radius: 999px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            white-space: nowrap;
        }

        .ticket-status-badge.success {
            background: rgba(26, 122, 94, 0.1);
            color: var(--ftm-green);
        }
        .ticket-status-badge.pending {
            background: rgba(238, 78, 139, 0.1);
            color: var(--ftm-pink);
        }
        .ticket-status-badge.failed {
            background: #fee2e2;
            color: #b91c1c;
        }

        /* Body of the ticket card */
        .ticket-body {
            padding: 1.5rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr) auto;
            gap: 1.5rem;
            align-items: center;
        }

        .ticket-col {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            min-width: 0;
        }

        .ticket-label {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6B7280;
        }

        .ticket-value {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: var(--ftm-layl);
            line-height: 1.4;
            word-break: break-word;
        }

        .ticket-value.amount {
            font-family: 'Nord', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--ftm-cherry);
        }

        .ticket-value.amount.success {
            color: var(--ftm-green);
        }

        .ticket-value.order-code {
            font-family: monospace;
            background: #F3F4F6;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-size: 12px;
            border: 1px solid #E5E7EB;
            display: inline-block;
            width: fit-content;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        /* Action Button */
        .ticket-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1rem;
            background: #fff;
            color: var(--ftm-cherry);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            white-space: nowrap;
        }

        .ticket-btn:hover {
            background: var(--ftm-cherry);
            color: #fff;
            border-color: var(--ftm-cherry);
            box-shadow: 0 4px 12px rgba(122, 43, 74, 0.15);
        }

        /* Empty State Override */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px dashed var(--border-color);
        }

        .empty-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 1.25rem;
            background: rgba(244, 201, 223, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--ftm-cherry);
        }

        .empty-state h3 {
            font-family: 'Nord', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--ftm-cherry);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 1.75rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--ftm-cherry) 0%, var(--ftm-pink) 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-family: 'Nord', sans-serif;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 15px rgba(238, 78, 139, 0.2);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(238, 78, 139, 0.3);
        }

        /* Responsive Breakpoints */
        @media (max-width: 968px) {
            .ticket-body {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
            .ticket-col.amount-col {
                grid-column: span 2;
                border-top: 1px dashed rgba(122, 43, 74, 0.08);
                padding-top: 1rem;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
            .ticket-col.btn-col {
                grid-column: span 2;
                align-items: stretch;
            }
            .ticket-btn {
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1.25rem;
                margin-top: 3.5rem;
            }
            .page-header {
                margin-bottom: 1.75rem;
            }
            .page-header h1 {
                font-size: 22px;
            }
            .page-header p {
                font-size: 13px;
            }
            .ticket-header {
                padding: 1rem 1.25rem;
            }
            .ticket-package-name {
                font-size: 14px;
            }
            .ticket-body {
                padding: 1.25rem;
                gap: 1rem;
            }
            .ticket-col {
                gap: 0.25rem;
            }
            .ticket-label {
                font-size: 10px;
            }
            .ticket-value {
                font-size: 13px;
            }
            .ticket-value.amount {
                font-size: 15px;
            }
            .transaction-card::before,
            .transaction-card::after {
                top: 50px;
            }
        }

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
            .main-content {
                padding: 1rem;
                margin-top: 3rem;
            }

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

    <!-- MAIN CONTENT -->
    <!-- Mobile Sidebar Overlay -->
    <!-- Mobile Sidebar Overlay removed to avoid dark backdrop -->

    <!-- Mobile Hamburger Button -->
    <button id="hamburger-btn" class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <main class="flex-1 overflow-y-auto">
        <div class="main-content">
            
            <!-- Page Header -->
            <div class="page-header">
                <h1>Transaction History</h1>
                <p>View all your transactions and payment details</p>
            </div>

            <!-- Transactions Grid -->
            <div class="transactions-grid">
        @forelse ($transactions as $t)
                    @php
                        $status = strtolower($t->status ?? 'pending');
                        $statusClass = 'pending';
                        $statusText = 'Pending';
                        $statusIcon = 'fa-clock';
                        
                        if (in_array($status, ['paid', 'success', 'settlement'])) {
                            $statusClass = 'success';
                            $statusText = 'Success';
                            $statusIcon = 'fa-check-circle';
                        } elseif (in_array($status, ['failed', 'expire'])) {
                            $statusClass = 'failed';
                            $statusText = 'Failed';
                            $statusIcon = 'fa-times-circle';
                        }
                    @endphp

                    <div class="transaction-card">
                        <!-- Ticket Header -->
                        <div class="ticket-header">
                            <div class="ticket-package-info">
                                <div class="ticket-package-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div class="ticket-package-name">
                                    {{ $t->package->name ?? 'Membership Class' }}
                                </div>
                            </div>
                            <div class="ticket-status-badge {{ $statusClass }}">
                                <i class="fas {{ $statusIcon }}"></i>
                                {{ $statusText }}
                            </div>
                        </div>

                        <!-- Ticket Body -->
                        <div class="ticket-body">
                            <!-- Order Code -->
                            <div class="ticket-col">
                                <span class="ticket-label">Order Code</span>
                                <span class="ticket-value order-code">
                                    {{ $t->transaction_id ?? $t->order_code ?? '-' }}
                                </span>
                            </div>

                            <!-- Payment Method -->
                            <div class="ticket-col">
                                <span class="ticket-label">Payment</span>
                                <span class="ticket-value">
                                    @if($t->payment_type && $t->payment_type !== '-')
                                        {{ $formatPaymentMethod($t->payment_type) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>

                            <!-- Date & Time -->
                            <div class="ticket-col">
                                <span class="ticket-label">Date & Time</span>
                                <span class="ticket-value">
                                    {{ $t->created_at ? $t->created_at->format('d M Y, H:i') : '-' }}
                                </span>
                            </div>

                            <!-- Amount Column -->
                            <div class="ticket-col amount-col">
                                <span class="ticket-label">Total Amount</span>
                                <span class="ticket-value amount {{ $statusClass }}">
                                    Rp {{ number_format($t->amount ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Action Column -->
                            @if(isset($t->order_id))
                            <div class="ticket-col btn-col">
                                <a href="{{ route('invoice.show', $t->order_id) }}" 
                                   target="_blank"
                                   class="ticket-btn">
                                    <i class="fas fa-download"></i> Download Invoice
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
        @empty
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-receipt" style="color: #94a3b8;"></i>
                        </div>
                        <h3>No Transactions Yet</h3>
                        <p>You don't have any transaction history yet</p>
                        <a href="{{ route('home') ?? '#' }}" class="btn-primary">
                            <i class="fas fa-shopping-cart"></i>
                            Start Shopping
                        </a>
                    </div>
        @endforelse

        <!-- Pagination Links -->
        @if(method_exists($transactions, 'links'))
            <div class="mt-6 flex justify-center">
                {{ $transactions->links('pagination::bootstrap-4') }}
            </div>
        @endif
            </div>

        </div>
    </main>

</div>

<script>
// ===== SIDEBAR TOGGLE FUNCTION =====
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger-btn');
    if (!sidebar) return;

    const willOpen = !sidebar.classList.contains('active') && !sidebar.classList.contains('open');
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