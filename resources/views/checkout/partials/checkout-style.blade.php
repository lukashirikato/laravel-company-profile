<style>
    /* ============================================ */
    /* FTM CHECKOUT — Brand-aligned & mobile-first  */
    /* Brand: Power Pink #EE4E8B · Burnt Cherry #7A2B4A */
    /*        Soft Petals #F4C9DF · Rising #FCF9F2     */
    /* ============================================ */

    .ftm-checkout-page {
        background: #FCF9F2;
        min-height: 100vh;
    }

    /* Heading utama "Checkout" */
    .ftm-checkout-title {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 800;
        letter-spacing: -0.01em;
        color: #7A2B4A;
        font-size: 1.5rem;
        line-height: 1.2;
        text-align: center;
        margin-bottom: 1.25rem;
    }
    @media (min-width: 640px) {
        .ftm-checkout-title { font-size: 1.875rem; margin-bottom: 1.5rem; }
    }

    /* Sub-heading section (Program dan Kelas / Ringkasan Pembayaran / Kode Voucher) */
    .ftm-section-title {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        color: #7A2B4A;
        font-size: 0.95rem;
        letter-spacing: 0.02em;
        text-transform: none;
        line-height: 1.35;
        margin: 0;
    }

    /* Label kecil di atas field */
    .ftm-field-label {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 0.75rem;
        color: rgba(28, 28, 28, 0.6);
        margin-bottom: 0.35rem;
        display: block;
    }

    /* Field readonly (Program name) */
    .ftm-field-static {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 0.9rem;
        background: #FCF9F2;
        border: 1px solid rgba(244, 201, 223, 0.7);
        border-radius: 0.75rem;
        color: #1C1C1C;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 0.875rem;
    }
    .ftm-field-static .quota {
        font-size: 0.75rem;
        color: rgba(28, 28, 28, 0.55);
        font-weight: 400;
        margin-left: 0.5rem;
        flex-shrink: 0;
    }

    /* Select & input dasar */
    .ftm-input,
    .ftm-select {
        width: 100%;
        padding: 0.7rem 0.9rem;
        border: 1px solid rgba(244, 201, 223, 0.7);
        border-radius: 0.75rem;
        background: #FFFFFF;
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: 0.875rem;
        color: #1C1C1C;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .ftm-select {
        appearance: none;
        -webkit-appearance: none;
        background-image:
            linear-gradient(45deg, transparent 50%, #7A2B4A 50%),
            linear-gradient(135deg, #7A2B4A 50%, transparent 50%);
        background-position:
            calc(100% - 22px) calc(50% - 2px),
            calc(100% - 16px) calc(50% - 2px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
        padding-right: 2.7rem;
    }
    .ftm-select option {
        color: #1C1C1C;
        background: #FFFFFF;
    }
    .ftm-input:focus,
    .ftm-select:focus {
        outline: none;
        border-color: #EE4E8B;
        box-shadow: 0 0 0 3px rgba(238, 78, 139, 0.15);
    }
    .ftm-input::placeholder { color: rgba(28, 28, 28, 0.4); }

    /* Summary card */
    .ftm-summary {
        background: #FCF9F2;
        border: 1px solid rgba(244, 201, 223, 0.7);
        border-radius: 0.85rem;
        padding: 0.9rem 1rem;
    }
    .ftm-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: 'Poppins', sans-serif;
        font-size: 0.825rem;
        color: #1C1C1C;
        padding: 0.15rem 0;
    }
    .ftm-summary-row .val { font-weight: 500; }
    .ftm-summary-divider {
        height: 1px;
        background: rgba(244, 201, 223, 0.7);
        margin: 0.55rem 0;
    }
    .ftm-summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .ftm-summary-total .label {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.9rem;
        color: #7A2B4A;
    }
    .ftm-summary-total .val {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1.1rem;
        color: #7A2B4A;
        letter-spacing: -0.01em;
    }
    @media (min-width: 640px) {
        .ftm-summary-total .val { font-size: 1.25rem; }
    }

    /* Voucher input + apply */
    .ftm-voucher-row {
        display: flex;
        gap: 0.5rem;
    }
    .ftm-btn-apply {
        flex-shrink: 0;
        background: #7A2B4A;
        color: #FFFFFF;
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        padding: 0 1.1rem;
        border-radius: 0.7rem;
        border: none;
        cursor: pointer;
        transition: background .15s ease, transform .1s ease;
    }
    .ftm-btn-apply:hover:not(:disabled) {
        background: #EE4E8B;
    }
    .ftm-btn-apply:active:not(:disabled) { transform: scale(0.97); }
    .ftm-btn-apply:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    /* Tombol Bayar utama */
    .ftm-btn-pay {
        width: 100%;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #EE4E8B 0%, #7A2B4A 100%);
        color: #FFFFFF;
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 1rem 1.25rem;
        border-radius: 1rem;
        border: none;
        cursor: pointer;
        box-shadow: 0 14px 28px rgba(122, 43, 74, 0.25), 0 6px 14px rgba(238, 78, 139, 0.22);
        transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
    }
    .ftm-btn-pay::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
        transform: translateX(-120%);
        transition: transform .55s ease;
    }
    .ftm-btn-pay span {
        position: relative;
        z-index: 1;
    }
    .ftm-btn-pay:hover:not(:disabled) {
        transform: translateY(-1px);
        filter: saturate(1.05);
        box-shadow: 0 18px 34px rgba(122, 43, 74, 0.32), 0 8px 18px rgba(238, 78, 139, 0.24);
    }
    .ftm-btn-pay:hover:not(:disabled)::before {
        transform: translateX(120%);
    }
    .ftm-btn-pay:active:not(:disabled) { transform: scale(0.99); }
    .ftm-btn-pay:disabled {
        background: linear-gradient(135deg, rgba(122, 43, 74, 0.35), rgba(122, 43, 74, 0.18));
        color: rgba(122, 43, 74, 0.7);
        opacity: 1;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Info box "Cara Booking" */
    .ftm-info-box {
        background: rgba(244, 201, 223, 0.35);
        border: 1px solid rgba(238, 78, 139, 0.25);
        border-radius: 0.85rem;
        padding: 0.85rem 1rem;
        font-family: 'Poppins', sans-serif;
    }
    .ftm-info-box h4 {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        color: #7A2B4A;
        font-size: 0.825rem;
        margin: 0 0 0.2rem 0;
    }
    .ftm-info-box p {
        color: rgba(28, 28, 28, 0.75);
        font-size: 0.78rem;
        line-height: 1.5;
        margin: 0;
    }

    /* Schedule list (dynamic) */
    .ftm-schedule-box {
        background: linear-gradient(135deg, rgba(122, 43, 74, 0.08), rgba(238, 78, 139, 0.08));
        border: 1px solid rgba(238, 78, 139, 0.28);
        border-radius: 1rem;
        padding: 0.95rem;
        margin-top: 0.75rem;
        box-shadow: 0 10px 24px rgba(122, 43, 74, 0.08);
    }
    .ftm-schedule-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .ftm-schedule-box h4 {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 800;
        color: #7A2B4A;
        font-size: 0.95rem;
        margin: 0;
    }
    .ftm-schedule-box p {
        font-family: 'Poppins', sans-serif;
        font-size: 0.72rem;
        color: rgba(28, 28, 28, 0.62);
        line-height: 1.45;
        margin: 0.15rem 0 0;
    }
    .ftm-schedule-badge {
        flex-shrink: 0;
        background: #7A2B4A;
        color: #FFFFFF;
        border-radius: 999px;
        padding: 0.28rem 0.55rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.66rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .ftm-schedule-box ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }
    .ftm-schedule-box li {
        display: flex;
        align-items: flex-start;
        gap: 0.55rem;
        background: rgba(255, 255, 255, 0.82);
        border: 1px solid rgba(244, 201, 223, 0.65);
        border-radius: 0.75rem;
        padding: 0.6rem 0.7rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.8rem;
        color: #1C1C1C;
    }
    .ftm-schedule-box li .check {
        color: #EE4E8B;
        font-weight: 700;
        line-height: 1.35;
    }
    .ftm-schedule-box li strong {
        display: block;
        color: #7A2B4A;
        font-weight: 700;
    }
    .ftm-schedule-box li span:last-child {
        line-height: 1.35;
    }

    .ftm-professional-note {
        background: rgba(238, 78, 139, 0.08);
        border: 1px solid rgba(238, 78, 139, 0.22);
        border-radius: 0.85rem;
        padding: 0.8rem 0.95rem;
        margin-top: 0.65rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.76rem;
        line-height: 1.55;
        color: rgba(28, 28, 28, 0.72);
    }
    .ftm-professional-note strong {
        color: #7A2B4A;
        font-weight: 700;
    }

    /* Card wrapper */
    .ftm-checkout-card {
        background: #FFFFFF;
        border: 1px solid rgba(244, 201, 223, 0.55);
        border-radius: 1.1rem;
        box-shadow: 0 6px 20px rgba(122, 43, 74, 0.06);
        padding: 1.1rem;
    }
    @media (min-width: 640px) {
        .ftm-checkout-card { padding: 1.5rem; }
    }

    /* Section divider */
    .ftm-divider {
        border: none;
        border-top: 1px solid rgba(244, 201, 223, 0.6);
        margin: 0.85rem 0;
    }

    /* Status text */
    #statusText {
        font-family: 'Poppins', sans-serif;
        font-size: 0.78rem;
        text-align: center;
        margin-top: 0.6rem;
        color: rgba(28, 28, 28, 0.7);
    }

    /* Trust note di bawah tombol bayar */
    .ftm-secure-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin-top: 0.85rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.72rem;
        color: rgba(28, 28, 28, 0.55);
    }

    /* Voucher message */
    #voucherMessage {
        font-family: 'Poppins', sans-serif;
        font-size: 0.75rem;
        margin-top: 0.4rem;
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
        background-color: rgba(28, 28, 28, 0.55);
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease;
    }

    .custom-modal.active {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: #FCF9F2;
        padding: 1.5rem;
        border-radius: 1rem;
        max-width: 380px;
        width: calc(100% - 2rem);
        box-shadow: 0 20px 50px rgba(122, 43, 74, 0.25);
        border: 1px solid rgba(244, 201, 223, 0.6);
        animation: slideUp 0.3s ease;
    }
    @media (min-width: 640px) {
        .modal-content { padding: 2rem; max-width: 420px; }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to   { transform: translateY(0); opacity: 1; }
    }

    .modal-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 0.85rem;
        background: #F4C9DF;
        border: 2px solid #EE4E8B;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7A2B4A;
    }

    .modal-content h3 {
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        color: #7A2B4A;
        text-align: center;
        font-size: 1.05rem;
        margin: 0 0 0.4rem 0;
    }
    .modal-content p {
        font-family: 'Poppins', sans-serif;
        color: rgba(28, 28, 28, 0.7);
        text-align: center;
        font-size: 0.825rem;
        line-height: 1.5;
        margin: 0;
    }

    .modal-buttons {
        display: flex;
        gap: 0.6rem;
        margin-top: 1.25rem;
    }
    .modal-btn {
        flex: 1;
        padding: 0.7rem 0.9rem;
        border-radius: 0.65rem;
        font-family: 'Nord', 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: background .15s ease, transform .1s ease;
        border: none;
    }
    .modal-btn-cancel {
        background: transparent;
        color: #7A2B4A;
        border: 1.5px solid #7A2B4A;
    }
    .modal-btn-cancel:hover {
        background: rgba(122, 43, 74, 0.08);
    }
    .modal-btn-continue {
        background: #EE4E8B;
        color: #FFFFFF;
    }
    .modal-btn-continue:hover {
        background: #7A2B4A;
    }
</style>
