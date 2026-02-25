<?php

namespace App\Http\Controllers;

use App\Mail\CalculatorAdminNotificationMail;
use App\Mail\CalculatorClientInvoiceMail;
use App\Mail\RequestAdminNotificationMail;
use App\Models\Contact;
use App\Support\SimpleInvoicePdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;

class FormController extends Controller
{
    public function submitRequest(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'address' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:50'],
                'whatsapp' => ['nullable', 'in:1'],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
                'message' => 'Lūdzu aizpildiet obligātos laukus.',
            ], 422);
        }

        $adminEmail = (string) config('mail.admin_address', config('mail.from.address'));

        try {
            if ($adminEmail !== '') {
                Mail::to($adminEmail)->send(new RequestAdminNotificationMail([
                    'address' => $validated['address'],
                    'phone' => $validated['phone'],
                    'whatsapp' => isset($validated['whatsapp']) ? 1 : 0,
                ]));
            }
        } catch (\Throwable $e) {
            Log::error('Request form mail send failed', [
                'error' => $e->getMessage(),
                'payload' => $validated,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Neizdevās nosūtīt pieprasījumu. Lūdzu, mēģiniet vēlreiz.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pieprasījums nosūtīts veiksmīgi!',
        ]);
    }

    public function submitCalculator(Request $request): JsonResponse
    {
        try {
            $messages = [
                'category.required' => __('ui.calc.validation.category.required'),
                'category.max' => __('ui.calc.validation.category.max'),
                'width.required' => __('ui.calc.validation.width.required'),
                'width.numeric' => __('ui.calc.validation.width.numeric'),
                'width.min' => __('ui.calc.validation.width.min'),
                'height.required' => __('ui.calc.validation.height.required'),
                'height.numeric' => __('ui.calc.validation.height.numeric'),
                'height.min' => __('ui.calc.validation.height.min'),
                'depth.required' => __('ui.calc.validation.depth.required'),
                'depth.numeric' => __('ui.calc.validation.depth.numeric'),
                'depth.min' => __('ui.calc.validation.depth.min'),
                'full_name.required' => __('ui.calc.validation.full_name.required'),
                'full_name.max' => __('ui.calc.validation.full_name.max'),
                'email.required' => __('ui.calc.validation.email.required'),
                'email.email' => __('ui.calc.validation.email.email'),
                'email.max' => __('ui.calc.validation.email.max'),
                'address.required' => __('ui.calc.validation.address.required'),
                'address.max' => __('ui.calc.validation.address.max'),
            ];

            $validated = $request->validate([
                'category' => ['required', 'string', 'max:255'],
                'width' => ['required', 'numeric', 'min:0.1'],
                'height' => ['required', 'numeric', 'min:0.1'],
                'depth' => ['required', 'numeric', 'min:0.1'],
                'full_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'address' => ['required', 'string', 'max:255'],
            ], $messages);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        }

        $invoiceNumber = $this->generateInvoiceNumber();
        $invoice = $this->buildInvoiceData($validated, $invoiceNumber);
        $invoicePdf = $this->buildInvoicePdf($invoice);

        $adminEmail = (string) config('mail.admin_address', config('mail.from.address'));
        $customerEmail = (string) $validated['email'];

        try {
            Mail::to($customerEmail)->send(new CalculatorClientInvoiceMail($validated, $invoiceNumber, $invoicePdf, $invoice));

            if ($adminEmail !== '') {
                Mail::to($adminEmail)->send(new CalculatorAdminNotificationMail($validated, $invoiceNumber));
            }
        } catch (\Throwable $e) {
            Log::error('Calculator mail send failed', [
                'error' => $e->getMessage(),
                'invoice_number' => $invoiceNumber,
                'payload' => $validated,
            ]);

            return response()->json([
                'status' => 'error',
                'errors' => [
                    'form' => [__('ui.calc.validation.form.send_failed')],
                ],
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('ui.calc.validation.form.success'),
        ]);
    }

    private function generateInvoiceNumber(): string
    {
        return now()->format('YmdHis').'-'.strtoupper((string) str()->random(4));
    }

    private function buildInvoiceData(array $payload, string $invoiceNumber): array
    {
        $baseAmount = (float) config('invoice.base_amount', 50.00);
        $vatRate = (float) config('invoice.vat_rate', 0.21);
        $vatAmount = round($baseAmount * $vatRate, 2);
        $total = round($baseAmount + $vatAmount, 2);

        $company = (array) config('invoice.company', []);
        $contact = Contact::query()->first();
        $logoDataUri = $this->buildLogoDataUri((string) config('invoice.logo_path', ''));

        $companyData = [
            'name' => (string) ($company['name'] ?? config('app.name', 'Anselat')),
            'registration_number' => (string) ($company['registration_number'] ?? ''),
            'vat_number' => (string) ($company['vat_number'] ?? ''),
            'iban' => (string) ($company['iban'] ?? ''),
            'swift' => (string) ($company['swift'] ?? ''),
            'bank' => (string) ($company['bank'] ?? ''),
            'address' => (string) ($company['address'] ?? $contact?->address ?? ''),
            'email' => (string) ($company['email'] ?? $contact?->email ?? ''),
            'phone' => (string) ($company['phone'] ?? $contact?->phone ?? ''),
        ];

        return [
            'number' => $invoiceNumber,
            'date' => now()->format('d.m.Y'),
            'due_date' => now()->addDays(5)->format('d.m.Y'),
            'service_name' => (string) config('invoice.service_name', 'Izmaksu aprēķins un tāmes sagatavošana'),
            'currency' => (string) config('invoice.currency', 'EUR'),
            'amount_base' => $baseAmount,
            'vat_rate_percent' => $vatRate * 100,
            'amount_vat' => $vatAmount,
            'amount_total' => $total,
            'logo_data_uri' => $logoDataUri,
            'company' => $companyData,
            'customer' => Arr::only($payload, ['full_name', 'email', 'address', 'category', 'width', 'height', 'depth']),
        ];
    }

    private function buildInvoicePdf(array $invoice): string
    {
        $pdfFacade = \Barryvdh\DomPDF\Facade\Pdf::class;

        if (class_exists($pdfFacade) && View::exists('pdf.calculator-invoice')) {
            return $pdfFacade::loadView('pdf.calculator-invoice', [
                'invoice' => $invoice,
            ])->setPaper('a4')->output();
        }

        $company = (array) ($invoice['company'] ?? []);
        $lines = [
            'REKINS',
            'Rekina Nr.: '.((string) ($invoice['number'] ?? '')),
            'Datums: '.((string) ($invoice['date'] ?? '')),
            '',
            'Piegadatajs: '.((string) ($company['name'] ?? '-')),
            'Adrese: '.((string) ($company['address'] ?? '-')),
            'E-pasts: '.((string) ($company['email'] ?? '-')),
            'Talrunis: '.((string) ($company['phone'] ?? '-')),
            'Reg. Nr.: '.((string) ($company['registration_number'] ?? '-')),
            'PVN Nr.: '.((string) ($company['vat_number'] ?? '-')),
            'IBAN: '.((string) ($company['iban'] ?? '-')),
            'SWIFT: '.((string) ($company['swift'] ?? '-')),
            '',
            'Pakalpojums: '.((string) ($invoice['service_name'] ?? '')),
            'Summa bez PVN: '.number_format((float) ($invoice['amount_base'] ?? 0), 2, '.', '').' '.((string) ($invoice['currency'] ?? 'EUR')),
            'PVN '.number_format((float) ($invoice['vat_rate_percent'] ?? 0), 0).'%' . ': ' . number_format((float) ($invoice['amount_vat'] ?? 0), 2, '.', '') . ' ' . ((string) ($invoice['currency'] ?? 'EUR')),
            'Kopa apmaksai: '.number_format((float) ($invoice['amount_total'] ?? 0), 2, '.', '').' '.((string) ($invoice['currency'] ?? 'EUR')),
        ];

        return SimpleInvoicePdf::generate($lines);
    }

    private function buildLogoDataUri(string $relativePath): ?string
    {
        if ($relativePath === '') {
            return null;
        }

        $path = public_path(ltrim($relativePath, '/'));
        if (! is_file($path)) {
            return null;
        }

        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($extension) {
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg', 'jpeg' => 'image/jpeg',
            default => 'application/octet-stream',
        };

        $content = file_get_contents($path);
        if ($content === false) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($content);
    }
}
