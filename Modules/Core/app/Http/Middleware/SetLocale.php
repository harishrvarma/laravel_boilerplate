<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Modules\Translation\Models\TranslationLocale;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = Session::get('admin.locale', config('app.locale'));

        if (class_exists(TranslationLocale::class)) {
            $available = TranslationLocale::pluck('code')->toArray();
            if (!in_array($locale, $available)) {
                $locale = config('app.locale');
            }
        }
        App::setLocale($locale);

        return $next($request);
    }
}
