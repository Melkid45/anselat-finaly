<!doctype html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Rēķins {{ $invoiceNumber }}</title>
</head>
<body style="margin:0;padding:24px;background:#f4f6ef;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:#363B23;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #e3e7d8;border-radius:12px;overflow:hidden;">
    <tr>
        <td style="background:#B8F13C;padding:20px 24px;">
            @if(!empty($invoice['logo_data_uri']))
                <img src="{{ $invoice['logo_data_uri'] }}" alt="Anselat" style="max-height:30px;display:block;margin-bottom:10px;">
            @endif
            <div style="font-weight:700;font-size:20px;">Rēķins Nr. {{ $invoiceNumber }}</div>
        </td>
    </tr>
    <tr>
        <td style="padding:24px;line-height:1.6;font-size:15px;">
            <p style="margin:0 0 12px;">Sveiki, {{ $payload['full_name'] }}!</p>
            <p style="margin:0 0 12px;">Paldies par pieprasījumu. Pievienojām rēķinu PDF formātā par izmaksu aprēķina un tāmes sagatavošanu.</p>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:16px 0;border-collapse:collapse;">
                <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;">Pakalpojums</td>
                    <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;text-align:right;">{{ $invoice['service_name'] }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;">Summa bez PVN</td>
                    <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;text-align:right;">{{ number_format((float) $invoice['amount_base'], 2, '.', ' ') }} {{ $invoice['currency'] }}</td>
                </tr>
                <tr>
                    <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;">PVN {{ number_format((float) $invoice['vat_rate_percent'], 0) }}%</td>
                    <td style="padding:8px 0;border-bottom:1px solid #e5e7eb;text-align:right;">{{ number_format((float) $invoice['amount_vat'], 2, '.', ' ') }} {{ $invoice['currency'] }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0 0;font-weight:700;">Kopā apmaksai</td>
                    <td style="padding:10px 0 0;text-align:right;font-weight:700;">{{ number_format((float) $invoice['amount_total'], 2, '.', ' ') }} {{ $invoice['currency'] }}</td>
                </tr>
            </table>
            <p style="margin:0 0 8px;">Apmaksas termiņš: {{ $invoice['due_date'] }}.</p>
            <p style="margin:0;color:#6b7280;font-size:13px;">Ja nolemsiet noformēt pasūtījumu, šī summa tiks atskaitīta no kopējās cenas.</p>
        </td>
    </tr>
</table>
</body>
</html>
