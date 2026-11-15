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
    $hasExclusiveScheduleData = !$isExclusiveClass || $showClassDropdown;
@endphp

@include('checkout.partials.checkout-style')

{{-- Modal Konfirmasi - Hanya muncul ketika user klik X di Midtrans --}}
<div id="confirmationModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-icon">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.5m0 3h.01M10.27 4.5l-7.43 12.86A2 2 0 0 0 4.57 20.5h14.86a2 2 0 0 0 1.73-3.14L13.73 4.5a2 2 0 0 0-3.46 0z"/>
            </svg>
        </div>
        <h3>Batalkan Pembayaran?</h3>
        <p>Anda belum menyelesaikan pembayaran. Apakah Anda ingin melanjutkan atau membatalkan?</p>
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

<div class="ftm-checkout-page flex justify-center items-start py-8 sm:py-12 px-4">
    <div class="max-w-md w-full">
        <div class="ftm-checkout-card">
            <h1 class="ftm-checkout-title">Checkout</h1>

            <form id="checkoutForm" method="POST">
                @csrf
                <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                <input type="hidden" id="hiddenClassId" name="class_id" value="">

                <div class="space-y-4">

                    {{-- ── Section: Program dan Kelas ── --}}
                    <div>
                        <h2 class="ftm-section-title mb-3">Program dan Kelas</h2>

                        @if($showClassDropdown)
                            <div class="flex flex-col gap-3">
                                <div>
                                    <label class="ftm-field-label">Program</label>
                                    <div class="ftm-field-static">
                                        <span class="truncate">{{ $pkg->name }}</span>
                                        <span class="quota">({{ $pkg->quota }}x)</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="ftm-field-label">Pilih Kelas</label>
                                    <select id="classOption" class="ftm-select" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classOptions as $classId => $opt)
                                            <option value="{{ $classId }}">{{ $opt['label'] }}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                        @elseif($isExclusiveClass)
                            <div>
                                <label class="ftm-field-label">Program</label>
                                <div class="ftm-field-static">
                                    <span class="truncate">{{ $pkg->name }}</span>
                                    <span class="quota">({{ $pkg->quota }}x)</span>
                                </div>
                            </div>

                            <div class="ftm-info-box mt-3">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#EE4E8B" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                    </svg>
                                    <div>
                                        <h4>Jadwal belum tersedia</h4>
                                        <p>Package ini belum memiliki jadwal aktif di admin Schedule. Silakan hubungi admin FTM agar jadwal untuk <strong style="color:#7A2B4A;">{{ $pkg->name }}</strong> diaktifkan sebelum checkout.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="ftm-field-label">Program</label>
                                <div class="ftm-field-static">
                                    <span class="truncate">{{ $pkg->name }}</span>
                                    <span class="quota">({{ $pkg->quota }}x)</span>
                                </div>
                            </div>

                            <div class="ftm-info-box mt-3">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#EE4E8B" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                    </svg>
                                    <div>
                                        <h4>Cara Booking</h4>
                                        <p>Setelah pembayaran berhasil, Anda dapat memilih jadwal kelas di halaman <strong style="color:#7A2B4A;">Booking</strong>.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="classSchedules"></div>
                    </div>

                    <hr class="ftm-divider">

                    {{-- ── Section: Ringkasan Pembayaran ── --}}
                    <div>
                        <h2 class="ftm-section-title mb-3">Ringkasan Pembayaran</h2>
                        <div class="ftm-summary">
                            <div class="ftm-summary-row">
                                <span>Harga Program (IDR)</span>
                                <span id="priceValue" class="val">{{ rp($pkg->price) }}</span>
                            </div>
                            <div class="ftm-summary-row">
                                <span>Diskon Voucher</span>
                                <span id="voucherValue" class="val" style="color:#EE4E8B;">Rp0</span>
                            </div>
                            <div class="ftm-summary-divider"></div>
                            <div class="ftm-summary-total">
                                <span class="label">Amount to Pay</span>
                                <span id="totalValue" class="val">{{ rp($pkg->price) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Section: Kode Voucher ── --}}
                    <div>
                        <h2 class="ftm-section-title mb-2">Kode Voucher</h2>
                        <div class="ftm-voucher-row">
                            <input id="voucher" name="voucher_code" type="text" placeholder="Masukkan kode voucher" class="ftm-input" autocomplete="off">
                            <button id="applyVoucher" type="button" class="ftm-btn-apply">Apply</button>
                        </div>
                        <div id="voucherMessage"></div>
                    </div>

                    {{-- ── Tombol Bayar ── --}}
                    <button type="button" id="pay-button" class="ftm-btn-pay mt-1" @unless($hasExclusiveScheduleData) disabled @endunless>
                        <span>{{ $hasExclusiveScheduleData ? 'Bayar Sekarang' : 'Jadwal Belum Tersedia' }}</span>
                    </button>

                    <div id="statusText">{{ $hasExclusiveScheduleData ? '' : 'Pembayaran dibuka kembali setelah admin menambahkan jadwal untuk program ini.' }}</div>
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
        const num = parseInt(amount) || 0;
        return 'Rp' + num.toLocaleString('id-ID');
    }

    function updatePaymentSummary() {
        const total = Math.max(0, CONFIG.originalPrice - STATE.discount);
        DOM.voucherValueEl.textContent = STATE.discount > 0 ? '- ' + formatRupiah(STATE.discount) : 'Rp0';
        DOM.priceValueEl.textContent = formatRupiah(CONFIG.originalPrice);
        DOM.totalValueEl.textContent = formatRupiah(total);
    }

    function showMessage(element, message, type = 'error') {
        const colors = {
            error:   '#7A2B4A',
            success: '#1A7A5E',
            info:    '#EE4E8B',
            warning: '#1D5A4B'
        };
        element.textContent = message;
        element.style.color = colors[type] || colors.error;
        element.style.marginTop = '0.4rem';
    }

    function setButtonState(button, disabled, text) {
        button.disabled = disabled;
        if (text) button.innerHTML = text;
    }

    function updateStatusText(message, type = 'info') {
        const colors = {
            info:    '#EE4E8B',
            success: '#1A7A5E',
            error:   '#7A2B4A',
            warning: '#1D5A4B',
            default: 'rgba(28, 28, 28, 0.7)'
        };
        DOM.statusText.textContent = message;
        DOM.statusText.style.color = colors[type] || colors.default;
        DOM.statusText.style.fontWeight = type === 'default' ? '500' : '600';
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
            body: JSON.stringify({ 
                code,
                package_id: parseInt(CONFIG.packageId)
            })
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

            // ✅ Data structure is now flat
            const voucherValue = parseFloat(data.value) || 0;
            const maxDiscount = data.max_discount ? parseFloat(data.max_discount) : Infinity;
            
            if (data.type === "percent") {
                STATE.discount = Math.min(
                    Math.floor(CONFIG.originalPrice * voucherValue / 100),
                    maxDiscount
                );
            } else {
                // nominal type
                STATE.discount = maxDiscount !== Infinity
                    ? Math.min(voucherValue, maxDiscount)
                    : voucherValue;
            }
            
            STATE.discount = Math.max(0, parseInt(STATE.discount) || 0);

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

            const selectedClass = classOptions[key];
            const schedules = selectedClass.schedules || [];
            DOM.scheduleContainer.innerHTML = `
                <div class="ftm-schedule-box">
                    <div class="ftm-schedule-head">
                        <div>
                            <h4>${escapeHtml(selectedClass.label)}</h4>
                            
                        </div>
                        <span class="ftm-schedule-badge">${schedules.length} Jadwal</span>
                    </div>
                    <ul>
                        ${schedules.map(s => {
                            const parts = splitScheduleText(s);
                            return `
                            <li>
                                <span class="check">✓</span>
                                <span><strong>${escapeHtml(parts.main)}</strong>${parts.detail ? escapeHtml(parts.detail) : ''}</span>
                            </li>
                        `}).join('')}
                    </ul>
                </div>
            `;
        });
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>'"]/g, function(char) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[char];
        });
    }

    function splitScheduleText(value) {
        const text = String(value ?? '');
        const separator = text.includes(' - ') ? ' - ' : (text.includes(' – ') ? ' – ' : null);

        if (!separator) {
            return { main: text, detail: '' };
        }

        const [main, ...detail] = text.split(separator);
        return { main: main.trim(), detail: ' - ' + detail.join(separator).trim() };
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
