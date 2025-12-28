 @extends('layouts.app')

@section('content')

@php
    /** NORMALISASI PACKAGE **/
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

    /**
     * KITA TIDAK PAKAI $classes LAGI
     * Controller sekarang kirim $groups (ClassGroup dengan relasi classes.schedules)
     * Di sini kita ratakan dulu jadi $clsCollection supaya kode lama tetap jalan.
     */
    $groupCollection = is_iterable($groups ?? []) ? $groups : [];
    $clsCollection   = [];

$clsCollection = collect();

foreach ($groups as $g) {
    if (isset($g->classes) && $g->classes instanceof \Illuminate\Support\Collection) {
        foreach ($g->classes as $class) {
            $clsCollection->push($class);
        }
    }
}


    /** USER **/
    $user = auth('customer')->user();

    /** DETEKSI REFORMER **/
    $reformerPackageIds = [2,3,4,8,9,10,11,12];
    $isReformer = in_array((int) $pkg->id, $reformerPackageIds);

    /** AUTO SELECT CLASSES (LOGIKA LAMA TETAP) **/
    $selectedClasses = [];

    if ($isReformer) {
        foreach ($clsCollection as $class) {
            $c = is_object($class) ? $class : (object) $class;
            if (!empty($c->class_name) && stripos($c->class_name, 'reformer') !== false) {
                $selectedClasses[] = $c;
            }
        }

        if (count($selectedClasses) === 0 && count($clsCollection) > 0) {
            $selectedClasses[] = is_object($clsCollection[0])
                ? $clsCollection[0]
                : (object) $clsCollection[0];
        }
    } else {
        if (count($clsCollection) > 0) {
            $selectedClasses[] = is_object($clsCollection[0])
                ? $clsCollection[0]
                : (object) $clsCollection[0];
        }
    }

    /** HELPER RUPIAH (CEGAH REDECLARE) **/
    if (!function_exists('rp')) {
        function rp($n) {
            return 'Rp' . number_format((int) $n, 0, ',', '.');
        }
    }
@endphp

<style>
    /* BUTTON – DUSTY ROSE / PINK */
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
</style>


<div class="bg-white flex justify-center items-start pt-12 pb-12">
    <div class="max-w-lg w-full px-6">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-50 p-6 sm:p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Checkout</h1>

            <form id="checkoutForm" class="space-y-6">
                @csrf
                <input type="hidden" name="package_id" value="{{ $pkg->id }}">

                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-gray-800">Program dan Kelas</h2>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1">
                            <label class="text-sm text-gray-500 mb-1 block">Program</label>
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-xl font-medium text-gray-900 border border-gray-200">
                                <span class="truncate">{{ $pkg->name }}</span>
                                <span class="text-sm text-gray-500 ml-2">({{ $pkg->quota }}x)</span>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label class="text-sm text-gray-500 mb-1 block">Kelas Dipilih</label>
                            <select id="classOption" name="class_option" class="w-full p-3 border rounded-xl">
    <option value="">Pilih Kelas</option>
    @foreach($classOptions as $key => $opt)
        <option value="{{ $key }}">{{ $opt['label'] }}</option>
    @endforeach


</select>
                        </div>
                    </div>
                </div>

               <div id="classSchedules" class="mb-6"></div>

                <hr class="border-gray-100">

                {{-- Ringkasan Pembayaran --}}
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

                    {{-- Voucher --}}
                    <div class="pt-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Kode Voucher</h3>
                        <div class="flex gap-3">
                            <input id="voucher" name="voucher_code" type="text" placeholder="Masukkan kode voucher"
                                   class="flex-1 rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-violet-200 focus:border-violet-400">
                            <button id="applyVoucher" type="button"
                                class="h-11 px-6 rounded-lg text-white font-semibold btn-purple shadow-md shadow-violet-500/20 text-sm flex items-center justify-center">
                                Apply
                            </button>
                        </div>
                        <div id="voucherMessage"></div>
                    </div>
                </div>

                <button id="pay-button" type="submit"
                        class="w-full mt-6 py-4 rounded-xl text-white font-semibold btn-purple shadow-lg shadow-violet-500/40 text-lg flex items-center justify-center gap-3">
                    <span id="pay-text">Bayar Sekarang</span>
                </button>

                <div id="statusText" class="text-sm text-center mt-3 text-gray-600"></div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const isReformer = @json($isReformer);
    const selectClass = document.getElementById('classOption');
    const wrapper = selectClass?.closest('.flex-1');

    if (isReformer && selectClass && wrapper) {
        // sembunyikan dropdown HANYA untuk reformer
        wrapper.style.display = 'none';

        // auto pilih class pertama agar jadwal langsung muncul
        const firstKey = Object.keys(classOptions)[0];
        if (firstKey) {
            selectClass.value = firstKey;
            selectClass.dispatchEvent(new Event('change'));
        }
    }
});
</script>

<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>


<script>
const originalPrice = {{ (int) $pkg->price }};
let discount = 0;

/* ================= ELEMENT ================= */
const checkoutForm      = document.getElementById("checkoutForm");
const applyBtn          = document.getElementById("applyVoucher");
const voucherInput      = document.getElementById("voucher");
const voucherValueEl    = document.getElementById("voucherValue");
const totalValueEl      = document.getElementById("totalValue");
const priceValueEl      = document.getElementById("priceValue");
const payBtn            = document.getElementById("pay-button");
const statusText        = document.getElementById("statusText");
const voucherMessage    = document.getElementById("voucherMessage");
const scheduleContainer = document.getElementById("classSchedules");
const selectClass       = document.getElementById("classOption");
const classOptions      = @json($classOptions);
const isReformer = @json($isReformer);


/* ================= HELPER ================= */
function formatRp(n){
    return 'Rp' + n.toLocaleString('id-ID');
}

function updateTotal(){
    const total = Math.max(0, originalPrice - discount);
    voucherValueEl.textContent = discount > 0 ? '- ' + formatRp(discount) : 'Rp0';
    priceValueEl.textContent  = formatRp(originalPrice);
    totalValueEl.textContent  = formatRp(total);
}

updateTotal();

/* ================= APPLY VOUCHER ================= */
applyBtn.addEventListener("click", () => {
    const code = voucherInput.value.trim();

    if (!code) {
        voucherMessage.textContent = "Masukkan kode voucher";
        voucherMessage.className = "mt-2 text-sm text-red-600";
        return;
    }

    applyBtn.disabled = true;
    applyBtn.innerHTML = "Checking...";

    fetch("{{ url('/voucher/check') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ code })
    })
    .then(r => r.json())
    .then(res => {
        applyBtn.disabled = false;
        applyBtn.innerHTML = "Apply";

        if (!res.valid) {
            discount = 0;
            voucherMessage.textContent = res.message;
            voucherMessage.className = "mt-2 text-sm text-red-600";
            updateTotal();
            return;
        }

        discount = res.type === "percent"
            ? Math.min(Math.floor(originalPrice * res.value / 100), res.max_discount ?? Infinity)
            : res.value;

        voucherMessage.textContent = "Voucher berhasil digunakan";
        voucherMessage.className = "mt-2 text-sm text-green-600";
        updateTotal();
    })
    .catch(() => {
        applyBtn.disabled = false;
        applyBtn.innerHTML = "Apply";
        voucherMessage.textContent = "Gagal cek voucher";
        voucherMessage.className = "mt-2 text-sm text-red-600";
    });
});

/* ================= JADWAL KELAS ================= */
selectClass.addEventListener("change", () => {

    // 🚫 KHUSUS REFORMER: JANGAN TAMPILKAN JADWAL
    if (isReformer) {
        scheduleContainer.innerHTML = '';
        return;
    }

    // ✅ PACKAGE LAIN TETAP NORMAL
    const key = selectClass.value;
    if (!key || !classOptions[key]) {
        scheduleContainer.innerHTML = '';
        return;
    }

    let html = `
        <div class="bg-gray-50 border rounded-xl p-4">
            <h4 class="font-semibold mb-2">Jadwal Kelas</h4>
            <ul class="text-sm space-y-1">
    `;

    classOptions[key].schedule.forEach(s => {
        html += `<li>• ${s}</li>`;
    });

    html += `</ul></div>`;
    scheduleContainer.innerHTML = html;
});


/* ================= SUBMIT CHECKOUT ================= */
checkoutForm.addEventListener("submit", e => {
    e.preventDefault();

    @if(!$isReformer)
    if (!selectClass.value) {
        alert("Pilih kelas dahulu");
        return;
    }
    @endif

    payBtn.disabled = true;

    const formData = new FormData(checkoutForm);
    formData.append("total_price", Math.max(0, originalPrice - discount));
    formData.append("discount", discount);

    // ✅ TAMBAHKAN INI (WAJIB)
    @if(!$isReformer)
        formData.append("schedule_ids[]", 1); // dummy dulu
    @endif

    fetch("{{ url('/checkout/process') }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
        body: formData
    })
    .then(r => r.json())
    .then(d => {
        if (!d.snapToken) {
            throw new Error("Snap token tidak diterima");
        }
        snap.pay(d.snapToken);
    })
    .catch(err => {
        console.error(err);
        payBtn.disabled = false;
        alert("Gagal memproses pembayaran");
    });
});

</script>
