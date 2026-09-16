<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use App\Models\Language;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $langCode = $request->segment(1);

        if (! Schema::hasTable('languages')) {
            $locale = is_string($langCode) && preg_match('/^[a-z]{2}$/', $langCode)
                ? $langCode
                : config('app.locale', 'tr');

            App::setLocale($locale);
            \Illuminate\Support\Facades\URL::defaults(['lang' => $locale]);

            return $next($request);
        }

        $languages = Language::active()->pluck('code')->toArray();

        if (in_array($langCode, $languages)) {
            App::setLocale($langCode);
            \Illuminate\Support\Facades\URL::defaults(['lang' => $langCode]);
        } else {
            $default = Language::default();
            App::setLocale($default->code);
            \Illuminate\Support\Facades\URL::defaults(['lang' => $default->code]);
            
            // Optional: Redirect / to /tr
            // if ($request->path() == '/') {
            //     return redirect('/' . $default->code);
            // }
        }

        return $next($request);
    }
}
