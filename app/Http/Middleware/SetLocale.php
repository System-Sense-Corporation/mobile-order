<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $availableLocales = config('app.available_locales', []);
        $locale = $request->session()->get('locale');

        if ($locale && in_array($locale, $availableLocales, true)) {
            App::setLocale($locale);
        } else {
            $fallback = config('app.locale');

            if (! in_array($fallback, $availableLocales, true) && ! empty($availableLocales)) {
                $fallback = $availableLocales[0];
            }

            App::setLocale($fallback);
        }

        return $next($request);
    }
}
