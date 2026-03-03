@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 to-black py-8">
    <div class="max-w-3xl mx-auto px-4">
        
        <!-- HEADER -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-white mb-2">
                    <i class="ri-qr-scan-2-line mr-3 text-red-600"></i>Member Check-in Scanner
                </h1>
                <p class="text-gray-400">Scan QR code member untuk automatic check-in dan pengurangan quota</p>
            </div>
            <a href="{{ url('/admin') }}" class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                <i class="ri-arrow-left-line"></i> Back to Admin
            </a>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- MAIN SCANNER AREA -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- INFO BOX -->
                <div class="bg-blue-900/30 border border-blue-700 rounded-xl p-4 shadow-lg">
                    <p class="text-blue-200 text-sm flex gap-2">
                        <i class="ri-information-line flex-shrink-0 mt-0.5"></i>
                        <span><strong>Cara Kerja:</strong> Scan atau paste QR token member. Sistem akan otomatis kurangi quota dan catat absensi. Setiap member hanya bisa check-in 1x per hari.</span>
                    </p>
                </div>

                <!-- SCANNER CARD -->
                <div class="bg-slate-800 rounded-xl p-8 border border-slate-700 shadow-xl">
                    
                    <!-- QR Token Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-300 mb-3">
                            <i class="ri-qr-code-line mr-2"></i>QR Token / Member ID
                        </label>
                        <input type="text" 
                               id="qr-input" 
                               placeholder="Scan QR code atau paste token di sini..."
                               class="w-full px-4 py-4 bg-slate-700 border-2 border-slate-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600 text-lg font-mono transition-all"
                               autofocus>
                    </div>

                    <!-- Program & Location -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">Program (Opsional)</label>
                            <input type="text" 
                                   id="program-input" 
                                   placeholder="Boxing, Yoga, dll"
                                   class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-red-600">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-300 mb-2">Location (Opsional)</label>
                            <select id="location-input" class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-1 focus:ring-red-600">
                                <option value="Gym Entrance">Gym Entrance</option>
                                <option value="Main Hall">Main Hall</option>
                                <option value="Studio">Studio</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button onclick="submitScan()" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-4 rounded-lg transition-all transform hover:scale-105 flex items-center justify-center gap-3 shadow-lg">
                        <i class="ri-check-double-fill text-xl"></i> 
                        <span>Process Check-in</span>
                    </button>
                </div>

                <!-- RESULT DISPLAY -->
                <div id="result-area" class="hidden">
                    <div id="result-message" class="rounded-xl p-6 border shadow-xl text-center"></div>
                    <button onclick="resetScan()" class="mt-4 w-full bg-slate-700 hover:bg-slate-600 text-white font-semibold py-3 rounded-lg transition">
                        <i class="ri-refresh-line mr-2"></i>Scan Member Berikutnya
                    </button>
                </div>
            </div>

            <!-- SIDEBAR STATS -->
            <div class="space-y-4">
                
                <!-- TODAY STATS -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl p-6 border border-slate-700 shadow-lg">
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-4 flex items-center gap-2">
                        <i class="ri-calendar-today-line"></i>Hari Ini
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-400 text-xs">Total Check-in</p>
                            <p id="today-count" class="text-4xl font-bold text-white">0</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-green-900/20 border border-green-700 rounded-lg p-3">
                                <p class="text-green-400 text-xs font-bold">SUKSES</p>
                                <p id="success-count" class="text-2xl font-bold text-green-400">0</p>
                            </div>
                            <div class="bg-red-900/20 border border-red-700 rounded-lg p-3">
                                <p class="text-red-400 text-xs font-bold">ERROR</p>
                                <p id="error-count" class="text-2xl font-bold text-red-400">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TIPS -->
                <div class="bg-amber-900/20 border border-amber-700 rounded-xl p-4 shadow-lg">
                    <h4 class="text-amber-400 font-bold text-sm mb-2">
                        <i class="ri-lightbulb-line mr-1"></i>Tips:
                    </h4>
                    <ul class="text-amber-200 text-xs space-y-1">
                        <li>✓ Gunakan barcode scanner atau paste QR</li>
                        <li>✓ Press Enter untuk auto-submit</li>
                        <li>✓ Quota auto berkurang</li>
                        <li>✓ Member hanya 1x/hari</li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- SCAN LOGS -->
        <div class="mt-8 bg-slate-800 rounded-xl p-6 border border-slate-700 shadow-xl">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <i class="ri-history-line text-red-600"></i>Recent Check-ins (Last 20)
            </h3>
            <div id="scans-log" class="space-y-2 max-h-64 overflow-y-auto">
                <p class="text-gray-400 text-sm text-center py-8">Hasil scan akan muncul di sini...</p>
            </div>
        </div>

    </div>
</div>

<script>
    let successCount = 0;
    let errorCount = 0;
    const scansLog = [];
    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Auto submit with Enter key
    document.getElementById('qr-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitScan();
        }
    });

    async function submitScan() {
        const qrToken = document.getElementById('qr-input').value.trim();
        const program = document.getElementById('program-input').value.trim();
        const location = document.getElementById('location-input').value;

        if (!qrToken) {
            showAlert('QR Token tidak boleh kosong!', 'error');
            document.getElementById('qr-input').focus();
            return;
        }

        try {
            // Disable button & show loading
            const btn = document.querySelector('button[onclick="submitScan()"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Processing...';
            }

            const response = await fetch('/api/member/scan-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    qr_token: qrToken,
                    program: program,
                    location: location
                })
            });

            const result = await response.json();

            if (result.success) {
                successCount++;
                showSuccessResult(result.data);
                logScan(result.data, 'success');
            } else {
                errorCount++;
                showErrorResult(result.message);
                logScan({message: result.message}, 'error');
            }

            updateStats();

            // Re-enable button
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="ri-check-double-fill text-xl mr-2"></i><span>Process Check-in</span>';
            }

        } catch (error) {
            errorCount++;
            showErrorResult('Error: ' + (error.message || 'Terjadi kesalahan'));
            logScan({message: error.message}, 'error');
            updateStats();
        }
    }

    function showSuccessResult(data) {
        const resultArea = document.getElementById('result-area');
        const resultMsg = document.getElementById('result-message');

        resultMsg.className = 'bg-gradient-to-r from-green-900/30 to-green-900/20 border-2 border-green-600 rounded-xl p-8';
        resultMsg.innerHTML = `
            <div class="text-center">
                <div class="text-6xl mb-4 animate-pulse">✅</div>
                <h3 class="text-3xl font-bold text-green-400 mb-2">CHECK-IN BERHASIL!</h3>
                
                <div class="bg-green-900/40 rounded-lg p-6 my-6 space-y-3 text-left max-w-md mx-auto">
                    <div class="border-l-4 border-green-500 pl-3">
                        <p class="text-gray-200 text-sm">Nama Member</p>
                        <p class="text-white font-bold text-lg">${data.member_name} (${data.member_id})</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-3">
                        <p class="text-gray-200 text-sm">Program</p>
                        <p class="text-white font-bold">${data.program}</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-3">
                        <p class="text-gray-200 text-sm">Waktu Check-in</p>
                        <p class="text-white font-bold">${data.check_in_time} - ${data.check_in_date}</p>
                    </div>
                    <div class="border-l-4 border-green-500 pl-3">
                        <p class="text-gray-200 text-sm">Paket</p>
                        <p class="text-white font-bold">${data.package_name}</p>
                    </div>
                </div>

                <div class="bg-red-900/30 border-2 border-red-600 rounded-lg p-4 my-6">
                    <p class="text-red-300 text-xs uppercase font-bold mb-2">📊 Quota Status</p>
                    <p class="text-white font-black text-3xl mb-2">${data.remaining_quota} / ${data.total_quota}</p>
                    <div class="w-full bg-slate-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-full rounded-full transition-all" 
                             style="width: ${(data.remaining_quota / data.total_quota) * 100}%"></div>
                    </div>
                    <p class="text-gray-300 text-xs mt-2">Quota used: ${data.quota_used_today}</p>
                </div>

                <p class="text-green-300 text-lg font-semibold">${data.notification.message}</p>
            </div>
        `;

        resultArea.classList.remove('hidden');
        document.getElementById('qr-input').value = '';
        document.getElementById('program-input').value = '';
        setTimeout(() => document.getElementById('qr-input').focus(), 500);
    }

    function showErrorResult(message) {
        const resultArea = document.getElementById('result-area');
        const resultMsg = document.getElementById('result-message');

        resultMsg.className = 'bg-gradient-to-r from-red-900/30 to-red-900/20 border-2 border-red-600 rounded-xl p-8';
        resultMsg.innerHTML = `
            <div class="text-center">
                <div class="text-6xl mb-4">⚠️</div>
                <h3 class="text-3xl font-bold text-red-400 mb-2">CHECK-IN GAGAL</h3>
                <p class="text-red-300 text-lg bg-red-900/40 rounded-lg p-4 inline-block">${message}</p>
            </div>
        `;

        resultArea.classList.remove('hidden');
        document.getElementById('qr-input').value = '';
        setTimeout(() => document.getElementById('qr-input').focus(), 500);
    }

    function resetScan() {
        document.getElementById('result-area').classList.add('hidden');
        document.getElementById('qr-input').value = '';
        document.getElementById('program-input').value = '';
        document.getElementById('qr-input').focus();
    }

    function updateStats() {
        document.getElementById('success-count').textContent = successCount;
        document.getElementById('error-count').textContent = errorCount;
        document.getElementById('today-count').textContent = successCount + errorCount;
    }

    function logScan(data, status) {
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const statusBg = status === 'success' ? 'bg-green-900/30 border-green-700' : 'bg-red-900/30 border-red-700';
        const statusText = status === 'success' ? 'text-green-400' : 'text-red-400';
        const icon = status === 'success' ? 'ri-check-circle-fill' : 'ri-close-circle-fill';
        
        const logEntry = document.createElement('div');
        logEntry.className = `${statusBg} border rounded-lg p-3 flex items-start gap-3`;
        logEntry.innerHTML = `
            <i class="ri-${icon} ${statusText} flex-shrink-0 mt-0.5 text-lg"></i>
            <div class="flex-1 text-left">
                <p class="${statusText} font-bold text-sm">${data.member_name ? '✓ ' + data.member_name : '✗ ' + (data.message || 'Error')}</p>
                <p class="text-gray-400 text-xs">${time}</p>
                ${status === 'success' ? `<p class="text-gray-300 text-xs mt-1">Quota: ${data.remaining_quota}/${data.total_quota}</p>` : ''}
            </div>
        `;

        const logsContainer = document.getElementById('scans-log');
        
        // Remove "no data" message if exists
        if (logsContainer.children.length === 1 && logsContainer.children[0].textContent.includes('akan muncul')) {
            logsContainer.innerHTML = '';
        }

        logsContainer.insertBefore(logEntry, logsContainer.firstChild);

        // Keep only last 20 entries
        while (logsContainer.children.length > 20) {
            logsContainer.removeChild(logsContainer.lastChild);
        }
    }

    function showAlert(message, type = 'info') {
        const bgClass = type === 'error' ? 'bg-red-600' : type === 'success' ? 'bg-green-600' : 'bg-blue-600';
        const alert = document.createElement('div');
        alert.className = `${bgClass} text-white px-6 py-3 rounded-lg fixed top-4 right-4 z-50 shadow-lg font-semibold`;
        alert.textContent = message;
        document.body.appendChild(alert);

        setTimeout(() => alert.remove(), 3000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('qr-input').focus();
    });
</script>

<style>
    @media (max-width: 768px) {
        .grid.lg\:grid-cols-3 {
            grid-template-columns: 1fr;
        }
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
@endsection
