@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- HEADER -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">
                <i class="ri-qr-scan-2-line mr-3 text-red-600"></i>Member Check-in Scanner
            </h1>
            <p class="text-gray-400">Scan QR code member untuk check-in otomatis</p>
        </div>

        <!-- SCANNER CARD -->
        <div class="bg-gray-800 rounded-xl p-8 border border-gray-700 mb-6">
            
            <!-- Info Box -->
            <div class="bg-blue-900/30 border border-blue-700 rounded-lg p-4 mb-6">
                <p class="text-blue-200 text-sm flex gap-2">
                    <i class="ri-information-line flex-shrink-0 mt-0.5"></i>
                    <span>Gunakan scanner barcode atau kamera untuk scan QR code member. Quota akan otomatis berkurang saat check-in berhasil.</span>
                </p>
            </div>

            <!-- Camera Preview (Optional) -->
            <div id="camera-container" class="mb-6 hidden">
                <video id="camera-preview" class="w-full rounded-lg bg-black" autoplay playsinline></video>
                <button onclick="stopCamera()" class="mt-3 w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition">
                    <i class="ri-camera-off-line mr-2"></i>Stop Camera
                </button>
            </div>

            <!-- Manual Input / Barcode Scanner -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Scan QR Code atau Input Token</label>
                    <input type="text" 
                           id="qr-input" 
                           placeholder="Paste QR token hasil scan di sini..."
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600"
                           autofocus>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Program (Opsional)</label>
                    <input type="text" 
                           id="program-input" 
                           placeholder="Misal: Boxing, Yoga, etc"
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600">
                </div>

                <button onclick="submitScan()" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                    <i class="ri-check-line"></i> Process Check-in
                </button>
            </div>
        </div>

        <!-- RESULT AREA -->
        <div id="result-area" class="hidden mb-6">
            <div id="result-message" class="rounded-xl p-6 border"></div>
            <button onclick="resetScan()" class="mt-4 w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold py-2 rounded-lg transition">
                Scan QR Code Berikutnya
            </button>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-400 text-xs uppercase mb-1">Hari Ini</p>
                <p id="today-count" class="text-2xl font-bold text-white">0</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-400 text-xs uppercase mb-1">Suksess</p>
                <p id="success-count" class="text-2xl font-bold text-green-400">0</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                <p class="text-gray-400 text-xs uppercase mb-1">Error</p>
                <p id="error-count" class="text-2xl font-bold text-red-400">0</p>
            </div>
        </div>

        <!-- RECENT SCANS LOG -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <h3 class="text-lg font-bold text-white mb-4">
                <i class="ri-history-line mr-2"></i>Recent Check-ins
            </h3>
            <div id="scans-log" class="space-y-2 max-h-96 overflow-y-auto">
                <p class="text-gray-400 text-sm">Hasil scan akan muncul di sini</p>
            </div>
        </div>
    </div>
</div>

<script>
    let successCount = 0;
    let errorCount = 0;
    const scansLog = [];

    // Auto submit jika ada scan dari barcode scanner
    document.getElementById('qr-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitScan();
        }
    });

    async function submitScan() {
        const qrToken = document.getElementById('qr-input').value.trim();
        const program = document.getElementById('program-input').value.trim();

        if (!qrToken) {
            alertMessage('QR Token tidak boleh kosong', 'error');
            return;
        }

        try {
            // Disable button
            const btn = event ? event.target.closest('button') : null;
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i>Processing...';
            }

            const response = await fetch('/api/member/scan-qr', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    qr_token: qrToken,
                    program: program,
                    location: 'Gym Entrance'
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

        } catch (error) {
            errorCount++;
            const errorMsg = error.message || 'Terjadi kesalahan saat processing scan';
            showErrorResult(errorMsg);
            logScan({message: errorMsg}, 'error');
            updateStats();
        }
    }

    function showSuccessResult(data) {
        const resultArea = document.getElementById('result-area');
        const resultMsg = document.getElementById('result-message');

        resultMsg.className = 'bg-green-900/30 border border-green-700 rounded-xl p-6';
        resultMsg.innerHTML = `
            <div class="text-center">
                <i class="ri-check-circle-line text-6xl text-green-400 mb-4"></i>
                <h3 class="text-2xl font-bold text-white mb-2">✅ Check-in Berhasil!</h3>
                
                <div class="bg-green-900/20 rounded-lg p-4 my-4 space-y-2 text-left">
                    <p class="text-gray-300"><strong>Member:</strong> ${data.member_name} (${data.member_id})</p>
                    <p class="text-gray-300"><strong>Program:</strong> ${data.program}</p>
                    <p class="text-gray-300"><strong>Jam Masuk:</strong> ${data.check_in_time} (${data.check_in_date})</p>
                    <p class="text-gray-300"><strong>Package:</strong> ${data.package_name}</p>
                    
                    <div class="pt-3 border-t border-green-700">
                        <p class="text-green-400 font-bold">📊 Quota Tersisa: <span class="text-2xl">${data.remaining_quota}/${data.total_quota}</span></p>
                        <div class="mt-2 w-full bg-green-900/50 rounded-full h-2">
                            <div class="bg-green-500 h-full rounded-full" style="width: ${(data.remaining_quota / data.total_quota) * 100}%"></div>
                        </div>
                    </div>
                </div>

                <p class="text-green-300 text-lg">${data.notification.message}</p>
            </div>
        `;

        resultArea.classList.remove('hidden');
        document.getElementById('qr-input').value = '';
        document.getElementById('program-input').value = '';
        document.getElementById('qr-input').focus();
    }

    function showErrorResult(message) {
        const resultArea = document.getElementById('result-area');
        const resultMsg = document.getElementById('result-message');

        resultMsg.className = 'bg-red-900/30 border border-red-700 rounded-xl p-6';
        resultMsg.innerHTML = `
            <div class="text-center">
                <i class="ri-close-circle-line text-6xl text-red-400 mb-4"></i>
                <h3 class="text-2xl font-bold text-white mb-2">⚠️ Check-in Gagal</h3>
                <p class="text-red-300 text-lg">${message}</p>
            </div>
        `;

        resultArea.classList.remove('hidden');
        document.getElementById('qr-input').value = '';
        document.getElementById('qr-input').focus();
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
        const time = now.toLocaleTimeString('id-ID');
        const statusClass = status === 'success' ? 'text-green-400' : 'text-red-400';
        const statusIcon = status === 'success' ? 'ri-check-circle-line' : 'ri-close-circle-line';
        
        const logEntry = document.createElement('div');
        logEntry.className = `border-l-4 ${status === 'success' ? 'border-green-600' : 'border-red-600'} bg-gray-700/50 p-3 rounded text-sm`;
        logEntry.innerHTML = `
            <div class="flex items-start gap-2">
                <i class="ri-check-circle-line ${statusClass} flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <p class="${statusClass} font-semibold">${data.member_name || data.message || 'Scan Processed'}</p>
                    <p class="text-gray-400 text-xs">${time}</p>
                    ${status === 'success' ? `<p class="text-gray-300 text-xs mt-1">Quota: ${data.remaining_quota}/${data.total_quota}</p>` : ''}
                </div>
            </div>
        `;

        const logsContainer = document.getElementById('scans-log');
        logsContainer.insertBefore(logEntry, logsContainer.firstChild);

        // Keep only last 20 entries
        if (logsContainer.children.length > 20) {
            logsContainer.removeChild(logsContainer.lastChild);
        }
    }

    function alertMessage(message, type = 'info') {
        const bgClass = type === 'error' ? 'bg-red-600' : 'bg-blue-600';
        const alert = document.createElement('div');
        alert.className = `${bgClass} text-white px-6 py-4 rounded-lg fixed top-4 right-4 z-50 shadow-lg`;
        alert.textContent = message;
        document.body.appendChild(alert);

        setTimeout(() => alert.remove(), 3000);
    }

    function stopCamera() {
        const video = document.getElementById('camera-preview');
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }
        document.getElementById('camera-container').classList.add('hidden');
    }

    // Auto-focus input on load
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('qr-input').focus();
    });
</script>
@endsection
