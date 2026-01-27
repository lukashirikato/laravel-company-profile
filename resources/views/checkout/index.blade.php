@extends('layouts.app')

@section('content')

@php
    if (is_object($package)) {
        $pkg = $package;
    } elseif (is_array($package)) {
        $pkg = (object) $package;
    } else {
        $pkg = (object) [
            'id'    => null,
            'name'  => $package ?? 'Tidak ada program',
            'price' => 0,
            'quota' => 0,
        ];
    }

    $user = auth('customer')->user();

    if (!function_exists('rp')) {
        function rp($n) {
            return 'Rp' . number_format((int) $n, 0, ',', '.');
        }
    }
    
    $isExclusiveClass = (bool) ($pkg->is_exclusive ?? false);
    $showClassDropdown = $isExclusiveClass && isset($classOptions) && !empty($classOptions);
@endphp

<style>
    .btn-purple {
        background: linear-gradient(90deg, #c08484, #d19a9a);
        transition: all 0.25s ease;
    }

    .btn-purple:hover:not(:disabled) {
        background: linear-gradient(90deg, #b76e6e, #c08484);
        transform: scale(1.03);
        box-shadow: 0 12px 28px rgba(183, 110, 110, 0.35);
    }

    .btn-purple:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Modal Konfirmasi */
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease;
    }

    .custom-modal.active {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        max-width: 420px;
        width: 90%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { 
            transform: translateY(30px);
            opacity: 0;
        }
        to { 
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-buttons {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .modal-btn {
        flex: 1;
        padding: 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.95rem;
    }

    .modal-btn-cancel {
        background: #ef4444;
        color: white;
    }

    .modal-btn-cancel:hover {
        background: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .modal-btn-continue {
        background: linear-gradient(90deg, #c08484, #d19a9a);
        color: white;
    }

    .modal-btn-continue:hover {
        background: linear-gradient(90deg, #b76e6e, #c08484);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(192, 132, 132, 0.3);
    }
</style>

{{-- Modal Konfirmasi - Hanya muncul ketika user klik X di Midtrans --}}
<div id="confirmationModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-icon">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Batalkan Pembayaran?</h3>
        <p class="text-gray-600 text-center text-sm mb-4">
            Anda belum menyelesaikan pembayaran. Apakah Anda ingin melanjutkan atau membatalkan?
        </p>
        <div class="modal-buttons">
            <button id="modalCancelBtn" class="modal-btn modal-btn-cancel">
                Batalkan
            </button>
            <button id="modalContinueBtn" class="modal-btn modal-btn-continue">
                Lanjutkan Bayar
            </button>
        </div>
    </div>
</div>

<div class="bg-white flex justify-center items-start pt-12 pb-12">
    <div class="max-w-lg w-full px-6">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-50 p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Checkout</h1>

            <form id="checkoutForm" method="POST">
                @csrf
                <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                <input type="hidden" id="hiddenClassId" name="class_id" value="">

                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800">Program dan Kelas</h2>
                    
                    @if($showClassDropdown)
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1">
                                <label class="text-sm text-gray-500 mb-1 block">Program</label>
                                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-xl font-medium text-gray-900 border border-gray-200">
                                    <span class="truncate">{{ $pkg->name }}</span>
                                    <span class="text-sm text-gray-500 ml-2">({{ $pkg->quota }}x)</span>
                                </div>
                            </div>

                            <div class="flex-1">
                                <label class="text-sm text-gray-500 mb-1 block">Pilih Kelas</label>
                                <select id="classOption" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-violet-200 focus:border-violet-400" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classOptions as $classId => $opt)
                                        <option value="{{ $classId }}">{{ $opt['label'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="text-sm text-gray-500 mb-1 block">Program</label>
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-xl font-medium text-gray-900 border border-gray-200">
                                <span class="truncate">{{ $pkg->name }}</span>
                                <span class="text-sm text-gray-500 ml-2">({{ $pkg->quota }}x)</span>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-blue-900 text-sm">Cara Booking</h4>
                                    <p class="text-sm text-blue-700 mt-1">Setelah pembayaran berhasil, Anda dapat memilih jadwal kelas di halaman <strong>Booking</strong>.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div id="classSchedules"></div>

                    <hr class="border-gray-100">

                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-800">Ringkasan Pembayaran</h2>
                        <div class="bg-gray-50 rounded-xl p-4 space-y-2 border border-gray-200">
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>Harga Program (IDR)</span>
                                <span id="priceValue" class="font-medium">{{ rp($pkg->price) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>Diskon Voucher</span>
                                <span id="voucherValue" class="font-medium text-violet-600">Rp0</span>
                            </div>
                            <hr class="border-gray-300 my-2">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900">Amount to Pay</span>
                                <span id="totalValue" class="text-2xl font-bold text-gray-900">{{ rp($pkg->price) }}</span>
                            </div>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-md font-semibold text-gray-800 mb-3">Kode Voucher</h3>
                            <div class="flex gap-3">
                                <input id="voucher" name="voucher_code" type="text" placeholder="Masukkan kode voucher"
                                       class="flex-1 rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-violet-200 focus:border-violet-400">
                                <button id="applyVoucher" type="button" class="h-11 px-6 rounded-lg text-white font-semibold btn-purple shadow-md shadow-violet-500/20 text-sm">
                                    Apply
                                </button>
                            </div>
                            <div id="voucherMessage"></div>
                        </div>
                    </div>

                    <button id="pay-button" type="button" class="w-full mt-6 py-4 rounded-xl text-white font-semibold btn-purple shadow-lg shadow-violet-500/40 text-lg">
                        <span>Bayar Sekarang</span>
                    </button>

                    <div id="statusText" class="text-sm text-center mt-3 text-gray-600"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
// ============================================
// 🔥 CHECKOUT SCRIPT - DENGAN MODAL KONFIRMASI
// ============================================
window.__PAYMENT_DONE__ = false;

(function() {
    'use strict';

    // ==================== KONFIGURASI ====================
    const CONFIG = {
        originalPrice: {{ (int) $pkg->price }},
        showClassDropdown: {{ $showClassDropdown ? 'true' : 'false' }},
        packageId: "{{ $pkg->id }}",
        csrfToken: "{{ csrf_token() }}",
        urls: {
            voucherCheck: "{{ url('/voucher/check') }}",
            checkoutProcess: "{{ url('/checkout/process') }}",
            successPage: "{{ route('payment.success', '') }}"
        }
    };

    // ==================== DOM ELEMENTS ====================
    const DOM = {
        applyBtn: document.getElementById("applyVoucher"),
        voucherInput: document.getElementById("voucher"),
        voucherValueEl: document.getElementById("voucherValue"),
        totalValueEl: document.getElementById("totalValue"),
        priceValueEl: document.getElementById("priceValue"),
        payBtn: document.getElementById("pay-button"),
        statusText: document.getElementById("statusText"),
        voucherMessage: document.getElementById("voucherMessage"),
        scheduleContainer: document.getElementById("classSchedules"),
        hiddenClassId: document.getElementById("hiddenClassId"),
        selectClass: CONFIG.showClassDropdown ? document.getElementById("classOption") : null,
        confirmModal: document.getElementById("confirmationModal"),
        modalCancelBtn: document.getElementById("modalCancelBtn"),
        modalContinueBtn: document.getElementById("modalContinueBtn")
    };

    // ==================== STATE MANAGEMENT ====================
    const STATE = {
        discount: 0,
        paymentCompleted: false,
        paymentMethodSelected: false,  // Track apakah user sudah pilih payment method
        currentSnapToken: null,
        currentOrderId: null
    };

    const classOptions = CONFIG.showClassDropdown ? @json($classOptions ?? []) : {};

    // ==================== UTILITY FUNCTIONS ====================
    function formatRupiah(amount) {
        return 'Rp' + amount.toLocaleString('id-ID');
    }

    function updatePaymentSummary() {
        const total = Math.max(0, CONFIG.originalPrice - STATE.discount);
        DOM.voucherValueEl.textContent = STATE.discount > 0 ? '- ' + formatRupiah(STATE.discount) : 'Rp0';
        DOM.priceValueEl.textContent = formatRupiah(CONFIG.originalPrice);
        DOM.totalValueEl.textContent = formatRupiah(total);
    }

    function showMessage(element, message, type = 'error') {
        const colors = {
            error: 'text-red-600',
            success: 'text-green-600',
            info: 'text-blue-600',
            warning: 'text-yellow-600'
        };
        element.textContent = message;
        element.className = `mt-2 text-sm ${colors[type]}`;
    }

    function setButtonState(button, disabled, text) {
        button.disabled = disabled;
        if (text) button.innerHTML = text;
    }

    function updateStatusText(message, type = 'info') {
        const colors = {
            info: 'text-blue-600',
            success: 'text-green-600',
            error: 'text-red-600',
            warning: 'text-yellow-600',
            default: 'text-gray-600'
        };
        DOM.statusText.textContent = message;
        DOM.statusText.className = `text-sm text-center mt-3 ${colors[type]} font-semibold`;
    }

    // ==================== MODAL FUNCTIONS ====================
    function showConfirmationModal() {
        DOM.confirmModal.classList.add('active');
    }

    function hideConfirmationModal() {
        DOM.confirmModal.classList.remove('active');
    }

    function reopenMidtransPayment() {
        if (STATE.currentSnapToken) {
            console.log('🔄 Membuka kembali pembayaran');
            updateStatusText("Membuka kembali pembayaran...", 'info');
            snap.pay(STATE.currentSnapToken, handleMidtransCallbacks(STATE.currentOrderId));
        }
    }

    // ==================== VOUCHER HANDLING ====================
    DOM.applyBtn.addEventListener("click", () => {
        const code = DOM.voucherInput.value.trim();

        if (!code) {
            showMessage(DOM.voucherMessage, "Masukkan kode voucher", 'error');
            return;
        }

        setButtonState(DOM.applyBtn, true, "Checking...");

        fetch(CONFIG.urls.voucherCheck, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": CONFIG.csrfToken
            },
            body: JSON.stringify({ code })
        })
        .then(response => response.json())
        .then(data => {
            setButtonState(DOM.applyBtn, false, "Apply");

            if (!data.valid) {
                STATE.discount = 0;
                showMessage(DOM.voucherMessage, data.message, 'error');
                updatePaymentSummary();
                return;
            }

            STATE.discount = data.type === "percent"
                ? Math.min(Math.floor(CONFIG.originalPrice * data.value / 100), data.max_discount ?? Infinity)
                : data.value;

            showMessage(DOM.voucherMessage, "Voucher berhasil digunakan ✓", 'success');
            updatePaymentSummary();
        })
        .catch(error => {
            console.error("Voucher check error:", error);
            setButtonState(DOM.applyBtn, false, "Apply");
            showMessage(DOM.voucherMessage, "Gagal memeriksa voucher", 'error');
        });
    });

    // ==================== CLASS SELECTION ====================
    if (CONFIG.showClassDropdown && DOM.selectClass) {
        DOM.selectClass.addEventListener("change", function() {
            const key = this.value;
            DOM.hiddenClassId.value = key;
            
            if (!key || !classOptions[key]) {
                DOM.scheduleContainer.innerHTML = '';
                return;
            }

            const schedules = classOptions[key].schedules;
            DOM.scheduleContainer.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-3">
                    <h4 class="font-semibold text-green-900 mb-2">Jadwal Kelas Anda</h4>
                    <ul class="text-sm space-y-1">
                        ${schedules.map(s => `
                            <li class="flex items-center gap-2 text-green-800">
                                <span class="text-green-600 font-bold">✓</span>
                                <span>${s}</span>
                            </li>
                        `).join('')}
                    </ul>
                </div>
            `;
        });
    }

    // ==================== PAYMENT HANDLING ====================
    function validatePayment() {
        if (CONFIG.showClassDropdown && DOM.selectClass) {
            const selectedClass = DOM.selectClass.value;
            
            if (!selectedClass) {
                alert("Silakan pilih kelas terlebih dahulu");
                return false;
            }

            DOM.hiddenClassId.value = selectedClass;
        }
        return true;
    }

    function createFormData() {
        const formData = new FormData();
        formData.append("_token", CONFIG.csrfToken);
        formData.append("package_id", CONFIG.packageId);
        
        if (CONFIG.showClassDropdown && DOM.selectClass && DOM.selectClass.value) {
            formData.append("class_id", DOM.selectClass.value);
        }
        
        formData.append("voucher_code", DOM.voucherInput.value || '');
        
        return formData;
    }

    function handleMidtransCallbacks(orderId) {
        return {
            onSuccess: function(result) {
                console.log('✅ Pembayaran BERHASIL:', result);

                // 🔥 TANDAI PAYMENT SELESAI
                window.__PAYMENT_DONE__ = true;
                STATE.paymentCompleted = true;

                updateStatusText("Pembayaran berhasil! Mengalihkan...", 'success');

                // 🔥 HAPUS EVENT LISTENER SEBELUM REDIRECT (PENTING!)
                window.removeEventListener('beforeunload', handleBeforeUnload);

                // 🚀 LANGSUNG REDIRECT KE SUCCESS PAGE
                setTimeout(() => {
                    window.location.replace(`${CONFIG.urls.successPage}/${orderId}`);
                }, 500);
            },
            
            onPending: function(result) {
                console.log('⏳ Pembayaran PENDING:', result);
                
                // User sudah pilih payment method
                STATE.paymentMethodSelected = true;
                
                updateStatusText("Menunggu pembayaran diselesaikan", 'warning');
                setButtonState(DOM.payBtn, false);
            },
            
            onError: function(result) {
                console.error('❌ Pembayaran GAGAL:', result);
                
                STATE.paymentMethodSelected = false;
                
                updateStatusText("Pembayaran gagal", 'error');
                setButtonState(DOM.payBtn, false);
                alert("Pembayaran gagal. Silakan coba lagi.");
            },
            
            onClose: function() {
                console.log('🚪 Popup Midtrans DITUTUP');
                
                // ✅ Jika payment sudah selesai, lewat saja
                if (STATE.paymentCompleted) {
                    console.log('Payment completed, allowing close');
                    return;
                }
                
                // ✅ Jika user sudah pilih payment method (pending), lewat saja
                if (STATE.paymentMethodSelected) {
                    console.log('Payment method selected, allowing close');
                    updateStatusText("Silakan selesaikan pembayaran Anda", 'warning');
                    setButtonState(DOM.payBtn, false);
                    return;
                }
                
                // 🔥 HANYA TAMPILKAN MODAL JIKA USER BELUM PILIH PAYMENT METHOD
                // (User klik X sebelum pilih payment method)
                console.log('User closed without selecting payment method - showing modal');
                showConfirmationModal();
            }
        };
    }

    function processPayment() {
        if (!validatePayment()) {
            return;
        }

        const formData = createFormData();
        
        // Reset states
        STATE.paymentCompleted = false;
        STATE.paymentMethodSelected = false;
        
        setButtonState(DOM.payBtn, true);
        updateStatusText("Memproses pembayaran...", 'info');

        fetch(CONFIG.urls.checkoutProcess, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (!data.snapToken) {
                throw new Error("Snap token tidak diterima");
            }
            
            console.log("✅ Membuka Midtrans Snap");
            
            // Simpan token untuk reopen
            STATE.currentSnapToken = data.snapToken;
            STATE.currentOrderId = data.order_id;
            
            // Buka Midtrans
            snap.pay(data.snapToken, handleMidtransCallbacks(data.order_id));
        })
        .catch(error => {
            console.error("Payment error:", error);
            setButtonState(DOM.payBtn, false);
            updateStatusText("Gagal memproses pembayaran", 'error');
            alert("Gagal memproses pembayaran. Silakan coba lagi.");
        });
    }

    // ==================== PREVENT PAGE LEAVE (TIDAK ADA WARNING) ====================
    function handleBeforeUnload(event) {
        // ✅ Jika payment sudah selesai, lewat saja (TIDAK ADA POPUP)
        if (window.__PAYMENT_DONE__ === true) {
            return undefined;
        }

        // ✅ Tidak ada warning sama sekali - langsung lewat
        return undefined;
    }

    // ==================== MODAL EVENT LISTENERS ====================
    DOM.modalCancelBtn.addEventListener("click", function() {
        console.log('❌ User membatalkan pembayaran');
        
        STATE.paymentMethodSelected = false;
        STATE.currentSnapToken = null;
        STATE.currentOrderId = null;
        
        updateStatusText("Pembayaran dibatalkan", 'default');
        setButtonState(DOM.payBtn, false);
        hideConfirmationModal();
    });

    DOM.modalContinueBtn.addEventListener("click", function() {
        console.log('✅ User melanjutkan pembayaran');
        hideConfirmationModal();
        reopenMidtransPayment();
    });

    // Tutup modal jika klik backdrop
    DOM.confirmModal.addEventListener("click", function(e) {
        if (e.target === DOM.confirmModal) {
            hideConfirmationModal();
            reopenMidtransPayment();
        }
    });

    // ==================== INITIALIZATION ====================
    DOM.payBtn.addEventListener("click", processPayment);
    
    // Attach beforeunload (tapi tidak akan trigger warning)
    window.addEventListener('beforeunload', handleBeforeUnload);
    
    updatePaymentSummary();
    
    console.log("✅ Checkout berhasil diinisialisasi");

})();
</script>

@endsection