@php
    use Illuminate\Support\Facades\Auth;
    use App\Models\Customer;
    use Carbon\Carbon;
    
    $user = Auth::user();
    $currentDate = now()->format('l, d F Y');
    
    // Data dari Database - Customer Table
    $totalMembers = Customer::count();
    $activeMembers = Customer::where('is_login_active', 1)->count();
    $inactiveMembers = Customer::where('is_login_active', 0)->count();
    $newMembers = Customer::whereMonth('created_at', now()->month)->count();
    
    // Persentase pertumbuhan member
    $growthPercentage = $totalMembers > 0 ? round(($newMembers / $totalMembers) * 100, 1) : 0;
    
    // Recent Members (5 terbaru)
    $recentMembers = Customer::latest('created_at')->take(5)->get();
@endphp

<x-filament::page>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            /* Modern Professional Admin Color Palette */
            --primary: #4F46E5;
            --primary-dark: #4338CA;
            --primary-light: #6366F1;
            --primary-ultra-light: #EEF2FF;
            
            --secondary: #10B981;
            --secondary-light: #D1FAE5;
            
            --warning: #F59E0B;
            --warning-light: #FEF3C7;
            
            --danger: #EF4444;
            --danger-light: #FEE2E2;
            
            --info: #3B82F6;
            --info-light: #DBEAFE;
            
            /* Neutral Professional Grays */
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            
            --white: #FFFFFF;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            
            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }
        
        /* ==================== GLOBAL RESET ==================== */
        .fi-page {
            background: var(--gray-50) !important;
            padding: 0 !important;
            font-family: 'Inter', sans-serif !important;
        }
        
        .fi-page-heading {
            display: none !important;
        }
        
        /* ==================== DASHBOARD WRAPPER ==================== */
        .dashboard-wrapper {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* ==================== HEADER SECTION ==================== */
        .dashboard-header {
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.5rem;
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }
        
        .header-left h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
            margin: 0 0 0.5rem;
            letter-spacing: -0.5px;
        }
        
        .header-left p {
            font-size: 0.95rem;
            color: var(--gray-600);
            margin: 0;
            font-weight: 400;
        }
        
        .header-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary-ultra-light);
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gray-100);
            color: var(--gray-900);
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
        }
        
        /* ==================== KPI METRICS GRID ==================== */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .kpi-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        
        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--card-color);
        }
        
        .kpi-card:hover::before {
            opacity: 1;
        }
        
        .kpi-card.primary {
            --card-color: var(--primary);
            --card-color-light: var(--primary-light);
        }
        
        .kpi-card.success {
            --card-color: var(--secondary);
            --card-color-light: #34D399;
        }
        
        .kpi-card.warning {
            --card-color: var(--warning);
            --card-color-light: #FBBF24;
        }
        
        .kpi-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }
        
        .kpi-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            background: var(--icon-bg);
            color: var(--card-color);
            box-shadow: var(--shadow-sm);
        }
        
        .kpi-card.primary .kpi-icon-wrapper {
            --icon-bg: var(--primary-ultra-light);
        }
        
        .kpi-card.success .kpi-icon-wrapper {
            --icon-bg: var(--secondary-light);
        }
        
        .kpi-card.warning .kpi-icon-wrapper {
            --icon-bg: var(--warning-light);
        }
        
        .kpi-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.375rem 0.625rem;
            border-radius: var(--radius-sm);
        }
        
        .kpi-trend.up {
            background: var(--secondary-light);
            color: var(--secondary);
        }
        
        .kpi-trend.down {
            background: var(--danger-light);
            color: var(--danger);
        }
        
        .kpi-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.75rem;
        }
        
        .kpi-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1;
            margin-bottom: 0.75rem;
            letter-spacing: -1px;
        }
        
        .kpi-meta {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }
        
        .kpi-meta-highlight {
            color: var(--card-color);
            font-weight: 700;
        }
        
        /* ==================== MAIN CONTENT GRID ==================== */
        .dashboard-content {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 1.5rem;
        }
        
        /* ==================== CARD COMPONENT ==================== */
        .card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }
        
        .card:last-child {
            margin-bottom: 0;
        }
        
        .card:hover {
            border-color: var(--primary-light);
            box-shadow: var(--shadow-lg);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray-100);
        }
        
        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-icon {
            font-size: 1.25rem;
        }
        
        .card-action {
            font-size: 0.875rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-sm);
        }
        
        .card-action:hover {
            background: var(--primary-ultra-light);
            gap: 0.75rem;
        }
        
        /* ==================== TABLE STYLING ==================== */
        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-md);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table thead {
            background: var(--gray-50);
        }
        
        .table thead th {
            padding: 1rem 1.25rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gray-700);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--gray-200);
        }
        
        .table tbody tr {
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: var(--gray-50);
        }
        
        .table tbody tr:last-child {
            border-bottom: none;
        }
        
        .table tbody td {
            padding: 1.25rem;
            font-size: 0.9rem;
            color: var(--gray-700);
            font-weight: 500;
        }
        
        .member-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        
        .member-details {
            flex: 1;
            min-width: 0;
        }
        
        .member-name {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }
        
        .member-email {
            font-size: 0.8rem;
            color: var(--gray-600);
        }
        
        /* ==================== BADGE COMPONENTS ==================== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: var(--secondary-light);
            color: var(--secondary);
        }
        
        .badge-warning {
            background: var(--warning-light);
            color: var(--warning);
        }
        
        .badge-danger {
            background: var(--danger-light);
            color: var(--danger);
        }
        
        .badge-info {
            background: var(--info-light);
            color: var(--info);
        }
        
        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
        
        /* ==================== ACTION BUTTONS ==================== */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .action-btn {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
        }
        
        .action-btn-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        
        .action-btn-content {
            flex: 1;
        }
        
        .action-btn-title {
            font-weight: 600;
            margin-bottom: 0.125rem;
        }
        
        .action-btn-subtitle {
            font-size: 0.75rem;
            opacity: 0.8;
        }
        
        .action-btn.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        
        .action-btn.primary .action-btn-icon {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
        }
        
        .action-btn.secondary {
            background: var(--gray-100);
            color: var(--gray-900);
            border: 1px solid var(--gray-200);
        }
        
        .action-btn.secondary .action-btn-icon {
            background: var(--white);
        }
        
        .action-btn.secondary:hover {
            background: var(--white);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateX(4px);
        }
        
        /* ==================== STAT BOXES ==================== */
        .stat-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .stat-box {
            background: linear-gradient(135deg, var(--stat-color-light), var(--stat-color-ultra-light));
            padding: 1.5rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--stat-color-border);
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-box.primary {
            --stat-color-light: var(--primary-ultra-light);
            --stat-color-ultra-light: #F5F3FF;
            --stat-color-border: #C7D2FE;
            --stat-color: var(--primary);
        }
        
        .stat-box.success {
            --stat-color-light: var(--secondary-light);
            --stat-color-ultra-light: #ECFDF5;
            --stat-color-border: #A7F3D0;
            --stat-color: var(--secondary);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--stat-color);
        }
        
        .stat-box-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--stat-color);
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }
        
        .stat-box-label {
            font-size: 0.8rem;
            color: var(--gray-700);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--gray-600);
        }
        
        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }
        
        .empty-description {
            font-size: 0.9rem;
            color: var(--gray-600);
        }
        
        /* ==================== RESPONSIVE DESIGN ==================== */
        @media (max-width: 1200px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 1024px) {
            .dashboard-wrapper {
                padding: 1.5rem;
            }
            
            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
            }
            
            .header-right {
                align-self: stretch;
            }
            
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-wrapper {
                padding: 1rem;
            }
            
            .dashboard-header {
                padding: 1.25rem;
            }
            
            .header-left h1 {
                font-size: 1.5rem;
            }
            
            .kpi-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .kpi-card {
                padding: 1.25rem;
            }
            
            .kpi-value {
                font-size: 1.75rem;
            }
            
            .card {
                padding: 1.25rem;
            }
            
            .stat-grid {
                grid-template-columns: 1fr;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .header-left h1 {
                font-size: 1.25rem;
            }
            
            .header-left p {
                font-size: 0.85rem;
            }
            
            .kpi-icon-wrapper {
                width: 48px;
                height: 48px;
                font-size: 1.5rem;
            }
            
            .kpi-value {
                font-size: 1.5rem;
            }
        }

        /* ==================== MODAL STYLING ==================== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            width: 90%;
            max-width: 900px;
            max-height: 80vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 2px solid var(--gray-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: var(--white);
            z-index: 10;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .modal-close {
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-600);
            transition: color 0.2s;
            background: none;
            border: none;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            color: var(--gray-900);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-filters {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid var(--gray-200);
            background: var(--white);
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .filter-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .filter-btn.active {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .customer-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .customer-item {
            padding: 1rem;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            transition: all 0.2s;
        }

        .customer-item:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .customer-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .customer-name {
            font-weight: 700;
            color: var(--gray-900);
            font-size: 1.05rem;
        }

        .customer-status {
            font-size: 0.8rem;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
        }

        .customer-status.active {
            background: var(--secondary-light);
            color: var(--secondary);
        }

        .customer-status.inactive {
            background: var(--warning-light);
            color: var(--warning);
        }

        .customer-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .customer-info-item {
            display: flex;
            flex-direction: column;
        }

        .customer-info-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.25rem;
        }

        .customer-info-value {
            color: var(--gray-900);
        }

        .customer-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .action-link.whatsapp {
            background: #25D366;
            color: white;
        }

        .action-link.whatsapp:hover {
            background: #20BA5A;
            transform: translateY(-2px);
        }

        .action-link.view {
            background: var(--info-light);
            color: var(--info);
        }

        .action-link.view:hover {
            background: var(--info);
            color: white;
        }

        .loading {
            text-align: center;
            padding: 2rem;
            color: var(--gray-600);
        }

        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid var(--gray-200);
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .empty-state-modal {
            text-align: center;
            padding: 2rem;
            color: var(--gray-600);
        }

        .empty-icon-modal {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    
    </style>
    
    <div class="dashboard-wrapper">
        <!-- ==================== HEADER ==================== -->
        <div class="dashboard-header">
            <div class="header-left">
                <h1>🏋️ Dashboard Admin Gym</h1>
                <p>Kelola sistem gym Anda dengan dashboard yang powerful dan modern</p>
            </div>
            <div class="header-right">
                <div class="time-badge">
                    📅 {{ $currentDate }}
                </div>
                <div class="user-badge">
                    <div class="user-avatar">
                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                    </div>
                    {{ $user->name ?? 'Admin' }}
                </div>
            </div>
        </div>
        
        <!-- ==================== KPI METRICS ==================== -->
        <div class="kpi-grid">
            <!-- Total Members -->
            <div class="kpi-card primary" onclick="openCustomerModal('all')" style="cursor: pointer;">
                <div class="kpi-card-header">
                    <div class="kpi-icon-wrapper">
                        👥
                    </div>
                    <div class="kpi-trend up">
                        ↗ +{{ $growthPercentage }}%
                    </div>
                </div>
                <div class="kpi-label">Total Member Terdaftar</div>
                <div class="kpi-value">{{ $totalMembers }}</div>
                <div class="kpi-meta">
                    <span class="kpi-meta-highlight">{{ $newMembers }}</span> member baru bulan ini
                </div>
            </div>
            
            <!-- Active Members -->
            <div class="kpi-card success" onclick="openCustomerModal('active')" style="cursor: pointer;">
                <div class="kpi-card-header">
                    <div class="kpi-icon-wrapper">
                        ✅
                    </div>
                    <div class="kpi-trend up">
                        {{ $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0 }}%
                    </div>
                </div>
                <div class="kpi-label">Member Aktif</div>
                <div class="kpi-value">{{ $activeMembers }}</div>
                <div class="kpi-meta">
                    Dari total {{ $totalMembers }} member terdaftar
                </div>
            </div>
            
            <!-- Inactive Members -->
            <div class="kpi-card warning" onclick="openCustomerModal('inactive')" style="cursor: pointer;">
                <div class="kpi-card-header">
                    <div class="kpi-icon-wrapper">
                        ⏸️
                    </div>
                    <div class="kpi-trend down">
                        ⚠️ Perhatian
                    </div>
                </div>
                <div class="kpi-label">Member Tidak Aktif</div>
                <div class="kpi-value">{{ $inactiveMembers }}</div>
                <div class="kpi-meta">
                    <span class="kpi-meta-highlight">Perlu follow-up</span> dan re-engagement
                </div>
            </div>
        </div>
        
        <!-- ==================== MAIN CONTENT ==================== -->
        <div class="dashboard-content">
            <!-- LEFT COLUMN: Members Table -->
            <div>
                <!-- RECENT MEMBERS -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <span class="card-icon">👤</span>
                            Member Terbaru
                        </h2>
                        <a href="#" class="card-action">
                            Lihat Semua
                            <span>→</span>
                        </a>
                    </div>
                    
                    @if($recentMembers->count() > 0)
                    <div class="table-wrapper">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Email</th>
                                    <th>Bergabung</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMembers as $member)
                                <tr>
                                    <td>
                                        <div class="member-info">
                                            <div class="member-avatar">
                                                {{ strtoupper(substr($member->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="member-details">
                                                <div class="member-name">{{ $member->name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="member-email">{{ $member->email ?? 'N/A' }}</div>
                                    </td>
                                    <td>{{ $member->created_at->diffForHumans() }}</td>
                                    <td>
                                        @php $isActive = (int) ($member->is_login_active ?? 0); @endphp
                                        <span class="badge {{ $isActive === 1 ? 'badge-success' : 'badge-warning' }}">
                                            <span class="badge-dot"></span>
                                            {{ $isActive === 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <div class="empty-title">Belum Ada Member Terbaru</div>
                        <div class="empty-description">Member yang baru mendaftar akan muncul di sini</div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- RIGHT COLUMN: Actions & Summary -->
            <div>
                <!-- QUICK ACTIONS -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <span class="card-icon">⚡</span>
                            Aksi Cepat
                        </h2>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="action-btn primary">
                            <div class="action-btn-icon">➕</div>
                            <div class="action-btn-content">
                                <div class="action-btn-title">Tambah Member Baru</div>
                                <div class="action-btn-subtitle">Daftarkan member baru</div>
                            </div>
                        </button>
                        
                        <button class="action-btn secondary">
                            <div class="action-btn-icon">📅</div>
                            <div class="action-btn-content">
                                <div class="action-btn-title">Jadwal Kelas</div>
                                <div class="action-btn-subtitle">Kelola jadwal kelas gym</div>
                            </div>
                        </button>
                        
                        <button class="action-btn secondary">
                            <div class="action-btn-icon">💳</div>
                            <div class="action-btn-content">
                                <div class="action-btn-title">Kelola Pembayaran</div>
                                <div class="action-btn-subtitle">Proses pembayaran member</div>
                            </div>
                        </button>
                        
                        <button class="action-btn secondary">
                            <div class="action-btn-icon">📊</div>
                            <div class="action-btn-content">
                                <div class="action-btn-title">Laporan</div>
                                <div class="action-btn-subtitle">Lihat laporan lengkap</div>
                            </div>
                        </button>
                    </div>
                </div>
                
                <!-- SUMMARY STATS -->
                <div class="card">
                    <div class="card-header">
                        <h2>
                            <span class="card-icon">📈</span>
                            Ringkasan Statistik
                        </h2>
                    </div>
                    
                    <div class="stat-grid">
                        <div class="stat-box primary">
                            <div class="stat-icon">👥</div>
                            <div class="stat-box-value">{{ $totalMembers }}</div>
                            <div class="stat-box-label">Total Member</div>
                        </div>
                        
                        <div class="stat-box success">
                            <div class="stat-icon">✅</div>
                            <div class="stat-box-value">{{ $activeMembers }}</div>
                            <div class="stat-box-label">Member Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== CUSTOMER LIST MODAL ==================== -->
    <div id="customerModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Member</h2>
                <button class="modal-close" onclick="closeCustomerModal()">✕</button>
            </div>
           
                
                <div id="customerListContainer">
                    <div class="loading">
                        <div class="loading-spinner"></div>
                        <p>Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentFilter = 'all';

        /**
         * Buka modal dengan menampilkan customer list
         */
        function openCustomerModal(filterType = 'all') {
            currentFilter = filterType;
            document.getElementById('customerModal').classList.add('active');
            updateModalTitle(filterType);
            loadCustomers(filterType);
        }

        /**
         * Tutup modal
         */
        function closeCustomerModal() {
            document.getElementById('customerModal').classList.remove('active');
        }

        /**
         * Update title modal sesuai filter
         */
        function updateModalTitle(filterType) {
            const titles = {
                'all': '📊 Daftar Semua Member',
                'active': '✅ Member Aktif',
                'inactive': '⏸️ Member Tidak Aktif - Follow Up Required',
                'no-package': '🎁 Member Belum Beli Package - Follow Up'
            };
            document.getElementById('modalTitle').textContent = titles[filterType] || 'Member';
        }

        /**
         * Filter customers dan update list
         */
        function filterCustomers(filterType) {
            currentFilter = filterType;
            
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            updateModalTitle(filterType);
            loadCustomers(filterType);
        }

        /**
         * Load customer list from server
         */
        function loadCustomers(filterType) {
            const container = document.getElementById('customerListContainer');
            container.innerHTML = '<div class="loading"><div class="loading-spinner"></div><p>Memuat data...</p></div>';

            console.log('Loading customers with filter:', filterType);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            console.log('CSRF Token available:', !!csrfToken);

            fetch(`/api/customers?status=${filterType}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'include' // Include cookies for authentication
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                if (data.customers && data.customers.length > 0) {
                    console.log(`Found ${data.customers.length} customers`);
                    renderCustomerList(data.customers, filterType);
                } else {
                    const emptyMessages = {
                        'all': 'Tidak ada member yang ditemukan',
                        'active': 'Tidak ada member aktif',
                        'inactive': 'Tidak ada member tidak aktif',
                        'no-package': 'Tidak ada member yang belum membeli package'
                    };
                    
                    container.innerHTML = `
                        <div class="empty-state-modal">
                            <div class="empty-icon-modal">📭</div>
                            <h3>Tidak Ada Data</h3>
                            <p>${emptyMessages[filterType] || 'Tidak ada data'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading customers:', error);
                console.error('Error message:', error.message);
                container.innerHTML = `
                    <div class="empty-state-modal">
                        <div class="empty-icon-modal">⚠️</div>
                        <h3>Error</h3>
                        <p>Gagal memuat data. Silakan coba lagi. (${error.message})</p>
                    </div>
                `;
            });
        }

        /**
         * Render customer list
         */
        function renderCustomerList(customers, filterType) {
            const container = document.getElementById('customerListContainer');
            
            let html = '<div class="modal-filters">';
            html += '<button class="filter-btn ' + (currentFilter === 'all' ? 'active' : '') + '" onclick="filterCustomers(\'all\')">📊 Semua Member</button>';
            html += '<button class="filter-btn ' + (currentFilter === 'active' ? 'active' : '') + '" onclick="filterCustomers(\'active\')">✅ Member Aktif</button>';
            html += '<button class="filter-btn ' + (currentFilter === 'inactive' ? 'active' : '') + '" onclick="filterCustomers(\'inactive\')">⏸️ Member Tidak Aktif</button>';
           
            html += '</div>';
            
            html += '<ul class="customer-list">';

            customers.forEach(customer => {
                const isActive = customer.is_login_active === 1;
                const statusBadge = isActive 
                    ? '<span class="customer-status active">✅ Aktif</span>' 
                    : '<span class="customer-status inactive">⏸️ Tidak Aktif</span>';

                const lastActive = customer.last_activity 
                    ? `${customer.last_activity}` 
                    : 'Tidak ada activity';

                const joinDate = customer.created_at || 'N/A';
                const phone = customer.phone_number || 'N/A';
                
                // WhatsApp action - show for all customers with phone
                let whatsappAction = '';
                if (customer.phone_number && customer.whatsapp_url) {
                    whatsappAction = `
                        <a href="${customer.whatsapp_url}" 
                           target="_blank" 
                           class="action-link whatsapp"
                           title="Kirim pesan follow-up via WhatsApp">
                            💬 Kirim WhatsApp
                        </a>
                    `;
                }

                html += `
                    <li class="customer-item">
                        <div class="customer-item-header">
                            <div class="customer-name">${customer.name || 'N/A'}</div>
                            ${statusBadge}
                        </div>

                        <div class="customer-info">
                            <div class="customer-info-item">
                                <span class="customer-info-label">📧 Email</span>
                                <span class="customer-info-value">${customer.email || 'N/A'}</span>
                            </div>
                            <div class="customer-info-item">
                                <span class="customer-info-label">📱 Telepon</span>
                                <span class="customer-info-value">${phone}</span>
                            </div>
                            <div class="customer-info-item">
                                <span class="customer-info-label">📅 Bergabung</span>
                                <span class="customer-info-value">${joinDate}</span>
                            </div>
                            <div class="customer-info-item">
                                <span class="customer-info-label">⏱️ Aktivitas Terakhir</span>
                                <span class="customer-info-value">${lastActive}</span>
                            </div>
                        </div>

                        <div class="customer-actions">
                            <a href="/filament/resources/customers/${customer.id}" class="action-link view">👁️ Lihat Detail</a>
                            ${whatsappAction}
                        </div>
                    </li>
                `;
            });

            html += '</ul>';
            container.innerHTML = html;
        }

        /**
         * Generate WhatsApp URL dengan message template
         */
        function getWhatsAppUrl(phoneNumber, customerName) {
            if (!phoneNumber) return '#';

            // Ensure phone format
            let phone = phoneNumber.replace(/^0/, '62').replace(/[^0-9]/g, '');
            
            // Default message template
            const message = `Halo ${customerName}! 👋\n\nKami rindu Anda di FTM Society! 💪\n\nApakah ada yang bisa kami bantu terkait membership Anda? Kami memiliki program spesial untuk member setia seperti Anda.\n\nJangan ragu untuk menghubungi kami ya! 😊\n\nhttp://ftmsociety.id`;

            return `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        }

        /**
         * Close modal ketika klik overlay
         */
        document.getElementById('customerModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCustomerModal();
            }
        });
    </script>
</x-filament::page>