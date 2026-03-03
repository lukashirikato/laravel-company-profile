<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner - FTM Society</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script src="https://cdn.jsdelivr.net/npm/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet" />
    <style>
        .scanner-overlay {
            position: relative;
            overflow: hidden;
        }
        
        .scanner-border {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            border: 3px solid #c68e8f;
            border-radius: 0;
        }
        
        .scanner-corner {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 3px solid #c68e8f;
        }
        
        .scanner-corner.top-left {
            top: calc(50% - 150px);
            left: calc(50% - 150px);
            border-right: none;
            border-bottom: none;
        }
        
        .scanner-corner.top-right {
            top: calc(50% - 150px);
            right: calc(50% - 150px);
            border-left: none;
            border-bottom: none;
        }
        
        .scanner-corner.bottom-left {
            bottom: calc(50% - 150px);
            left: calc(50% - 150px);
            border-right: none;
            border-top: none;
        }
        
        .scanner-corner.bottom-right {
            bottom: calc(50% - 150px);
            right: calc(50% - 150px);
            border-left: none;
            border-top: none;
        }
        
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .scan-line {
            position: absolute;
            width: 300px;
            height: 2px;
            background: linear-gradient(to right, transparent, #c68e8f, transparent);
            top: calc(50% - 150px);
            left: calc(50% - 150px);
            animation: scanLine 2s infinite;
        }
        
        @keyframes scanLine {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(300px);
            }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <!-- FIXED HEADER -->
    <header class="fixed top-0 w-full bg-gray-800 z-50 border-b border-gray-700">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('member.dashboard') }}" class="text-gray-400 hover:text-white">
                    <i class="ri-arrow-left-line text-xl"></i>
                </a>
                <h1 class="text-lg font-semibold">QR Scanner</h1>
            </div>
            <span class="text-sm text-gray-400">{{ auth('customer')->user()->name }}</span>
        </div>
    </header>

    <main class="pt-20 pb-20">
        <!-- SCANNER SECTION -->
        <div id="scanner-container" class="relative w-full bg-black" style="height: 70vh;">
            <!-- Video element will be added here -->
            <video id="qr-video" style="width: 100%; height: 100%; object-fit: cover;"></video>
            
            <!-- Scanner Border & Corners -->
            <div class="scanner-overlay">
                <div class="scanner-border">
                    <div class="scanner-corner top-left"></div>
                    <div class="scanner-corner top-right"></div>
                    <div class="scanner-corner bottom-left"></div>
                    <div class="scanner-corner bottom-right"></div>
                    <div class="scan-line"></div>
                </div>
            </div>
            
            <!-- Loading -->
            <div id="scanner-loading" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="text-center">
                    <div class="animate-spin text-3xl text-primary mb-4">
                        <i class="ri-loader-4-line"></i>
                    </div>
                    <p class="text-gray-300">Initializing camera...</p>
                </div>
            </div>
        </div>

        <!-- INFO SECTION -->
        <div class="max-w-2xl mx-auto px-4 py-8">
            <!-- Status Card -->
            <div id="status-card" class="glass-effect rounded-xl p-6 mb-6 hidden">
                <div class="flex items-start gap-4">
                    <div id="status-icon" class="text-3xl mt-1"></div>
                    <div class="flex-1">
                        <h2 id="status-title" class="text-xl font-bold mb-2"></h2>
                        <p id="status-message" class="text-gray-300 mb-4"></p>
                        <div id="status-details" class="text-sm text-gray-400 space-y-1"></div>
                    </div>
                </div>
            </div>

            <!-- Active Session Card (saat check-in berhasil) -->
            <div id="active-session-card" class="glass-effect rounded-xl p-6 mb-6 hidden bg-green-900/20 border-green-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-green-400">✓ Sesi Aktif</h3>
                    <button onclick="checkoutSession()" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold transition">
                        <i class="ri-logout-circle-r-line mr-2"></i>Checkout
                    </button>
                </div>
                <div id="session-details" class="space-y-2 text-sm"></div>
            </div>

            <!-- Instructions -->
            <div class="glass-effect rounded-xl p-6">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i class="ri-information-line"></i> Petunjuk Penggunaan
                </h3>
                <ul class="space-y-3 text-sm text-gray-300">
                    <li class="flex gap-3">
                        <div class="text-primary font-bold mt-1">1</div>
                        <span>Pastikan cahaya cukup dan QR code terlihat jelas dalam frame</span>
                    </li>
                    <li class="flex gap-3">
                        <div class="text-primary font-bold mt-1">2</div>
                        <span>Arahkan kamera ke QR code pada member card</span>
                    </li>
                    <li class="flex gap-3">
                        <div class="text-primary font-bold mt-1">3</div>
                        <span>Tunggu hingga sistem mengenali dan memproses QR code</span>
                    </li>
                    <li class="flex gap-3">
                        <div class="text-primary font-bold mt-1">4</div>
                        <span>Jika check-in berhasil, klik Checkout saat selesai berolahraga</span>
                    </li>
                </ul>
            </div>
        </div>
    </main>

    <!-- BOTTOM ACTION BAR -->
    <div class="fixed bottom-0 w-full bg-gray-800 border-t border-gray-700 p-4">
        <div class="max-w-2xl mx-auto flex gap-3">
            <button onclick="toggleCamera()" id="camera-toggle-btn"
                class="flex-1 bg-gray-700 hover:bg-gray-600 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                <i class="ri-camera-line"></i>
                <span>Stop Camera</span>
            </button>
            <a href="{{ route('member.dashboard') }}"
                class="flex-1 bg-primary hover:bg-secondary py-3 rounded-lg font-semibold transition text-white flex items-center justify-center gap-2">
                <i class="ri-home-line"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <script>
        const token = '{{ csrf_token() }}';
        let qrScanner;
        let cameraActive = true;
        let activeAttendanceId = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', initScanner);

        async function initScanner() {
            try {
                qrScanner = new QrScanner(
                    document.getElementById('qr-video'),
                    onScanSuccess,
                    {
                        onDecodeError: onDecodeError,
                        preferredCamera: 'environment',
                        highlightCodeOutline: false,
                        maxScansPerSecond: 5,
                    }
                );
                
                await qrScanner.start();
                document.getElementById('scanner-loading').classList.add('hidden');
            } catch (error) {
                console.error('Camera access denied:', error);
                showError('Akses kamera ditolak. Silakan izinkan akses kamera di pengaturan browser');
            }
        }

        function onScanSuccess(result) {
            const qrData = result.data;
            console.log('QR Scanned:', qrData);
            
            // Pause scanner saat processing
            qrScanner?.pause();
            
            processQRScan(qrData);
        }

        function onDecodeError(error) {
            // Ignore decode errors
        }

        async function processQRScan(qrData) {
            try {
                showLoading('Memproses QR code...');
                
                const response = await fetch('{{ route("qr.scan") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ qr_data: qrData }),
                });

                const result = await response.json();
                
                if (result.success) {
                    handleScanSuccess(result.data);
                } else {
                    handleScanError(result);
                }

                // Resume scanner after 3s
                setTimeout(() => qrScanner?.start(), 3000);
            } catch (error) {
                showError('Gagal memproses QR code: ' + error.message);
                qrScanner?.start();
            }
        }

        function handleScanSuccess(data) {
            activeAttendanceId = data.attendance_id;
            
            const statusCard = document.getElementById('status-card');
            const activeCard = document.getElementById('active-session-card');
            const sessionDetails = document.getElementById('session-details');
            
            // Show success status
            document.getElementById('status-icon').innerHTML = '✓';
            document.getElementById('status-icon').className = 'text-4xl text-green-400';
            document.getElementById('status-title').textContent = 'Check-in Berhasil!';
            document.getElementById('status-message').textContent = `Selamat datang ${data.customer_name}!`;
            
            const details = `
                <div><strong>Kelas:</strong> ${data.class_name}</div>
                <div><strong>Jam:</strong> ${data.check_in_time}</div>
                <div><strong>Quota Tersisa:</strong> ${data.quota_remaining}</div>
            `;
            document.getElementById('status-details').innerHTML = details;
            
            statusCard.classList.remove('hidden');
            statusCard.classList.add('bg-green-900/20', 'border-green-700');
            
            // Show active session card
            const sessionHTML = `
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-400 text-xs mb-1">MEMBER</div>
                        <div class="font-bold">${data.customer_name}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">KELAS</div>
                        <div class="font-bold">${data.class_name}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">CHECK-IN</div>
                        <div class="font-bold">${data.check_in_time}</div>
                    </div>
                    <div>
                        <div class="text-gray-400 text-xs mb-1">DURASI</div>
                        <div class="font-bold">00:00</div>
                    </div>
                </div>
            `;
            sessionDetails.innerHTML = sessionHTML;
            activeCard.classList.remove('hidden');
        }

        function handleScanError(result) {
            const statusCard = document.getElementById('status-card');
            const activeCard = document.getElementById('active-session-card');
            
            activeCard.classList.add('hidden');
            
            let icon = '⚠️';
            let title = 'Scan Gagal';
            let bgClass = 'bg-red-900/20 border-red-700';
            
            switch(result.type) {
                case 'no_booking':
                    icon = '📅';
                    title = 'Tidak Ada Booking';
                    break;
                case 'no_quota':
                    icon = '📊';
                    title = 'Quota Habis';
                    break;
                case 'expired_package':
                    icon = '⏰';
                    title = 'Paket Expired';
                    break;
                case 'already_checkin':
                    icon = '✓';
                    title = 'Sudah Check-in';
                    bgClass = 'bg-blue-900/20 border-blue-700';
                    break;
            }
            
            document.getElementById('status-icon').textContent = icon;
            document.getElementById('status-icon').className = 'text-4xl';
            document.getElementById('status-title').textContent = title;
            document.getElementById('status-message').textContent = result.message;
            document.getElementById('status-details').innerHTML = '';
            
            statusCard.classList.remove('hidden', 'bg-green-900/20', 'border-green-700');
            statusCard.classList.add(bgClass);
        }

        function showLoading(message) {
            const statusCard = document.getElementById('status-card');
            const activeCard = document.getElementById('active-session-card');
            
            activeCard.classList.add('hidden');
            
            document.getElementById('status-icon').innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
            document.getElementById('status-icon').className = 'text-3xl text-blue-400';
            document.getElementById('status-title').textContent = message;
            document.getElementById('status-message').textContent = '';
            document.getElementById('status-details').innerHTML = '';
            
            statusCard.classList.remove('hidden', 'bg-green-900/20', 'border-green-700', 'bg-red-900/20', 'border-red-700');
            statusCard.classList.add('bg-blue-900/20', 'border-blue-700');
        }

        function showError(message) {
            const statusCard = document.getElementById('status-card');
            const activeCard = document.getElementById('active-session-card');
            
            activeCard.classList.add('hidden');
            
            document.getElementById('status-icon').textContent = '❌';
            document.getElementById('status-icon').className = 'text-4xl';
            document.getElementById('status-title').textContent = 'Error';
            document.getElementById('status-message').textContent = message;
            document.getElementById('status-details').innerHTML = '';
            
            statusCard.classList.remove('hidden', 'bg-green-900/20', 'border-green-700');
            statusCard.classList.add('bg-red-900/20', 'border-red-700');
        }

        async function checkoutSession() {
            if (!activeAttendanceId) return;
            
            try {
                const response = await fetch('{{ route("qr.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify({ attendance_id: activeAttendanceId }),
                });

                const result = await response.json();
                
                if (result.success) {
                    const statusCard = document.getElementById('status-card');
                    const activeCard = document.getElementById('active-session-card');
                    
                    document.getElementById('status-icon').textContent = '⏁';
                    document.getElementById('status-icon').className = 'text-4xl text-blue-400';
                    document.getElementById('status-title').textContent = 'Check-out Berhasil';
                    document.getElementById('status-message').textContent = result.message;
                    
                    const details = `
                        <div><strong>Durasi:</strong> ${result.data.duration}</div>
                        <div><strong>Jam Keluar:</strong> ${result.data.check_out_time}</div>
                    `;
                    document.getElementById('status-details').innerHTML = details;
                    
                    statusCard.classList.remove('hidden', 'bg-red-900/20', 'border-red-700', 'bg-green-900/20', 'border-green-700');
                    statusCard.classList.add('bg-blue-900/20', 'border-blue-700');
                    
                    activeCard.classList.add('hidden');
                    activeAttendanceId = null;
                } else {
                    showError(result.message);
                }
            } catch (error) {
                showError('Gagal checkout: ' + error.message);
            }
        }

        function toggleCamera() {
            if (cameraActive) {
                qrScanner?.pause();
                document.getElementById('qr-video').style.opacity = '0.3';
                document.getElementById('camera-toggle-btn').textContent = '▶ Start Camera';
                cameraActive = false;
            } else {
                qrScanner?.start();
                document.getElementById('qr-video').style.opacity = '1';
                document.getElementById('camera-toggle-btn').innerHTML = '<i class="ri-camera-line"></i><span>Stop Camera</span>';
                cameraActive = true;
            }
        }
    </script>
</body>
</html>
