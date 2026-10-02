<x-filament::page>
    @php
        $pageWireId  = $_instance->id ?? null;
        $insideMembers = collect($recentScans)->where('status', 'active')->values();
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;900&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           FTM SOCIETY — KONSOL CHECK-IN
           Arah: signage lantai gym. Slab hitam, kuning keselamatan,
           instrumen mono. Nol radius, garis tebal, tanpa dekorasi.
           ============================================================ */

        .filament-page-heading,
        .filament-page-header,
        .filament-page > header,
        .filament-page-breadcrumbs {
            display: none !important;
        }
        .filament-page {
            padding-top: 0.5rem !important;
            background: var(--concrete) !important;
        }

        .desk {
            --ink: #1F2937; /* Equivalent to Filament's dark gray */
            --concrete: #F3F4F6; /* Equivalent to Filament's light gray */
            --chalk: #FFFFFF;
            --signal: #3B82F6; /* Equivalent to Filament's blue-500 */
            --court: #10B981; /* Equivalent to Filament's green-500 */
            --alert: #EF4444; /* Equivalent to Filament's red-500 */

            font-family: 'Archivo', 'Helvetica Neue', Arial, sans-serif;
            color: var(--ink);
            max-width: 1240px;
            margin: 0 auto;
        }
        .desk *,
        .desk *::before,
        .desk *::after {
            box-sizing: border-box;
        }

        /* ---------- Ribbon ---------- */
        .desk-ribbon {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            justify-content: space-between;
            gap: 0;
            background: var(--ink);
            color: var(--chalk);
            border-bottom: 4px solid var(--signal);
        }
        .desk-ribbon__brand {
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 2px;
            padding: 14px 22px;
            border-right: 1px solid rgba(255, 255, 255, 0.18);
        }
        .desk-ribbon__brand strong {
            font-weight: 900;
            font-size: 15px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }
        .desk-ribbon__brand span {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
        }
        .desk-ribbon__mode {
            display: flex;
            align-items: center;
            padding: 14px 22px;
            flex: 1;
            min-width: 200px;
        }
        .desk-chip {
            display: inline-block;
            font-weight: 900;
            font-size: 13px;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            padding: 8px 14px;
        }
        .desk-chip--signal {
            background: var(--signal);
            color: var(--ink);
        }
        .desk-chip--chalk {
            background: var(--chalk);
            color: var(--ink);
        }
        .desk-ribbon__clock {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            gap: 1px;
            padding: 10px 22px;
            border-left: 1px solid rgba(255, 255, 255, 0.18);
            text-align: right;
        }
        .desk-ribbon__date {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
        }
        .desk-clock {
            font-family: 'IBM Plex Mono', Consolas, monospace;
            font-weight: 600;
            font-size: clamp(1.35rem, 2.6vw, 1.75rem);
            letter-spacing: 0.04em;
            font-variant-numeric: tabular-nums;
            color: var(--signal);
            line-height: 1.1;
        }
        .desk-clock .desk-blink {
            animation: desk-blink 1s steps(1) infinite;
        }
        @keyframes desk-blink {
            50% { opacity: 0.15; }
        }

        /* ---------- Grid ---------- */
        .desk-grid {
            display: grid;
            grid-template-columns: minmax(0, 7fr) minmax(0, 5fr);
            gap: 20px;
            margin-top: 20px;
            align-items: start;
        }
        .desk-main,
        .desk-side {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 0;
        }
        .desk-side {
            position: sticky;
            top: 16px;
        }
        @media (max-width: 1080px) {
            .desk-grid {
                grid-template-columns: minmax(0, 1fr);
            }
            .desk-side {
                position: static;
            }
        }

        /* ---------- Konsol scan ---------- */
        .desk-console {
            background: var(--ink);
            color: var(--chalk);
            padding: clamp(20px, 3vw, 32px);
            border: 3px solid var(--ink);
        }
        .desk-console__eyebrow {
            font-family: 'IBM Plex Mono', Consolas, monospace;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--signal);
            margin: 0 0 10px;
        }
        .desk-console__title {
            margin: 0;
            font-weight: 900;
            font-size: clamp(2.6rem, 7vw, 4.75rem);
            line-height: 0.92;
            letter-spacing: -0.025em;
            text-transform: uppercase;
        }
        .desk-console__title .desk-hyphen {
            color: var(--signal);
        }
        .desk-console__sub {
            margin: 12px 0 0;
            font-size: 15px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.62);
            max-width: 46ch;
        }

        /* Segmented mode switch */
        .desk-seg {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            margin-top: 22px;
            border: 2px solid rgba(255, 255, 255, 0.35);
        }
        .desk-seg__btn {
            appearance: none;
            font-family: inherit;
            font-weight: 900;
            font-size: 13px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            padding: 13px 10px;
            background: transparent;
            color: rgba(255, 255, 255, 0.6);
            border: 0;
            cursor: pointer;
            transition: background 0.12s ease, color 0.12s ease;
        }
        .desk-seg__btn + .desk-seg__btn {
            border-left: 2px solid rgba(255, 255, 255, 0.35);
        }
        .desk-seg__btn:hover {
            color: var(--chalk);
            background: rgba(255, 255, 255, 0.07);
        }
        .desk-seg__btn.is-active {
            background: var(--signal);
            color: var(--ink);
        }
        .desk-seg__btn:focus-visible {
            outline: 3px solid var(--signal);
            outline-offset: -3px;
        }

        /* Slot scan + laser */
        .desk-field-label {
            display: block;
            margin: 26px 0 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
        }
        .desk-slot {
            position: relative;
            overflow: hidden;
            background: #000000;
            border: 2px solid #6B7280;
        }
        .desk-slot:focus-within {
            border-color: var(--signal);
        }
        .desk-slot--error {
            border-color: var(--alert);
        }
        .desk-slot__laser {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -4px;
            width: 3px;
            background: var(--signal);
            box-shadow: 0 0 14px 3px rgba(59, 130, 246, 0.55);
            animation: desk-sweep 2.6s linear infinite;
            pointer-events: none;
            opacity: 0.9;
        }
        .desk-slot:focus-within .desk-slot__laser {
            animation-duration: 1.2s;
        }
        @keyframes desk-sweep {
            from { left: -4px; }
            to   { left: 100%; }
        }
        .desk-input {
            position: relative;
            z-index: 1;
            display: block;
            width: 100%;
            height: 84px;
            padding: 0 22px;
            background: transparent;
            border: 0;
            outline: none;
            color: var(--chalk);
            caret-color: var(--signal);
            font-family: 'IBM Plex Mono', Consolas, monospace;
            font-weight: 500;
            font-size: clamp(1.15rem, 2.5vw, 1.5rem);
            letter-spacing: 0.05em;
        }
        .desk-input::placeholder {
            color: rgba(255, 255, 255, 0.34);
            font-family: 'Archivo', sans-serif;
            font-weight: 500;
            font-size: 0.95rem;
            letter-spacing: 0;
        }

        /* Error inside console */
        .desk-error {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-top: 12px;
            padding: 14px 16px;
            background: var(--alert);
            color: var(--chalk);
        }
        .desk-error__tag {
            flex-shrink: 0;
            font-family: 'IBM Plex Mono', Consolas, monospace;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.14em;
            background: var(--ink);
            color: var(--signal);
            padding: 4px 8px;
        }
        .desk-error__body {
            flex: 1;
            min-width: 0;
        }
        .desk-error__body p {
            margin: 0;
            font-weight: 600;
            font-size: 14.5px;
            line-height: 1.45;
        }
        .desk-error__body small {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            opacity: 0.85;
        }
        .desk-error__close {
            appearance: none;
            background: transparent;
            border: 0;
            color: var(--chalk);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            padding: 2px;
        }

        /* Actions */
        .desk-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 18px;
        }
        .desk-btn {
            appearance: none;
            font-family: inherit;
            font-weight: 900;
            font-size: 14px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            height: 56px;
            padding: 0 26px;
            border: 2px solid transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform 0.08s ease, filter 0.12s ease;
        }
        .desk-btn:active {
            transform: translate(2px, 2px);
        }
        .desk-btn:focus-visible {
            outline: 3px solid var(--signal);
            outline-offset: 2px;
        }
        .desk-btn--signal:focus-visible,
        .desk-btn--outline-ink:focus-visible {
            outline-color: var(--ink);
        }
        .desk-btn--signal {
            background: var(--signal);
            color: var(--ink);
            border-color: var(--signal);
            flex: 1;
            min-width: 220px;
        }
        .desk-btn--signal:hover {
            filter: brightness(1.06);
        }
        .desk-btn--signal:disabled {
            opacity: 0.6;
            cursor: wait;
        }
        .desk-btn--ghost {
            background: transparent;
            color: var(--chalk);
            border-color: rgba(255, 255, 255, 0.45);
        }
        .desk-btn--ghost:hover {
            border-color: var(--chalk);
            background: rgba(255, 255, 255, 0.06);
        }
        .desk-btn--ghost kbd {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            font-weight: 500;
            border: 1px solid rgba(255, 255, 255, 0.45);
            padding: 2px 6px;
            letter-spacing: 0;
        }
        .desk-btn--ink {
            background: var(--ink);
            color: var(--chalk);
            border-color: var(--ink);
            box-shadow: 4px 4px 0 rgba(31, 41, 55, 0.25);
        }
        .desk-btn--ink:hover {
            filter: brightness(1.25);
        }
        .desk-btn--outline-ink {
            background: var(--chalk);
            color: var(--ink);
            border-color: var(--ink);
        }
        .desk-btn--outline-ink:hover {
            background: var(--signal);
        }

        .desk-hints {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.16);
            display: flex;
            flex-wrap: wrap;
            gap: 8px 18px;
            align-items: center;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.55);
        }
        .desk-hints code {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            color: var(--signal);
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.35);
            padding: 3px 8px;
        }
        .desk-hints kbd {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 1px 6px;
            color: rgba(255, 255, 255, 0.8);
        }

        /* ---------- Panel (kartu terang) ---------- */
        .desk-panel {
            background: var(--chalk);
            border: 3px solid var(--ink);
        }
        .desk-panel__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 18px;
            border-bottom: 3px solid var(--ink);
        }
        .desk-panel__head h3 {
            margin: 0;
            font-weight: 900;
            font-size: 15px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .desk-panel__meta {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            font-weight: 500;
            color: rgba(31, 41, 55, 0.55);
            font-variant-numeric: tabular-nums;
        }
        .desk-panel__body {
            padding: 0;
        }

        /* ---------- Papan okupansi (di gym sekarang) ---------- */
        .desk-board {
            background: var(--ink);
            color: var(--chalk);
            border: 3px solid var(--ink);
        }
        .desk-board__head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
        }
        .desk-board__head h3 {
            margin: 0;
            font-weight: 900;
            font-size: 15px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .desk-board__head p {
            margin: 3px 0 0;
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 500;
        }
        .desk-board__count {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: clamp(2.4rem, 5vw, 3.2rem);
            line-height: 0.9;
            color: var(--signal);
            font-variant-numeric: tabular-nums;
        }
        .desk-board__rows {
            list-style: none;
            margin: 0;
            padding: 0;
            max-height: 320px;
            overflow-y: auto;
        }
        .desk-board__row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            animation: desk-flip 0.28s cubic-bezier(0.2, 0.8, 0.2, 1) both;
        }
        .desk-board__row:last-child {
            border-bottom: 0;
        }
        .desk-board__time {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: 16px;
            color: var(--signal);
            font-variant-numeric: tabular-nums;
            flex-shrink: 0;
            min-width: 54px;
        }
        .desk-board__who {
            flex: 1;
            min-width: 0;
        }
        .desk-board__who strong {
            display: block;
            font-size: 14.5px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .desk-board__who span {
            display: block;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.55);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .desk-board__empty {
            padding: 34px 18px;
            text-align: center;
        }
        .desk-board__empty strong {
            display: block;
            font-weight: 900;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.8);
        }
        .desk-board__empty span {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.45);
        }
        .desk-board__foot {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-top: 1px solid rgba(255, 255, 255, 0.18);
        }
        .desk-board__stat {
            padding: 12px 10px;
            text-align: center;
        }
        .desk-board__stat + .desk-board__stat {
            border-left: 1px solid rgba(255, 255, 255, 0.18);
        }
        .desk-board__stat b {
            display: block;
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: 1.6rem;
            line-height: 1.1;
            font-variant-numeric: tabular-nums;
        }
        .desk-board__stat span {
            display: block;
            margin-top: 2px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.5);
        }
        .desk-board__stat--ok b { color: var(--signal); }
        .desk-board__stat--bad b {             color: var(--alert); }

        /* ---------- Kartu hasil ---------- */
        .desk-result {
            background: var(--chalk);
            border: 3px solid var(--ink);
            animation: desk-flip 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) both;
        }
        .desk-result__head {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: var(--ink);
            color: var(--chalk);
        }
        .desk-result__mark {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--signal);
            color: var(--ink);
            font-weight: 900;
            font-size: 17px;
        }
        .desk-result__mark--alert {
            background: var(--alert);
            color: var(--chalk);
        }
        .desk-result__head h3 {
            margin: 0;
            font-weight: 900;
            font-size: 16px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .desk-result__head p {
            margin: 2px 0 0;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.6);
        }
        .desk-result__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        @media (max-width: 560px) {
            .desk-result__grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }
        .desk-cell {
            padding: 15px 18px;
            border-bottom: 1px solid rgba(31, 41, 55, 0.12);
            border-right: 1px solid rgba(31, 41, 55, 0.12);
            min-width: 0;
        }
        .desk-cell:nth-child(2n) {
            border-right: 0;
        }
        .desk-cell--wide {
            grid-column: 1 / -1;
            border-right: 0;
        }
        .desk-cell--signal {
            background: var(--signal);
        }
        .desk-cell--court {
            background: rgba(16, 185, 129, 0.1);
        }
        .desk-cell label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(31, 41, 55, 0.5);
            margin-bottom: 5px;
        }
        .desk-cell strong {
            display: block;
            font-size: 17px;
            font-weight: 900;
            line-height: 1.25;
            word-break: break-word;
        }
        .desk-cell .desk-data {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }
        .desk-cell small {
            display: block;
            margin-top: 3px;
            font-size: 12.5px;
            color: rgba(31, 41, 55, 0.55);
        }
        .desk-cell .desk-big {
            font-family: 'IBM Plex Mono', monospace;
            font-size: clamp(2rem, 5vw, 2.75rem);
            font-weight: 600;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .desk-tag {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 3px 8px;
            background: var(--ink);
            color: var(--signal);
            vertical-align: middle;
        }
        .desk-tag--outline {
            background: transparent;
            color: var(--ink);
            border: 2px solid var(--ink);
        }
        .desk-result__foot {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 13px 18px;
            background: var(--concrete);
            border-top: 3px solid var(--ink);
        }
        .desk-result__foot p {
            margin: 0;
            font-size: 12.5px;
            color: rgba(31, 41, 55, 0.6);
        }
        .desk-result__duration {
            padding: 22px 18px;
            background: var(--ink);
            color: var(--chalk);
            text-align: center;
        }
        .desk-result__duration label,
        .desk-result__duration .desk-duration__meta {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
        }
        .desk-result__duration .desk-duration__meta {
            margin-top: 8px;
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 500;
            font-size: 12px;
            letter-spacing: 0.08em;
            color: rgba(255, 255, 255, 0.75);
        }
        .desk-result__duration b {
            display: block;
            margin-top: 6px;
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: clamp(2.4rem, 6vw, 3.4rem);
            line-height: 1;
            color: var(--signal);
        }

        /* ---------- Riwayat ---------- */
        .desk-rows {
            list-style: none;
            margin: 0;
            padding: 0;
            max-height: 360px;
            overflow-y: auto;
        }
        .desk-rows li {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 18px;
            border-bottom: 1px solid rgba(31, 41, 55, 0.1);
        }
        .desk-rows li:last-child {
            border-bottom: 0;
        }
        .desk-rows .desk-rows__time {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: 14px;
            font-variant-numeric: tabular-nums;
            flex-shrink: 0;
            min-width: 48px;
        }
        .desk-rows .desk-rows__who {
            flex: 1;
            min-width: 0;
        }
        .desk-rows .desk-rows__who strong {
            display: block;
            font-size: 14.5px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .desk-rows .desk-rows__who span {
            display: block;
            font-size: 12.5px;
            color: rgba(31, 41, 55, 0.55);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .desk-status {
            flex-shrink: 0;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 5px 9px;
            border: 2px solid var(--ink);
        }
        .desk-status--in {
            background: var(--signal);
            color: var(--ink);
        }
        .desk-status--done {
            background: transparent;
            color: var(--court);
            border-color: var(--court);
        }
        .desk-empty {
            padding: 40px 18px;
            text-align: center;
        }
        .desk-empty strong {
            display: block;
            font-weight: 900;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .desk-empty span {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            color: rgba(31, 41, 55, 0.5);
        }

        /* ---------- Aturan ---------- */
        .desk-rules {
            list-style: none;
            margin: 0;
            padding: 16px 18px;
        }
        .desk-rules li {
            position: relative;
            padding: 0 0 12px 22px;
            font-size: 13.5px;
            line-height: 1.5;
            color: rgba(31, 41, 55, 0.78);
        }
        .desk-rules li:last-child {
            padding-bottom: 0;
        }
        .desk-rules li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            width: 9px;
            height: 9px;
            background: var(--signal);
            border: 1.5px solid var(--ink);
        }
        .desk-keys {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            padding: 14px 18px;
            background: var(--signal);
            border-top: 3px solid var(--ink);
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink);
        }
        .desk-keys kbd {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            font-weight: 600;
            background: var(--ink);
            color: var(--signal);
            padding: 2px 7px;
            margin-right: 6px;
        }

        @keyframes desk-flip {
            from {
                opacity: 0;
                transform: rotateX(-40deg);
            }
            to {
                opacity: 1;
                transform: rotateX(0deg);
            }
        }

        /* ---------- Modal pilih kelas ---------- */
        .desk-modal {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            background: rgba(31, 41, 55, 0.72);
        }
        .desk-modal__panel {
            width: 100%;
            max-width: 640px;
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            background: var(--chalk);
            border: 3px solid var(--ink);
            box-shadow: 10px 10px 0 rgba(59, 130, 246, 0.9);
        }
        .desk-modal__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            background: var(--ink);
            color: var(--chalk);
            border-bottom: 4px solid var(--signal);
        }
        .desk-modal__head h3 {
            margin: 0;
            font-weight: 900;
            font-size: 17px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .desk-modal__head p {
            margin: 3px 0 0;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.6);
        }
        .desk-modal__body {
            padding: 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .desk-pick {
            appearance: none;
            font-family: inherit;
            text-align: left;
            width: 100%;
            padding: 15px 16px;
            background: var(--chalk);
            color: var(--ink);
            border: 2px solid var(--ink);
            cursor: pointer;
            transition: background 0.1s ease, transform 0.08s ease;
        }
        .desk-pick:hover {
            background: var(--signal);
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0 rgba(31, 41, 55, 1);
        }
        .desk-pick:focus-visible {
            outline: 3px solid var(--ink);
            outline-offset: 2px;
        }
        .desk-pick--window {
            background: var(--signal);
        }
        .desk-pick--window:hover {
            filter: brightness(1.04);
        }
        .desk-pick__top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }
        .desk-pick__top strong {
            font-weight: 900;
            font-size: 16.5px;
        }
        .desk-pick__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 5px 16px;
            font-size: 13px;
            color: rgba(31, 41, 55, 0.7);
        }
        @media (max-width: 520px) {
            .desk-pick__grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }
        .desk-pick__grid span {
            display: flex;
            align-items: center;
            gap: 7px;
            min-width: 0;
        }
        .desk-pick__grid b {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            color: var(--ink);
        }
        .desk-modal__foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 20px;
            background: var(--concrete);
            border-top: 3px solid var(--ink);
            font-size: 12.5px;
            color: rgba(31, 41, 55, 0.6);
        }
        .desk-modal__foot kbd {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            border: 1.5px solid var(--ink);
            background: var(--chalk);
            color: var(--ink);
            padding: 1px 6px;
        }

        /* Spinner */
        .desk-spin {
            display: inline-block;
            vertical-align: middle;
            width: 16px;
            height: 16px;
            border: 3px solid rgba(31, 41, 55, 0.25);
            border-top-color: var(--ink);
            animation: desk-rot 0.7s linear infinite;
        }
        @keyframes desk-rot {
            to { transform: rotate(360deg); }
        }

        /* Aksesibilitas gerak */
        @media (prefers-reduced-motion: reduce) {
            .desk-clock .desk-blink,
            .desk-slot__laser,
            .desk-board__row,
            .desk-result,
            .desk-spin {
                animation: none !important;
            }
            .desk-slot__laser {
                opacity: 0.4;
                left: 40%;
            }
        }
    </style>

    <script>
        (function () {
            function focusScanner() {
                setTimeout(function () {
                    var el = document.getElementById('qr-token-input');
                    if (!el) return;
                    el.focus();
                    if (el.value) el.select();
                }, 60);
            }

            if (!window.__ftmQrFocusBound) {
                window.__ftmQrFocusBound = true;
                window.addEventListener('scanner-focus', focusScanner);
            }

            if (!window.__ftmQrKeysBound) {
                window.__ftmQrKeysBound = true;
                document.addEventListener('keydown', function (e) {
                    var page = document.querySelector('.qr-scanner-page');
                    if (!page) return;

                    var inPage = page.contains(e.target) || e.target === document.body;
                    if (!inPage) return;

                    if (e.key === 'Escape') {
                        e.preventDefault();
                        var wireId = page.getAttribute('data-wire-id');
                        if (wireId && window.Livewire) {
                            var component = window.Livewire.find(wireId);
                            if (component) component.call('resetForm');
                        }
                        return;
                    }

                    if (e.key === '/' && !e.target.matches('input, textarea, select')) {
                        e.preventDefault();
                        focusScanner();
                    }
                });
            }
        })();

        function ftmScanner() {
            return {
                hh: '--',
                mm: '--',
                ss: '--',
                date: '',
                init() {
                    var tick = function () {
                        var d = new Date();
                        var p = function (n) { return String(n).padStart(2, '0'); };
                        this.hh = p(d.getHours());
                        this.mm = p(d.getMinutes());
                        this.ss = p(d.getSeconds());
                        this.date = d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                    }.bind(this);

                    tick();
                    setInterval(tick, 1000);

                    this.$nextTick(function () {
                        window.dispatchEvent(new Event('scanner-focus'));
                    });
                },
            };
        }
    </script>

    <div
        class="qr-scanner-page desk"
        data-wire-id="{{ $pageWireId }}"
        x-data="ftmScanner()"
    >
        {{-- ===== RIBBON ===== --}}
        <header class="desk-ribbon">
            <div class="desk-ribbon__brand">
                <strong>FTM Society</strong>
                <span>Meja check-in</span>
            </div>
            <div class="desk-ribbon__mode">
                <span class="desk-chip {{ $isCheckOutMode ? 'desk-chip--chalk' : 'desk-chip--signal' }}">
                    Mode {{ $isCheckOutMode ? 'Keluar' : 'Masuk' }}
                </span>
            </div>
            <div class="desk-ribbon__clock">
                <span class="desk-ribbon__date" x-text="date">&nbsp;</span>
                <span class="desk-clock" aria-live="off">
                    <span x-text="hh">--</span><span class="desk-blink">:</span><span x-text="mm">--</span><span class="desk-blink">:</span><span x-text="ss">--</span>
                </span>
            </div>
        </header>

        <div class="desk-grid">
            {{-- ============ KIRI: konsol + hasil + riwayat ============ --}}
            <div class="desk-main">

                {{-- KONSOL SCAN --}}
                <section class="desk-console" aria-label="Konsol scan">
                    <p class="desk-console__eyebrow">Arahkan scanner · tekan Enter</p>
                    <h1 class="desk-console__title">
                        @if($isCheckOutMode)
                            Check<span class="desk-hyphen">-</span>Out
                        @else
                            Check<span class="desk-hyphen">-</span>In
                        @endif
                    </h1>
                    <p class="desk-console__sub">
                        {{ $isCheckOutMode
                            ? 'Pindai member yang sudah di dalam untuk mencatat waktu keluar dan durasi latihan.'
                            : 'Pindai QR member untuk mencatat masuk ke lantai gym dan memotong kuota paket.' }}
                    </p>

                    {{-- Ganti mode --}}
                    <div class="desk-seg" role="tablist" aria-label="Mode scan">
                        <button
                            type="button"
                            role="tab"
                            aria-selected="{{ !$isCheckOutMode ? 'true' : 'false' }}"
                            class="desk-seg__btn {{ !$isCheckOutMode ? 'is-active' : '' }}"
                            wire:click="setMode('in')"
                        >
                            Check-in · masuk
                        </button>
                        <button
                            type="button"
                            role="tab"
                            aria-selected="{{ $isCheckOutMode ? 'true' : 'false' }}"
                            class="desk-seg__btn {{ $isCheckOutMode ? 'is-active' : '' }}"
                            wire:click="setMode('out')"
                        >
                            Check-out · keluar
                        </button>
                    </div>

                    <form wire:submit.prevent="{{ $isCheckOutMode ? 'submitCheckOut' : 'submitScan' }}">
                        <label class="desk-field-label" for="qr-token-input">Barcode atau ID member</label>

                        <div class="desk-slot {{ $errorMessage ? 'desk-slot--error' : '' }}">
                            <span class="desk-slot__laser" aria-hidden="true"></span>
                            <input
                                id="qr-token-input"
                                class="desk-input"
                                type="text"
                                wire:model.defer="qrToken"
                                autofocus
                                autocomplete="off"
                                autocorrect="off"
                                autocapitalize="off"
                                spellcheck="false"
                                placeholder="Contoh: 0034 · ORD-2026-001 · qr_token"
                                x-on:focus="$event.target.select()"
                            />
                        </div>

                        @if($errorMessage)
                            <div class="desk-error" role="alert">
                                <span class="desk-error__tag">GAGAL</span>
                                <div class="desk-error__body">
                                    <p>{{ $errorMessage }}</p>
                                    <small>Input sudah dikosongkan — scan ulang.</small>
                                </div>
                                <button type="button" class="desk-error__close" wire:click="dismissResults">tutup</button>
                            </div>
                        @endif

                        <div class="desk-actions">
                            <button
                                type="submit"
                                class="desk-btn desk-btn--signal"
                                wire:loading.attr="disabled"
                                wire:target="submitScan,submitCheckOut"
                            >
                                <span wire:loading.remove wire:target="submitScan,submitCheckOut">
                                    {{ $isCheckOutMode ? 'Proses check-out' : 'Proses check-in' }}
                                </span>
                                <span wire:loading wire:target="submitScan,submitCheckOut">
                                    <span class="desk-spin" aria-hidden="true"></span>
                                    Memproses
                                </span>
                            </button>
                            <button type="button" class="desk-btn desk-btn--ghost" wire:click="resetForm">
                                Reset <kbd>Esc</kbd>
                            </button>
                        </div>
                    </form>

                    <div class="desk-hints">
                        <span>Format diterima:</span>
                        <code>0034</code>
                        <code>ORD-2026-001</code>
                        <code>qr_token</code>
                        <span><kbd>/</kbd> fokus ke input</span>
                    </div>
                </section>

                {{-- HASIL: check-in --}}
                @if(!empty($scanResults) && $scanResults['success'] && ($scanResults['status'] ?? '') === 'success')
                <article class="desk-result">
                    <header class="desk-result__head">
                        <span class="desk-result__mark" aria-hidden="true">✓</span>
                        <div>
                            <h3>Check-in berhasil</h3>
                            <p>Member tercatat masuk ke lantai gym</p>
                        </div>
                    </header>
                    <div class="desk-result__grid">
                        <div class="desk-cell">
                            <label>Member</label>
                            <strong>{{ $scanResults['member_name'] }}</strong>
                            <small class="desk-data">ID {{ $scanResults['member_id'] }}</small>
                        </div>
                        <div class="desk-cell">
                            <label>Kelas</label>
                            <strong>{{ $scanResults['class_name'] ?? $scanResults['program'] ?? '-' }}</strong>
                            <small>
                                {{ $scanResults['package_name'] }}
                                @if(!empty($scanResults['is_exclusive']))
                                    <span class="desk-tag">Exclusive</span>
                                @endif
                            </small>
                        </div>
                        <div class="desk-cell">
                            <label>Masuk</label>
                            <strong class="desk-data">{{ $scanResults['check_in_time'] }}</strong>
                            <small>
                                {{ $scanResults['check_in_date'] }}
                                @if(!empty($scanResults['schedule_time']) && $scanResults['schedule_time'] !== '-')
                                    · kelas {{ $scanResults['schedule_time'] }}
                                @endif
                            </small>
                        </div>
                        <div class="desk-cell {{ !empty($scanResults['is_exclusive']) ? 'desk-cell--signal' : 'desk-cell--court' }}">
                            <label>Sisa kuota</label>
                            @if(!empty($scanResults['is_exclusive']))
                                <strong class="desk-big">&infin;</strong>
                                <small>Paket exclusive — kuota tidak berkurang</small>
                            @else
                                <strong class="desk-big">{{ $scanResults['remaining_quota'] }}<small style="display:inline;font-size:1rem;"> / {{ $scanResults['total_quota'] }}</small></strong>
                                @if(($scanResults['total_quota'] ?? 0) > 0)
                                    <small>{{ round(($scanResults['remaining_quota'] / $scanResults['total_quota']) * 100) }}% tersisa</small>
                                @endif
                            @endif
                        </div>
                    </div>
                    <footer class="desk-result__foot">
                        <p>Input sudah dikosongkan untuk scan berikutnya.</p>
                        <button type="button" class="desk-btn desk-btn--ink" style="height:44px;min-width:0;padding:0 18px;font-size:12.5px;" wire:click="dismissResults">
                            Scan berikutnya →
                        </button>
                    </footer>
                </article>
                @endif

                {{-- HASIL: member sedang latihan --}}
                @if(!empty($scanResults) && $scanResults['success'] && ($scanResults['status'] ?? '') === 'already_active')
                <article class="desk-result">
                    <header class="desk-result__head">
                        <span class="desk-result__mark" aria-hidden="true">!</span>
                        <div>
                            <h3>Member sedang latihan</h3>
                            <p>Sudah check-in, belum check-out</p>
                        </div>
                    </header>
                    <div class="desk-result__grid">
                        <div class="desk-cell">
                            <label>Member</label>
                            <strong>{{ $scanResults['member_name'] }}</strong>
                            <small class="desk-data">ID {{ $scanResults['member_id'] }}</small>
                        </div>
                        <div class="desk-cell">
                            <label>Kelas</label>
                            <strong>{{ $scanResults['class_name'] }}</strong>
                            <small>Sudah {{ $scanResults['elapsed_minutes'] ?? '-' }} menit di dalam</small>
                        </div>
                        <div class="desk-cell">
                            <label>Masuk</label>
                            <strong class="desk-data">{{ $scanResults['check_in_time'] }}</strong>
                        </div>
                        <div class="desk-cell desk-cell--signal">
                            <label>Auto-checkout</label>
                            <strong class="desk-big">{{ $scanResults['auto_checkout_in'] ?? '-' }}<small style="display:inline;font-size:1rem;"> mnt</small></strong>
                            <small>Jika tidak checkout manual</small>
                        </div>
                    </div>
                    <footer class="desk-result__foot">
                        <p>Scan ulang juga akan mencatat check-out.</p>
                        <button type="button" class="desk-btn desk-btn--ink" style="height:44px;min-width:0;padding:0 18px;font-size:12.5px;" wire:click="dismissResults">
                            Mengerti →
                        </button>
                    </footer>
                </article>
                @endif

                {{-- HASIL: auto-checkout --}}
                @if(!empty($scanResults) && $scanResults['success'] && ($scanResults['status'] ?? '') === 'auto_checkout')
                <article class="desk-result">
                    <header class="desk-result__head">
                        <span class="desk-result__mark" aria-hidden="true">✓</span>
                        <div>
                            <h3>Auto-checkout — sesi berakhir</h3>
                            <p>Member ditutup otomatis melewati batas sesi</p>
                        </div>
                    </header>
                    <div class="desk-result__grid">
                        <div class="desk-cell">
                            <label>Member</label>
                            <strong>{{ $scanResults['member_name'] }}</strong>
                        </div>
                        <div class="desk-cell">
                            <label>Kelas</label>
                            <strong>{{ $scanResults['class_name'] ?? '-' }}</strong>
                        </div>
                        <div class="desk-cell">
                            <label>Masuk</label>
                            <strong class="desk-data">{{ $scanResults['check_in_time'] }}</strong>
                        </div>
                        <div class="desk-cell">
                            <label>Keluar (auto)</label>
                            <strong class="desk-data">{{ $scanResults['auto_checkout_time'] }}</strong>
                        </div>
                    </div>
                    <div class="desk-result__duration">
                        <label>Durasi latihan</label>
                        <b>{{ $scanResults['duration'] }}</b>
                    </div>
                    <footer class="desk-result__foot">
                        <p>Sesi ditutup otomatis, tidak perlu scan lagi.</p>
                        <button type="button" class="desk-btn desk-btn--ink" style="height:44px;min-width:0;padding:0 18px;font-size:12.5px;" wire:click="dismissResults">
                            Scan berikutnya →
                        </button>
                    </footer>
                </article>
                @endif

                {{-- HASIL: check-out --}}
                @if(!empty($checkOutResults) && $checkOutResults['success'])
                <article class="desk-result">
                    <header class="desk-result__head">
                        <span class="desk-result__mark" aria-hidden="true">✓</span>
                        <div>
                            <h3>Check-out berhasil</h3>
                            <p>Latihan member sudah ditutup</p>
                        </div>
                    </header>
                    <div class="desk-result__grid">
                        <div class="desk-cell">
                            <label>Member</label>
                            <strong>{{ $checkOutResults['member_name'] }}</strong>
                            <small class="desk-data">ID {{ $checkOutResults['member_id'] }}</small>
                        </div>
                        <div class="desk-cell">
                            <label>Paket</label>
                            <strong>{{ $checkOutResults['package_name'] }}</strong>
                            <small>{{ $checkOutResults['program'] }}</small>
                        </div>
                        <div class="desk-cell">
                            <label>Masuk</label>
                            <strong class="desk-data">{{ $checkOutResults['check_in_time'] }}</strong>
                        </div>
                        <div class="desk-cell">
                            <label>Keluar</label>
                            <strong class="desk-data">{{ $checkOutResults['check_out_time'] }}</strong>
                        </div>
                    </div>
                    <div class="desk-result__duration">
                        <label>Durasi latihan</label>
                        <b>{{ $checkOutResults['duration'] }}</b>
                        <p class="desk-duration__meta">{{ $checkOutResults['duration_minutes'] }} menit · kuota sisa {{ $checkOutResults['remaining_quota'] }}/{{ $checkOutResults['total_quota'] }}</p>
                    </div>
                    <footer class="desk-result__foot">
                        <p>Notifikasi durasi dikirim ke member via WhatsApp.</p>
                        <button type="button" class="desk-btn desk-btn--ink" style="height:44px;min-width:0;padding:0 18px;font-size:12.5px;" wire:click="dismissResults">
                            Scan berikutnya →
                        </button>
                    </footer>
                </article>
                @endif

                {{-- RIWAYAT --}}
                <section class="desk-panel" aria-label="Riwayat scan hari ini">
                    <header class="desk-panel__head">
                        <h3>Riwayat hari ini</h3>
                        <span class="desk-panel__meta">{{ now()->format('d/m/Y') }} · {{ count($recentScans) }} entri</span>
                    </header>
                    <div class="desk-panel__body">
                        @if(!empty($recentScans))
                            <ul class="desk-rows">
                                @foreach($recentScans as $scan)
                                <li>
                                    <span class="desk-rows__time">{{ $scan['time'] }}</span>
                                    <span class="desk-rows__who">
                                        <strong>{{ $scan['member'] }}</strong>
                                        <span>
                                            {{ $scan['class_name'] ?? '-' }}
                                            @if($scan['status'] === 'completed')
                                                · keluar {{ $scan['check_out_time'] }} · {{ $scan['duration'] }}
                                            @endif
                                        </span>
                                    </span>
                                    <span class="desk-status {{ $scan['status'] === 'completed' ? 'desk-status--done' : 'desk-status--in' }}">
                                        {{ $scan['status'] === 'completed' ? 'Selesai' : 'Di dalam' }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="desk-empty">
                                <strong>Belum ada scan hari ini</strong>
                                <span>Aktivitas muncul di sini sejak scan pertama.</span>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- ============ KANAN: papan okupansi + aturan ============ --}}
            <aside class="desk-side">

                {{-- PAPAN: DI GYM SEKARANG --}}
                <section class="desk-board" aria-label="Member di gym sekarang">
                    <header class="desk-board__head">
                        <div>
                            <h3>Di gym sekarang</h3>
                            <p>Belum check-out</p>
                        </div>
                        <span class="desk-board__count">{{ $insideMembers->count() }}</span>
                    </header>

                    @if($insideMembers->isNotEmpty())
                        <ul class="desk-board__rows">
                            @foreach($insideMembers as $member)
                            <li class="desk-board__row">
                                <span class="desk-board__time">{{ $member['time'] }}</span>
                                <span class="desk-board__who">
                                    <strong>{{ $member['member'] }}</strong>
                                    <span>{{ $member['class_name'] ?? '-' }}</span>
                                </span>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="desk-board__empty">
                            <strong>Ruang kosong</strong>
                            <span>Belum ada yang check-in. Scan member pertama.</span>
                        </div>
                    @endif

                    <footer class="desk-board__foot">
                        <div class="desk-board__stat">
                            <b>{{ $todayStats['total'] }}</b>
                            <span>Total scan</span>
                        </div>
                        <div class="desk-board__stat desk-board__stat--ok">
                            <b>{{ $todayStats['success'] }}</b>
                            <span>Berhasil</span>
                        </div>
                        <div class="desk-board__stat desk-board__stat--bad">
                            <b>{{ $todayStats['error'] }}</b>
                            <span>Gagal</span>
                        </div>
                    </footer>
                </section>

                {{-- ATURAN --}}
                <section class="desk-panel" aria-label="Aturan">
                    <header class="desk-panel__head">
                        <h3>{{ $isCheckOutMode ? 'Aturan check-out' : 'Aturan check-in' }}</h3>
                    </header>
                    <ul class="desk-rules">
                        @if(!$isCheckOutMode)
                            <li>Scan QR member atau ketik ID, lalu tekan Enter.</li>
                            <li>Check-in dibuka &plusmn;60 menit dari jam kelas.</li>
                            <li>Satu check-in per kelas per hari — scan ulang ditolak.</li>
                            <li>Kuota paket berkurang tiap check-in, kecuali paket exclusive.</li>
                            <li>Member dengan 2+ kelas hari ini diminta memilih kelas dulu.</li>
                            <li>WhatsApp notifikasi terkirim otomatis ke member.</li>
                        @else
                            <li>Scan member yang masih di dalam — atau biarkan auto-checkout.</li>
                            <li>Durasi latihan dihitung otomatis dari jam masuk.</li>
                            <li>WhatsApp berisi durasi dikirim ke member setelah keluar.</li>
                        @endif
                    </ul>
                    <div class="desk-keys">
                        <span><kbd>Enter</kbd> proses</span>
                        <span><kbd>Esc</kbd> reset</span>
                        <span><kbd>/</kbd> fokus input</span>
                    </div>
                </section>
            </aside>
        </div>

        {{-- ===== MODAL PILIH KELAS ===== --}}
        @if($showScheduleSelector && !empty($todaySchedules))
        <div class="desk-modal" role="dialog" aria-modal="true" aria-labelledby="desk-pick-title">
            <div class="desk-modal__panel">
                <header class="desk-modal__head">
                    <div>
                        <h3 id="desk-pick-title">Pilih kelas</h3>
                        <p>{{ count($todaySchedules) }} booking hari ini — kelas siap check-in ditandai kuning.</p>
                    </div>
                    <span class="desk-chip desk-chip--signal">Check-in</span>
                </header>

                <div class="desk-modal__body">
                    @foreach($todaySchedules as $schedule)
                    <button
                        type="button"
                        class="desk-pick {{ ($schedule['is_within_window'] ?? false) ? 'desk-pick--window' : '' }}"
                        wire:click="confirmScheduleSelection({{ $schedule['schedule_id'] }})"
                    >
                        <div class="desk-pick__top">
                            <strong>{{ $schedule['class_name'] }}</strong>
                            @if($schedule['is_within_window'] ?? false)
                                <span class="desk-tag">Siap check-in</span>
                            @else
                                <span class="desk-tag desk-tag--outline">Di luar waktu</span>
                            @endif
                            @if($schedule['is_exclusive'] ?? false)
                                <span class="desk-tag">Exclusive</span>
                            @endif
                        </div>
                        <div class="desk-pick__grid">
                            <span><b>{{ $schedule['class_time'] }}</b> jam kelas</span>
                            <span>{{ $schedule['instructor'] }}</span>
                            <span>{{ $schedule['location'] }}</span>
                            <span><b>{{ $schedule['booked'] }}/{{ $schedule['capacity'] }}</b> peserta</span>
                        </div>
                    </button>
                    @endforeach
                </div>

                <footer class="desk-modal__foot">
                    <span>Tekan <kbd>Esc</kbd> untuk batal</span>
                    <button type="button" class="desk-btn desk-btn--outline-ink" style="height:44px;padding:0 18px;font-size:12.5px;" wire:click="resetForm">
                        Batal / scan ulang
                    </button>
                </footer>
            </div>
        </div>
        @endif
    </div>
</x-filament::page>
