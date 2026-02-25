<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $supportedLocales = ['lv', 'en', 'ru'];
    $locale = (string) session('locale', config('app.locale'));

    if (!in_array($locale, $supportedLocales, true)) {
        $locale = (string) config('app.fallback_locale', 'lv');
    }

    return redirect()->route('home', ['locale' => $locale]);
});

Route::prefix('{locale}')
    ->whereIn('locale', ['lv', 'en', 'ru'])
    ->group(function (): void {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::post('/request', [FormController::class, 'submitRequest'])->name('request.submit');
        Route::post('/calculator', [FormController::class, 'submitCalculator'])->name('calculator.submit');

        Route::get('/{legacyPage}', function (string $locale, string $legacyPage) {
            $map = [
                'about' => 'about',
                'material' => 'material',
                'materials' => 'material',
                'contacts' => 'contacts',
                'contact' => 'contacts',
            ];

            $pageKey = $map[$legacyPage] ?? null;
            if ($pageKey === null) {
                abort(404);
            }

            return redirect()->to(page_url($pageKey, [], $locale), 301);
        })->whereIn('legacyPage', ['about', 'material', 'materials', 'contacts', 'contact'])->name('page.legacy');

        Route::get('/works/{categorySlug?}', [PageController::class, 'worksLegacy'])->name('works');
        Route::get('/{worksPageSlug}/{categorySlug}/{workSlug}', [PageController::class, 'workShow'])->name('work.show');
        Route::get('/{worksPageSlug}/{categorySlug}', [PageController::class, 'worksOrLegacyWork'])->name('works.category');
        Route::get('/{pageSlug}', [PageController::class, 'staticPage'])->name('page.static');
    });
