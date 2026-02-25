<!doctype html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 32px; }
        body { font-family: DejaVu Sans, sans-serif; color: #2f3523; font-size: 12px; line-height: 1.45; }
        .head { border-bottom: 2px solid #b8f13c; padding-bottom: 14px; margin-bottom: 18px; }
        .logo { max-height: 40px; margin-bottom: 12px; }
        .title { font-size: 20px; font-weight: 700; margin: 0; }
        .muted { color: #6b7280; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .grid td { padding: 4px 0; vertical-align: top; }
        .box { border: 1px solid #d9dfc7; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .table th, .table td { border-bottom: 1px solid #e5e7eb; padding: 8px 6px; text-align: left; }
        .table .r { text-align: right; }
        .total td { font-weight: 700; border-bottom: 0; padding-top: 12px; }
        .small { font-size: 10px; color: #6b7280; margin-top: 14px; }
    </style>
</head>
<body>
    <div class="head">
        @if(!empty($invoice['logo_data_uri']))
            <img src="{{ $invoice['logo_data_uri'] }}" class="logo" alt="logo">
        @endif
        <p class="title">Rēķins Nr. {{ $invoice['number'] }}</p>
        <p class="muted" style="margin:6px 0 0;">Datums: {{ $invoice['date'] }} | Apmaksas termiņš: {{ $invoice['due_date'] }}</p>
    </div>

    <table class="grid">
        <tr>
            <td width="50%">
                <div class="box">
                    <strong>Piegādātājs</strong><br>
                    {{ $invoice['company']['name'] ?: '-' }}<br>
                    @if(!empty($invoice['company']['registration_number'])) Reģ. Nr.: {{ $invoice['company']['registration_number'] }}<br> @endif
                    @if(!empty($invoice['company']['vat_number'])) PVN Nr.: {{ $invoice['company']['vat_number'] }}<br> @endif
                    @if(!empty($invoice['company']['address'])) {{ $invoice['company']['address'] }}<br> @endif
                    @if(!empty($invoice['company']['email'])) {{ $invoice['company']['email'] }}<br> @endif
                    @if(!empty($invoice['company']['phone'])) {{ $invoice['company']['phone'] }} @endif
                </div>
            </td>
            <td width="50%" style="padding-left:10px;">
                <div class="box">
                    <strong>Klients</strong><br>
                    {{ $invoice['customer']['full_name'] ?? '-' }}<br>
                    {{ $invoice['customer']['email'] ?? '-' }}<br>
                    {{ $invoice['customer']['address'] ?? '-' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Pakalpojums</th>
                <th class="r">Daudzums</th>
                <th class="r">Cena</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice['service_name'] }}</td>
                <td class="r">1</td>
                <td class="r">{{ number_format((float) $invoice['amount_base'], 2, '.', ' ') }} {{ $invoice['currency'] }}</td>
            </tr>
            <tr>
                <td>PVN {{ number_format((float) $invoice['vat_rate_percent'], 0) }}%</td>
                <td class="r">-</td>
                <td class="r">{{ number_format((float) $invoice['amount_vat'], 2, '.', ' ') }} {{ $invoice['currency'] }}</td>
            </tr>
            <tr class="total">
                <td>Kopā apmaksai</td>
                <td></td>
                <td class="r">{{ number_format((float) $invoice['amount_total'], 2, '.', ' ') }} {{ $invoice['currency'] }}</td>
            </tr>
        </tbody>
    </table>

    <div class="box" style="margin-top:14px;">
        <strong>Maksājuma rekvizīti</strong><br>
        @if(!empty($invoice['company']['bank'])) Banka: {{ $invoice['company']['bank'] }}<br> @endif
        @if(!empty($invoice['company']['iban'])) IBAN: {{ $invoice['company']['iban'] }}<br> @endif
        @if(!empty($invoice['company']['swift'])) SWIFT: {{ $invoice['company']['swift'] }} @endif
    </div>

    <p class="small">Rēķins ir sagatavots automātiski. Pēc apmaksas uzsāksim pieprasījuma izskatīšanu.</p>
</body>
</html>
