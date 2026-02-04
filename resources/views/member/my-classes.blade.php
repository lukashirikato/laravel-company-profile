<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Classes | FTM Society</title>
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
            margin-bottom: 2.5rem;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.75rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
        }

        .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            margin-bottom: 0.875rem;
            font-size: 1.25rem;
        }

        .stat-icon.blue { 
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
        }
        .stat-icon.purple { 
            background: linear-gradient(135deg, #f3e8ff 0%, #e9d5ff 100%);
            color: #7c3aed;
        }

        .classes-section h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 1.5rem;
            letter-spacing: -0.025em;
        }

        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 1.5rem;
        }

        .class-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .class-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.1);
            transform: translateY(-4px);
        }

        .card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6 0%, #8b5cf6 100%);
        }

        .class-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.25rem;
            letter-spacing: -0.025em;
        }

        .class-category {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .package-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #fcd34d;
            color: #92400e;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8125rem;
            margin-bottom: 1.25rem;
            width: 100%;
            justify-content: center;
        }

        .package-icon {
            font-size: 0.875rem;
        }

        .schedule-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .schedule-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #3b82f6;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .schedule-details {
            display: flex;
            flex-direction: column;
        }

        .schedule-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .schedule-value {
            font-size: 0.95rem;
            color: #0f172a;
            font-weight: 600;
        }

        .instructor-info {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            margin-bottom: 1.25rem;
            padding: 0.875rem;
            background: #f8fafc;
            border-radius: 10px;
        }

        .instructor-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .instructor-details {
            flex: 1;
            min-width: 0;
        }

        .instructor-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.125rem;
        }

        .instructor-name {
            font-size: 0.95rem;
            color: #0f172a;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #bbf7d0;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: #16a34a;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
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

        .btn-book {
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
            border: none;
            cursor: pointer;
        }

        .btn-book:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        @media (max-width: 1024px) {
            .main-content {
                padding: 2rem;
            }

            .classes-grid {
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .classes-grid {
                grid-template-columns: 1fr;
            }

            .schedule-info {
                grid-template-columns: 1fr;
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
               class="block px-4 py-2 rounded bg-indigo-600 text-white font-medium">
                <i class="fas fa-dumbbell mr-2"></i>My Classes
            </a>

            <a href="{{ route('member.transactions') }}" 
               class="block px-4 py-2 rounded hover:bg-white/10 transition">
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
                <h1>My Classes</h1>
                <p>Manage your scheduled workout sessions</p>
            </div>

            @if(!$myClasses->isEmpty())
                <!-- Stats Overview -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-label">Total Scheduled Classes</div>
                        <div class="stat-value">{{ $stats['total_classes'] ?? 0 }}</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i class="fas fa-fire-flame-curved"></i>
                        </div>
                        <div class="stat-label">Active Memberships</div>
                        <div class="stat-value">{{ $stats['unique_packages'] ?? 0 }}</div>
                    </div>
                </div>
            @endif

            <!-- Classes Section -->
            <div class="classes-section">
                @if($myClasses->isEmpty())
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-xmark" style="color: #94a3b8;"></i>
                        </div>
                        <h3>No Scheduled Classes</h3>
                        <p>You haven't booked any classes yet. Start your fitness journey today!</p>
                        <a href="{{ route('member.book') }}" class="btn-book">
                            <i class="fas fa-plus-circle"></i>
                            Book Your First Class
                        </a>
                    </div>
                @else
                    <h2>Scheduled Sessions</h2>
                    <div class="classes-grid">
                        @foreach($myClasses as $item)
                            @php
                                $className = $item->schedule->classModel->class_name ?? 'Class';
                                $classType = '';
                                
                                if (str_contains($className, 'Pilates')) {
                                    $classType = 'Pilates';
                                } elseif (str_contains($className, 'Muaythai')) {
                                    $classType = 'Muaythai';
                                } elseif (str_contains($className, 'Body Shaping')) {
                                    $classType = 'Body Shaping';
                                } else {
                                    $classType = 'Fitness';
                                }

                                $instructor = $item->schedule->instructor ?? 'FTM Coach';
                                
                                $words = explode(' ', $instructor);
                                $initials = '';
                                foreach ($words as $word) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                                if (strlen($initials) > 2) {
                                    $initials = substr($initials, 0, 2);
                                }

                                // ✅ GET PACKAGE NAME dari package_info
                                $packageName = $item->package_info['name'] ?? 'Your Package';
                            @endphp

                            <div class="class-card">
                                <!-- Card Header -->
                                <div class="card-header">
                                    <div class="class-category">{{ $classType }}</div>
                                    <h3 class="class-title">{{ $className }}</h3>
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <!-- Package Badge -->
                                    <div class="package-badge">
                                        <i class="fas fa-box-open package-icon"></i>
                                        <span>{{ $packageName }}</span>
                                    </div>

                                    <!-- Schedule Info -->
                                    <div class="schedule-info">
                                        <div class="schedule-item">
                                            <div class="schedule-icon">
                                                <i class="fas fa-calendar-day"></i>
                                            </div>
                                            <div class="schedule-details">
                                                <span class="schedule-label">Day</span>
                                                <span class="schedule-value">{{ $item->schedule->day }}</span>
                                            </div>
                                        </div>

                                        <div class="schedule-item">
                                            <div class="schedule-icon">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="schedule-details">
                                                <span class="schedule-label">Time</span>
                                                <span class="schedule-value">
                                                    {{ \Carbon\Carbon::parse($item->schedule->class_time)->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Instructor Info -->
                                    <div class="instructor-info">
                                        <div class="instructor-avatar">
                                            {{ $initials }}
                                        </div>
                                        <div class="instructor-details">
                                            <div class="instructor-label">Instructor</div>
                                            <div class="instructor-name">{{ $instructor }}</div>
                                        </div>
                                    </div>

                                    <!-- Status Badge -->
                                    <div class="status-badge">
                                        <span class="status-dot"></span>
                                        Confirmed
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </main>

</div>

</body>
</html>