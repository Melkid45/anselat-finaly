<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('translatable.locales', ['lv', 'en', 'ru']);
        $supportedLocales = array_values(array_filter($supportedLocales, 'is_string'));

        $routeLocale = $request->route('locale');
        $locale = is_string($routeLocale)
            ? $routeLocale
            : (string) session('locale', config('app.locale'));

        if (!in_array($locale, $supportedLocales, true)) {
            $locale = (string) config('app.fallback_locale', 'lv');
        }

        session(['locale' => $locale]);
        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $next($request);
    }
}
