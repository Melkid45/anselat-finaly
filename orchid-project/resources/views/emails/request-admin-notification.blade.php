<!doctype html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <title>Jauns pieprasījums</title>
</head>
<body style="margin:0;padding:24px;background:#f4f6ef;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:#363B23;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;margin:0 auto;background:#ffffff;border:1px solid #e3e7d8;border-radius:12px;overflow:hidden;">
    <tr>
        <td style="background:#B8F13C;padding:18px 24px;">
            @if(!empty($logoDataUri))
                <img src="{{ $logoDataUri }}" alt="Anselat" style="max-height:28px;display:block;margin-bottom:8px;">
            @endif
            <div style="font-size:20px;font-weight:700;">Jauns pieprasījums no formas</div>
            <div style="font-size:13px;opacity:.8;">{{ now()->format('d.m.Y H:i') }}</div>
        </td>
    </tr>
    <tr>
        <td style="padding:20px 24px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-weight:600;">Adrese</td>
                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;text-align:right;">{{ $payload['address'] }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;font-weight:600;">Tālrunis</td>
                    <td style="padding:10px 0;border-bottom:1px solid #e5e7eb;text-align:right;">{{ $payload['phone'] }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;font-weight:600;">WhatsApp</td>
                    <td style="padding:10px 0;text-align:right;">{{ !empty($payload['whatsapp']) ? 'Jā' : 'Nē' }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
