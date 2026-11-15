@extends('layouts.app')

@section('content')

@php
    $price = (int) $package->price;
    $quota = (int) ($package->quota ?? 0);
    $isExclusive = (bool) $package->is_exclusive;
    $classOptions = $classOptions ?? [];
    $hasClassOptions = $isExclusive && ! empty($classOptions);

    if (!function_exists('rp')) {
        function rp($n) {
            return 'Rp' . number_format((int) $n, 0, ',', '.');
        }
    }
@endphp

@include('checkout.partials.checkout-style')

<div class="ftm-checkout-page flex justify-center items-start py-6 sm:py-12 px-4">
    <div class="max-w-md w-full">
        <div class="ftm-checkout-card">
            <h1 class="ftm-checkout-title">Checkout</h1>

            <form id="guestCheckoutForm" method="POST" novalidate>
                @csrf
                <input type="hidden" name="package_id" value="{{ $package->id }}">

                <div class="space-y-4">

                    {{-- Section: Program dan Kelas --}}
                    <div>
                        <h2 class="ftm-section-title mb-3">Program dan Kelas</h2>

                        @if($hasClassOptions)
                            <div class="flex flex-col gap-3">
                                <div>
                                    <label class="ftm-field-label">Program</label>
                                    <div class="ftm-field-static">
                                        <span class="truncate">{{ $package->name }}</span>
                                        <span class="quota">({{ $quota }}x)</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="ftm-field-label" for="classOption">Pilih Kelas</label>
                                    <select id="classOption" name="class_id" class="ftm-select" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classOptions as $classId => $opt)
                                            <option value="{{ $classId }}">{{ $opt['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @elseif($isExclusive)
                            <div>
                                <label class="ftm-field-label">Program</label>
                                <div class="ftm-field-static">
                                    <span class="truncate">{{ $package->name }}</span>
                                    <span class="quota">({{ $quota }}x)</span>
                                </div>
                            </div>

                            <div class="ftm-info-box mt-3">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#EE4E8B" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                    </svg>
                                    <div>
                                        <h4>Jadwal belum tersedia</h4>
                                        <p>Package ini belum memiliki jadwal aktif. Hubungi admin FTM agar jadwal untuk <strong style="color:#7A2B4A;">{{ $package->name }}</strong> diaktifkan sebelum checkout.</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="ftm-field-label">Program</label>
                                <div class="ftm-field-static">
                                    <span class="truncate">{{ $package->name }}</span>
                                    <span class="quota">({{ $quota }}x)</span>
                                </div>
                            </div>

                            <div class="ftm-info-box mt-3">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#EE4E8B" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                    </svg>
                                    <div>
                                        <h4>Cara Booking</h4>
                                        <p>Setelah pembayaran berhasil dan akun diaktifkan, Anda dapat memilih jadwal kelas di halaman <strong style="color:#7A2B4A;">Booking</strong>.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="classSchedules" class="mt-3 hidden"></div>
                    </div>

                    <hr class="ftm-divider">

                    {{-- Section: Data Pemesan --}}
                    <div>
                        <h2 class="ftm-section-title mb-3">Data Pemesan</h2>

                        <div class="space-y-3">
                            <div>
                                <label class="ftm-field-label" for="guestName">Nama Lengkap</label>
                                <input id="guestName" name="name" type="text" class="ftm-input" placeholder="Contoh: Aisyah Putri" autocomplete="name" required>
                            </div>

                            <div>
                                <label class="ftm-field-label" for="guestEmail">Email</label>
                                <input id="guestEmail" name="email" type="email" class="ftm-input" placeholder="nama@email.com" autocomplete="email" inputmode="email" required>
                            </div>

                            <div>
                                <label class="ftm-field-label" for="guestPhone">Nomor WhatsApp</label>
                                <input id="guestPhone" name="phone" type="tel" class="ftm-input" placeholder="08xxxxxxxxxx" autocomplete="tel" inputmode="tel" required>
                            </div>
                        </div>

                        <div class="ftm-info-box mt-3">
                            <div class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="#1A7A5E" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                </svg>
                                <div>
                                    <h4>Tanpa daftar akun</h4>
                                    <p>Setelah pembayaran berhasil, akun member dibuat otomatis. Anda tinggal mengatur password untuk masuk.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="ftm-divider">

                    {{-- Section: Ringkasan Pembayaran --}}
                    <div>
                        <h2 class="ftm-section-title mb-3">Ringkasan Pembayaran</h2>
                        <div class="ftm-summary">
                            <div class="ftm-summary-row">
                                <span>Harga Program (IDR)</span>
                                <span id="priceValue" class="val">{{ rp($price) }}</span>
                            </div>
                            <div class="ftm-summary-row">
                                <span>Diskon Voucher</span>
                                <span id="voucherValue" class="val" style="color:#EE4E8B;">Rp0</span>
                            </div>
                            <div class="ftm-summary-row">
                                <span>Biaya Pendaftaran</span>
                                <span class="val" style="color:#1A7A5E;">Gratis</span>
                            </div>
                            <div class="ftm-summary-divider"></div>
                            <div class="ftm-summary-total">
                                <span class="label">Amount to Pay</span>
                                <span id="totalValue" class="val">{{ rp($price) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Section: Kode Voucher --}}
                    <div>
                        <h2 class="ftm-section-title mb-2">Kode Voucher</h2>
                        <div class="ftm-voucher-row">
                            <input id="voucher" name="voucher_code" type="text" placeholder="Masukkan kode voucher" class="ftm-input" autocomplete="off">
                            <button id="applyVoucher" type="button" class="ftm-btn-apply">Apply</button>
                        </div>
                        <div id="voucherMessage"></div>
                    </div>

                    {{-- Tombol Bayar --}}
                    <button type="submit" id="pay-button" class="ftm-btn-pay mt-1" @disabled($isExclusive && ! $hasClassOptions)>
                        <span>{{ ($isExclusive && ! $hasClassOptions) ? 'Jadwal Belum Tersedia' : 'Bayar Sekarang' }}</span>
                    </button>

                    <div id="statusText">{{ ($isExclusive && ! $hasClassOptions) ? 'Pembayaran dibuka kembali setelah admin menambahkan jadwal untuk program ini.' : '' }}</div>

                    <p class="ftm-secure-note">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H6.75a1.5 1.5 0 0 1-1.5-1.5v-7.5a1.5 1.5 0 0 1 1.5-1.5z"/>
                        </svg>
                        <span>Diproses aman oleh Midtrans</span>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const form = document.getElementById('guestCheckoutForm');
    const payButton = document.getElementById('pay-button');
    const statusText = document.getElementById('statusText');
    const classSelect = document.getElementById('classOption');
    const scheduleContainer = document.getElementById('classSchedules');
    const voucherInput = document.getElementById('voucher');
    const applyVoucherButton = document.getElementById('applyVoucher');
    const voucherMessage = document.getElementById('voucherMessage');
    const priceValue = document.getElementById('priceValue');
    const voucherValue = document.getElementById('voucherValue');
    const totalValue = document.getElementById('totalValue');

    const CONFIG = {
        processUrl: @json(route('guest.checkout.process', $package->slug ?: $package->id)),
        successUrl: @json(route('guest.checkout.success', '__CODE__')),
        voucherCheckUrl: @json(url('/voucher/check')),
        csrfToken: @json(csrf_token()),
        packageId: @json((int) $package->id),
        originalPrice: @json((int) $price),
        isExclusive: @json($isExclusive),
        cannotPay: @json($isExclusive && ! $hasClassOptions),
        classOptions: @json($classOptions),
    };

    const STATE = {
        discount: 0,
        voucherCode: '',
    };

    function formatRupiah(amount) {
        return 'Rp' + (parseInt(amount, 10) || 0).toLocaleString('id-ID');
    }

    function updateSummary() {
        const total = Math.max(0, CONFIG.originalPrice - STATE.discount);
        priceValue.textContent = formatRupiah(CONFIG.originalPrice);
        voucherValue.textContent = STATE.discount > 0 ? '- ' + formatRupiah(STATE.discount) : 'Rp0';
        totalValue.textContent = formatRupiah(total);
    }

    function showVoucherMessage(message, type = 'error') {
        const colors = {
            error: '#7A2B4A',
            success: '#1A7A5E',
            info: '#EE4E8B',
        };

        voucherMessage.textContent = message;
        voucherMessage.style.color = colors[type] || colors.error;
        voucherMessage.style.marginTop = '0.4rem';
        voucherMessage.style.fontSize = '0.75rem';
        voucherMessage.style.fontFamily = "'Poppins', sans-serif";
    }

    function setStatus(message, type = 'info') {
        const colors = {
            info: '#EE4E8B',
            success: '#1A7A5E',
            error: '#7A2B4A',
            default: 'rgba(28, 28, 28, 0.7)',
        };

        statusText.textContent = message;
        statusText.style.color = colors[type] || colors.default;
        statusText.style.fontWeight = type === 'default' ? '500' : '600';
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderClassSchedules(key) {
        if (!scheduleContainer) return;

        const option = CONFIG.classOptions[key];

        if (!option) {
            scheduleContainer.classList.add('hidden');
            scheduleContainer.innerHTML = '';
            return;
        }

        const schedules = Array.isArray(option.schedules) ? option.schedules : [];

        scheduleContainer.innerHTML = schedules.length
            ? '<div class="ftm-info-box"><h4>Jadwal Kelas</h4><ul class="space-y-1.5">'
                + schedules.map((schedule) => '<li class="flex items-start gap-2 text-xs" style="color:rgba(28,28,28,0.72);">'
                    + '<span style="color:#1A7A5E;">&#10003;</span><span>' + escapeHtml(schedule) + '</span></li>').join('')
                + '</ul></div>'
            : '<div class="ftm-info-box"><p>Jadwal akan diinformasikan admin.</p></div>';

        scheduleContainer.classList.remove('hidden');
    }

    if (classSelect) {
        classSelect.addEventListener('change', function () {
            renderClassSchedules(classSelect.value);
        });
    }

    function resetVoucher() {
        STATE.discount = 0;
        STATE.voucherCode = '';
        updateSummary();
    }

    applyVoucherButton.addEventListener('click', function () {
        const code = voucherInput.value.trim();

        if (!code) {
            showVoucherMessage('Masukkan kode voucher', 'error');
            return;
        }

        applyVoucherButton.disabled = true;
        applyVoucherButton.textContent = 'Checking...';

        fetch(CONFIG.voucherCheckUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CONFIG.csrfToken,
            },
            body: JSON.stringify({
                code: code,
                package_id: CONFIG.packageId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                applyVoucherButton.disabled = false;
                applyVoucherButton.textContent = 'Apply';

                if (!data.valid) {
                    resetVoucher();
                    showVoucherMessage(data.message || 'Kode voucher tidak valid', 'error');
                    return;
                }

                const voucherValueNumber = parseFloat(data.value) || 0;
                const maxDiscount = data.max_discount ? parseFloat(data.max_discount) : Infinity;

                if (data.type === 'percent') {
                    STATE.discount = Math.min(
                        Math.floor(CONFIG.originalPrice * voucherValueNumber / 100),
                        maxDiscount
                    );
                } else {
                    STATE.discount = Math.min(voucherValueNumber, maxDiscount, CONFIG.originalPrice);
                }

                STATE.discount = Math.max(0, parseInt(STATE.discount, 10) || 0);
                STATE.voucherCode = data.code || code.toUpperCase();

                updateSummary();
                showVoucherMessage('Voucher berhasil digunakan', 'success');
            })
            .catch(() => {
                applyVoucherButton.disabled = false;
                applyVoucherButton.textContent = 'Apply';
                showVoucherMessage('Gagal memeriksa voucher', 'error');
            });
    });

    voucherInput.addEventListener('input', function () {
        const currentCode = voucherInput.value.trim().toUpperCase();

        if (STATE.voucherCode && currentCode !== STATE.voucherCode.toUpperCase()) {
            resetVoucher();
            showVoucherMessage('Kode berubah. Klik Apply untuk memeriksa ulang.', 'info');
        }
    });

    function setLoading(isLoading) {
        payButton.disabled = isLoading || CONFIG.cannotPay;
        payButton.querySelector('span').textContent = isLoading
            ? 'Menyiapkan pembayaran…'
            : (CONFIG.cannotPay ? 'Jadwal Belum Tersedia' : 'Bayar Sekarang');
    }

    function redirectToOrder(orderCode) {
        window.location.href = CONFIG.successUrl.replace('__CODE__', encodeURIComponent(orderCode));
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        if (CONFIG.cannotPay) {
            setStatus('Pembayaran belum tersedia untuk program ini.', 'error');
            return;
        }

        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const phone = form.phone.value.trim();
        const classId = classSelect ? classSelect.value : '';

        if (!name || !email || !phone) {
            setStatus('Lengkapi nama, email, dan nomor WhatsApp terlebih dahulu.', 'error');
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setStatus('Format email belum benar. Contoh: nama@email.com', 'error');
            return;
        }

        if (CONFIG.isExclusive && !classId) {
            setStatus('Pilih kelas yang tersedia terlebih dahulu.', 'error');
            classSelect?.focus();
            return;
        }

        setLoading(true);
        setStatus('Menyiapkan pembayaran…', 'info');

        const payload = new FormData();
        payload.append('name', name);
        payload.append('email', email);
        payload.append('phone', phone);

        if (classId) {
            payload.append('class_id', classId);
        }

        if (STATE.voucherCode) {
            payload.append('voucher_code', STATE.voucherCode);
        }

        fetch(CONFIG.processUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CONFIG.csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: payload,
        })
            .then(async (response) => {
                const data = await response.json().catch(() => ({}));

                if (!response.ok || !data.success) {
                    const validationMessage = data.errors
                        ? Object.values(data.errors).flat()[0]
                        : null;

                    throw new Error(validationMessage || data.message || 'Pesanan gagal dibuat. Silakan coba lagi.');
                }

                return data;
            })
            .then((data) => {
                if (typeof window.snap === 'undefined') {
                    throw new Error('Modul pembayaran belum siap. Muat ulang halaman lalu coba lagi.');
                }

                setLoading(false);
                setStatus('Membuka jendela pembayaran…', 'info');

                window.snap.pay(data.snapToken, {
                    onSuccess: function () {
                        redirectToOrder(data.order_code);
                    },
                    onPending: function () {
                        redirectToOrder(data.order_code);
                    },
                    onError: function () {
                        setStatus('Pembayaran gagal diproses. Silakan coba metode pembayaran lain.', 'error');
                    },
                    onClose: function () {
                        setStatus('Jendela pembayaran ditutup. Pesanan tersimpan sebagai pending.', 'error');
                    },
                });
            })
            .catch((error) => {
                setLoading(false);
                setStatus(error.message || 'Terjadi kesalahan. Silakan coba lagi.', 'error');
            });
    });
})();
</script>

@endsection
