<?php

return [
    'currency' => 'EUR',
    'base_amount' => 50.00,
    'vat_rate' => 0.21,
    'service_name' => 'Izmaksu aprēķins un tāmes sagatavošana',
    'logo_path' => env('INVOICE_LOGO_PATH', 'images/dist/logo-dark.svg'),

    'company' => [
        'name' => env('INVOICE_COMPANY_NAME', env('APP_NAME', 'Anselat')),
        'registration_number' => env('INVOICE_COMPANY_REG_NUMBER', ''),
        'vat_number' => env('INVOICE_COMPANY_VAT_NUMBER', ''),
        'iban' => env('INVOICE_COMPANY_IBAN', ''),
        'swift' => env('INVOICE_COMPANY_SWIFT', ''),
        'bank' => env('INVOICE_COMPANY_BANK', ''),
        'address' => env('INVOICE_COMPANY_ADDRESS', ''),
        'email' => env('INVOICE_COMPANY_EMAIL', ''),
        'phone' => env('INVOICE_COMPANY_PHONE', ''),
    ],
];

