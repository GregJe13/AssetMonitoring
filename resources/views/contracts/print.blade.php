<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contract Report — {{ $contract->no_pks ?? $contract->no_bak ?? 'Contract #'.$contract->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Instrument Sans', 'Segoe UI', sans-serif;
            color: #1f2937;
            background: #fff;
            font-size: 11pt;
            line-height: 1.5;
            padding: 0;
        }

        .print-container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20mm 15mm;
        }

        /* ── Header ── */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .report-header .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22pt;
            font-weight: 700;
            color: #4f46e5;
        }
        .report-header .brand svg { width: 32px; height: 32px; }
        .report-header .meta { text-align: right; font-size: 9pt; color: #6b7280; }
        .report-header .meta .title {
            font-size: 13pt;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        /* ── Status Badge ── */
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 9pt;
            font-weight: 600;
        }
        .badge-active { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .badge-expired { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-paid { background: #dcfce7; color: #15803d; }
        .badge-overdue { background: #fef2f2; color: #b91c1c; }
        .badge-pending { background: #fefce8; color: #a16207; }

        /* ── Section ── */
        .section {
            margin-bottom: 24px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 11pt;
            font-weight: 700;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid #e5e7eb;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        /* ── Detail Grid ── */
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 32px;
        }
        .detail-grid .full { grid-column: 1 / -1; }
        .detail-item label {
            display: block;
            font-size: 8.5pt;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }
        .detail-item .value {
            font-size: 10.5pt;
            color: #111827;
        }
        .detail-item .value.mono { font-family: 'Consolas', 'Courier New', monospace; }
        .detail-item .value.large { font-size: 14pt; font-weight: 700; }
        .detail-item .sub { font-size: 8pt; color: #9ca3af; margin-top: 1px; }

        /* ── Tables ── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        thead th {
            background: #f9fafb;
            text-align: left;
            padding: 8px 10px;
            font-size: 8pt;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 2px solid #e5e7eb;
        }
        thead th.text-right { text-align: right; }
        thead th.text-center { text-align: center; }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }
        tbody td.text-right { text-align: right; }
        tbody td.text-center { text-align: center; }
        tbody tr.overdue-row { background: #fff5f5; }

        /* ── Asset Cards ── */
        .asset-list { list-style: none; }
        .asset-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .asset-list li:last-child { border-bottom: none; }
        .asset-name { font-weight: 600; font-size: 10pt; }
        .asset-code { font-size: 8.5pt; color: #6b7280; }
        .asset-area {
            font-size: 9pt;
            background: #eef2ff;
            color: #4338ca;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 500;
        }

        /* ── Amendment List ── */
        .amendment-item {
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .amendment-item:last-child { border-bottom: none; }
        .amendment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }
        .amendment-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #eef2ff;
            color: #4338ca;
            font-size: 8.5pt;
            font-weight: 700;
            margin-right: 6px;
        }
        .amendment-details { font-size: 9pt; color: #6b7280; margin-left: 28px; }
        .amendment-assets {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 4px;
            margin-left: 28px;
        }
        .amendment-asset-tag {
            font-size: 8pt;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 1px 6px;
            border-radius: 4px;
            border: 1px solid #bfdbfe;
        }

        /* ── Footer ── */
        .report-footer {
            margin-top: 32px;
            padding-top: 12px;
            border-top: 1.5px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #9ca3af;
        }

        /* ── Print-only actions bar (hidden when printing) ── */
        .actions-bar {
            position: fixed;
            top: 0; left: 0; right: 0;
            background: #4f46e5;
            color: #fff;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .actions-bar .info { font-size: 10pt; font-weight: 500; }
        .actions-bar .btns { display: flex; gap: 8px; }
        .actions-bar button, .actions-bar a {
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 9.5pt;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: background 0.15s;
        }
        .btn-print { background: #fff; color: #4f46e5; }
        .btn-print:hover { background: #e0e7ff; }
        .btn-back { background: rgba(255,255,255,0.15); color: #fff; }
        .btn-back:hover { background: rgba(255,255,255,0.25); }

        /* ── Print Media ── */
        @media print {
            body { padding: 0; }
            .print-container { padding: 10mm 12mm; max-width: none; }
            .actions-bar { display: none !important; }
            .section { page-break-inside: avoid; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; }
            thead { display: table-header-group; }
            @page {
                size: A4;
                margin: 10mm 8mm;
            }
        }

        @media screen {
            .print-container { margin-top: 56px; }
        }
    </style>
</head>
<body>

    {{-- Toolbar (hidden when printing) --}}
    <div class="actions-bar">
        <div class="info">📄 Contract Report Preview</div>
        <div class="btns">
            <a href="{{ route('contracts.show', $contract) }}" class="btn-back">← Back</a>
            <button onclick="window.print()" class="btn-print">🖨️ Print / Save PDF</button>
        </div>
    </div>

    <div class="print-container">

        {{-- ═══════ HEADER ═══════ --}}
        <div class="report-header">
            <div class="brand">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                INTI
            </div>
            <div class="meta">
                <div class="title">Contract Details Report</div>
                <div>Tenant: <strong>{{ $contract->tenant->name }}</strong></div>
                <div>
                    Status:
                    <span class="badge {{ $contract->is_expired ? 'badge-expired' : 'badge-active' }}">
                        {{ $contract->is_expired ? 'Expired' : ucfirst($contract->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ═══════ CONTRACT TERMS ═══════ --}}
        <div class="section">
            <div class="section-title">Contract Terms</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>No. PKS</label>
                    <div class="value mono">{{ $contract->no_pks ?? '-' }}</div>
                    <div class="sub">Date: {{ $contract->date_pks ? $contract->date_pks->format('d M Y') : '-' }}</div>
                </div>
                <div class="detail-item">
                    <label>No. BAK</label>
                    <div class="value mono">{{ $contract->no_bak ?? '-' }}</div>
                    <div class="sub">Date: {{ $contract->date_bak ? $contract->date_bak->format('d M Y') : '-' }}</div>
                </div>
                <div class="detail-item">
                    <label>Start Date</label>
                    <div class="value">{{ $contract->start_date->format('d F Y') }}</div>
                </div>
                <div class="detail-item">
                    <label>End Date</label>
                    <div class="value">{{ $contract->end_date->format('d F Y') }}</div>
                    @if($contract->is_expired)
                        <div class="sub" style="color: #b91c1c;">{{ $contract->days_expired }} hari sejak berakhir</div>
                    @else
                        <div class="sub">{{ $contract->remaining_days }} hari tersisa</div>
                    @endif
                </div>
                <div class="detail-item">
                    <label>Total Rental Value</label>
                    <div class="value large">Rp {{ number_format($contract->total_rental_value) }}</div>
                </div>
                @if($contract->security_deposit > 0)
                <div class="detail-item">
                    <label>Security Deposit</label>
                    <div class="value" style="font-weight:600;">Rp {{ number_format($contract->security_deposit) }}</div>
                </div>
                @endif
                <div class="detail-item full">
                    <label>Payment Terms</label>
                    <div class="value">
                        @if($contract->is_upfront)
                            Full Payment Upfront
                        @else
                            Every {{ $contract->payment_interval_value }} {{ Str::plural($contract->payment_interval_unit, $contract->payment_interval_value) }}
                        @endif
                    </div>
                </div>
                @if(!$contract->is_upfront && $contract->payment_start_date)
                <div class="detail-item full">
                    <label>Payment Start Date</label>
                    <div class="value">
                        {{ $contract->payment_start_date->format('d F Y') }}
                        @if($contract->payment_start_date->ne($contract->start_date))
                            <span class="sub" style="margin-left:4px;">(berbeda dengan tanggal mulai kontrak)</span>
                        @endif
                    </div>
                </div>
                @endif
                <div class="detail-item">
                    <label>Perwakilan Pihak Pertama</label>
                    <div class="value">{{ $contract->pihak_pertama }}</div>
                </div>
                <div class="detail-item">
                    <label>Perwakilan Pihak Kedua</label>
                    <div class="value">{{ $contract->pihak_kedua }}</div>
                </div>
            </div>
        </div>

        {{-- ═══════ RENTED ASSETS ═══════ --}}
        @php
            $rentedAssets = $contract->assets->filter(fn($a) => $a->pivot->rented_area_sqm > 0);
        @endphp
        <div class="section">
            <div class="section-title">Rented Assets ({{ $rentedAssets->count() }} units)</div>
            @if($rentedAssets->count() > 0)
            <ul class="asset-list">
                @foreach($rentedAssets as $asset)
                <li>
                    <div>
                        <span class="asset-name">{{ $asset->name }}</span>
                        <span class="asset-code">{{ $asset->id_gedung }}</span>
                    </div>
                    <div>
                        <span class="asset-area">{{ number_format($asset->pivot->rented_area_sqm, 0) }} m²</span>
                        <span style="font-size:8pt;color:#9ca3af;margin-left:4px;">of {{ number_format($asset->area_sqm, 0) }} m²</span>
                    </div>
                </li>
                @endforeach
            </ul>
            @else
            <p style="color:#9ca3af;font-size:9.5pt;">No assets attached.</p>
            @endif
        </div>

        {{-- ═══════ PAYMENT SCHEDULE ═══════ --}}
        <div class="section">
            <div class="section-title">Payment Schedule</div>
            @if($contract->payments->count() > 0)
            @php
                $totalPaid = $contract->payments->where('payment_status', 'paid')->count();
                $totalPayments = $contract->payments->count();
            @endphp
            <div style="font-size:9pt;color:#6b7280;margin-bottom:8px;">
                {{ $totalPaid }} of {{ $totalPayments }} payments completed
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Due Date</th>
                        <th class="text-right">Amount</th>
                        <th class="text-center">Status</th>
                        <th>Paid Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contract->payments as $payment)
                    <tr class="{{ $payment->payment_status == 'overdue' ? 'overdue-row' : '' }}">
                        <td>#{{ $payment->period_number }}</td>
                        <td>{{ $payment->due_date->format('d M Y') }}</td>
                        <td class="text-right">Rp {{ number_format($payment->amount_due) }}</td>
                        <td class="text-center">
                            <span class="badge {{ $payment->payment_status == 'paid' ? 'badge-paid' : ($payment->payment_status == 'overdue' ? 'badge-overdue' : 'badge-pending') }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </td>
                        <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color:#9ca3af;font-size:9.5pt;">No payments scheduled.</p>
            @endif
        </div>

        {{-- ═══════ AMENDMENTS ═══════ --}}
        @if($contract->amendments->count() > 0)
        <div class="section">
            <div class="section-title">Amendments ({{ $contract->amendments->count() }})</div>
            @foreach($contract->amendments->sortByDesc('amendment_number') as $amendment)
            <div class="amendment-item">
                <div class="amendment-header">
                    <div>
                        <span class="amendment-number">{{ $amendment->amendment_number }}</span>
                        <span style="font-weight:600;font-size:10pt;">{{ $amendment->no_amendment ?? 'AMD #' . $amendment->amendment_number }}</span>
                    </div>
                    <span class="badge {{ $amendment->is_expired ? 'badge-expired' : 'badge-active' }}">
                        {{ $amendment->is_expired ? 'Expired' : ucfirst($amendment->status) }}
                    </span>
                </div>
                <div class="amendment-details">
                    {{ $amendment->new_start_date->format('d M Y') }} — {{ $amendment->new_end_date->format('d M Y') }}
                    &nbsp;·&nbsp; Rp {{ number_format($amendment->total_rental_value) }}
                </div>
                @if($amendment->assets->count() > 0)
                <div class="amendment-assets">
                    @foreach($amendment->assets as $asset)
                    <span class="amendment-asset-tag">{{ $asset->name }} ({{ number_format($asset->pivot->rented_area_sqm, 0) }} m²)</span>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        {{-- ═══════ FOOTER ═══════ --}}
        <div class="report-footer">
            <span>Generated on {{ now()->format('d F Y, H:i') }} WIB</span>
            <span>INTI Asset Monitoring System</span>
        </div>

    </div>

    <script>
        // Auto-trigger print dialog when loaded
        window.addEventListener('load', function() {
            // Small delay to ensure styles are rendered
            setTimeout(function() { window.print(); }, 500);
        });
    </script>
</body>
</html>
