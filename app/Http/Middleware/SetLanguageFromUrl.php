<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLanguageFromUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // dd($request->query('lang'));
        $locales = config('translatable.locales');
        $fallbackLocale = config('translatable.fallback_locale');
        // Get the language code from the query parameter or route parameter
        $languageCode = $request->query('lang') ?? $request->route('lang');
        // Validate and set the application locale
        if ($languageCode && in_array($languageCode, $locales)) {
            // dd($languageCode);
            App::setLocale($languageCode);
        } else {
            App::setLocale($fallbackLocale); // Fallback to default locale
        }

        return $next($request);
    }
}
