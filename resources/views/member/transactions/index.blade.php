<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History | FTM Society</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
        }

        .main-content {
            padding: 2.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .page-header p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Transaction Grid - More compact */
        .transactions-grid {
            display: grid;
            gap: 1.25rem;
        }

        /* Compact Transaction Card */
        .transaction-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.2s ease;
        }

        .transaction-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        /* Card Layout - Horizontal compact */
        .card-content {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.5rem;
            padding: 1.5rem;
            align-items: center;
        }

        /* Status Section - Compact */
        .status-section {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 200px;
        }

        .status-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .status-icon.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }

        .status-icon.pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
        }

        .status-icon.failed {
            background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
            color: #dc2626;
        }

        .status-icon i {
            font-size: 1.25rem;
        }

        .status-info h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .status-info p {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Details Grid - Compact */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.25rem;
            flex: 1;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
        }

        .detail-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Amount Section */
        .amount-section {
            text-align: right;
            min-width: 140px;
        }

        .amount-label {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .amount-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }

        .amount-value.success {
            color: #059669;
        }

        /* Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8125rem;
            white-space: nowrap;
        }

        .status-badge.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .status-badge.failed {
            background: #fecaca;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .payment-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            background: #ede9fe;
            color: #6d28d9;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8125rem;
            border: 1px solid #c4b5fd;
            white-space: nowrap;
        }

        .payment-badge i {
            font-size: 0.875rem;
        }

        /* Order Code */
        .order-code {
            font-family: 'Courier New', monospace;
            font-size: 0.8125rem;
            color: #64748b;
            background: #f8fafc;
            padding: 0.25rem 0.625rem;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        /* Date */
        .transaction-date {
            color: #64748b;
            font-size: 0.8125rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .transaction-date i {
            font-size: 0.875rem;
        }

        /* Action Button */
        .action-button {
            padding: 0.5rem 1rem;
            background: white;
            color: #475569;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .action-button:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .action-button i {
            font-size: 0.875rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .empty-state p {
            color: #64748b;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .card-content {
                grid-template-columns: 1fr;
                gap: 1.25rem;
            }

            .status-section {
                min-width: auto;
            }

            .amount-section {
                text-align: left;
                min-width: auto;
            }

            .details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.75rem;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .amount-value {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body class="bg-gray-100 h-screen overflow-hidden">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
        <div class="px-6 py-5 text-xl font-bold border-b border-white/20">
            FTM SOCIETY
        </div>

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
               class="block px-4 py-2 rounded bg-indigo-600 text-white font-medium">
                <i class="fas fa-receipt mr-2"></i>Transactions
            </a>

            <a href="{{ route('member.profile') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
                <i class="fas fa-user mr-2"></i>Profile
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-white/20 text-xs text-white/60">
            © {{ date('Y') }} FTM Society
        </div>
    </aside>

    <!-- MAIN CONTENT -->
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
                        <div class="card-content">
                            
                            <!-- Status Section -->
                            <div class="status-section">
                                <div class="status-icon {{ $statusClass }}">
                                    <i class="fas {{ $statusIcon }}"></i>
                                </div>
                                <div class="status-info">
                                    <h3>{{ $statusText }}</h3>
                                    <p class="transaction-date">
                                        <i class="far fa-calendar"></i>
                                        {{ $t->created_at ? $t->created_at->format('d M Y, H:i') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="details-grid">
                                <!-- Order Code -->
                                <div class="detail-item">
                                    <span class="detail-label">Order Code</span>
                                    <span class="order-code">
                                        {{ Str::limit($t->transaction_id ?? $t->order_code ?? '-', 20) }}
                                    </span>
                                </div>

                                <!-- Package -->
                                @if(isset($t->package) && $t->package)
                                <div class="detail-item">
                                    <span class="detail-label">Package</span>
                                    <span class="detail-value">
                                        {{ Str::limit($t->package->name ?? '-', 25) }}
                                    </span>
                                </div>
                                @endif

                                <!-- Payment Method -->
                                <div class="detail-item">
                                    <span class="detail-label">Payment</span>
                                    @if($t->payment_type && $t->payment_type !== '-')
                                        <span class="payment-badge">
                                            <i class="fas fa-credit-card"></i>
                                            {{ strtoupper(str_replace('_', ' ', $t->payment_type)) }}
                                        </span>
                                    @else
                                        <span class="detail-value" style="color: #94a3b8;">-</span>
                                    @endif
                                </div>

                                <!-- Status Badge -->
                                <div class="detail-item">
                                    <span class="detail-label">Status</span>
                                    <span class="status-badge {{ $statusClass }}">
                                        <i class="fas {{ $statusIcon }}"></i>
                                        {{ ucfirst($t->status ?? 'Pending') }}
                                    </span>
                                </div>

                                <!-- Action Button -->
                                @if(isset($t->order_id))
                                <div class="detail-item">
                                    <span class="detail-label">Invoice</span>
                                    <a href="{{ route('invoice.show', $t->order_id) }}" 
                                       target="_blank"
                                       class="action-button">
                                        <i class="fas fa-download"></i>
                                        Download
                                    </a>
                                </div>
                                @endif
                            </div>

                            <!-- Amount Section -->
                            <div class="amount-section">
                                <div class="amount-label">Total Amount</div>
                                <div class="amount-value {{ $statusClass }}">
                                    Rp {{ number_format($t->amount ?? 0, 0, ',', '.') }}
                                </div>
                            </div>

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
            </div>

        </div>
    </main>

</div>

</body>
</html>