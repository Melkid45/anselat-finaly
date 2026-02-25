<?php

namespace App\Support;

class SimpleInvoicePdf
{
    public static function generate(array $lines): string
    {
        $content = self::buildContentStream($lines);

        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            2 => '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            3 => '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
            4 => "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "\nendstream",
            5 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $number => $body) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $body . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $count = count($objects) + 1;

        $pdf .= "xref\n0 " . $count . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) ($offsets[$i] ?? 0), 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        $pdf .= "trailer\n<< /Size " . $count . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xrefOffset . "\n%%EOF";

        return $pdf;
    }

    private static function buildContentStream(array $lines): string
    {
        $safeLines = array_map([self::class, 'escapePdfText'], $lines);

        $stream = "BT\n";
        $stream .= "/F1 12 Tf\n";
        $stream .= "1 0 0 1 50 800 Tm\n";
        $stream .= "16 TL\n";

        foreach ($safeLines as $index => $line) {
            if ($index > 0) {
                $stream .= "T*\n";
            }
            $stream .= "(" . $line . ") Tj\n";
        }

        $stream .= "ET";

        return $stream;
    }

    private static function escapePdfText(string $text): string
    {
        $text = str_replace(["\\", "(", ")"], ["\\\\", "\\(", "\\)"], $text);

        // Keep Type1-safe output in the generated PDF stream.
        return preg_replace('/[^\x20-\x7E]/', '', $text) ?? '';
    }
}
