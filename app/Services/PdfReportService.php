<?php

namespace App\Services;

use App\Models\Call;
use ImranDev\UnicodePdf\Facades\UnicodePdf;
use Symfony\Component\HttpFoundation\Response;

class PdfReportService
{
    /**
     * Generate and stream/download a call scorecard PDF.
     */
    public function generateCallScorecardPdf(Call $call, string $locale = 'en'): Response
    {
        $preset = match ($locale) {
            'bn' => 'bengali',
            'ar' => 'arabic',
            'hi' => 'hindi',
            default => 'latin',
        };

        return UnicodePdf::preset($preset)
            ->loadView('pdf.call-scorecard', [
                'call' => $call,
                'locale' => $locale,
            ])
            ->a4()
            ->download("scorecard-call-{$call->id}.pdf");
    }

    /**
     * Save PDF to storage path.
     */
    public function saveCallScorecardPdf(Call $call, string $path, string $locale = 'en'): string
    {
        $preset = match ($locale) {
            'bn' => 'bengali',
            'ar' => 'arabic',
            'hi' => 'hindi',
            default => 'latin',
        };

        UnicodePdf::preset($preset)
            ->loadView('pdf.call-scorecard', [
                'call' => $call,
                'locale' => $locale,
            ])
            ->a4()
            ->save($path);

        return $path;
    }
}
