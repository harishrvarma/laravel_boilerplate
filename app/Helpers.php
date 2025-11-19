<?php

use Illuminate\Support\Facades\Auth;
use Modules\Settings\Models\ConfigValue;
use Modules\Translation\Models\TranslationLocale;
use Illuminate\Support\Facades\Session;
use Modules\Settings\Models\ConfigKey;

if (! function_exists('urlx')) {
    function urlx(?string $route = null, array $params = [], bool $reset = false, ?string $fragment = null): string {
        return (new \App\View\Components\Urlx())->url($route, $params, $reset, $fragment);
    }
}

if (! function_exists('canAccess')) {
    function canAccess(string $resourceCode): bool
    {
        $admin = Auth::guard('admin')->user();
        return $admin && $admin->hasAccess($resourceCode);
    }
}
if (!function_exists('config_locale')) {
    function config_locale(): string
    {
        $savedLang = ConfigValue::whereHas('key', function ($q) {
            $q->where('key_name', 'app_locale');
        })->value('value');

        return $savedLang ?? env('APP_LOCALE', 'en');
    }
}

if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        return Session::get('admin.locale', app()->getLocale());
    }
}

if (!function_exists('current_locale_id')) {
    function current_locale_id(): string
    {
        return TranslationLocale::where('code', current_locale())->value('id');
    }
}

if (!function_exists('config_locale_id')) {
    function config_locale_id(): string
    {
        $configKey = ConfigKey::with('values')->where('key_name', 'app_locale')->first();
        return TranslationLocale::where('code', $configKey?->values?->first()?->value)->value('id');
    }
}