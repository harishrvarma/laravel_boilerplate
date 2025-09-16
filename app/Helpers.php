<?php

use Illuminate\Support\Facades\Auth;
use Modules\Settings\Models\ConfigValue;

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
if (!function_exists('current_locale')) {
    function current_locale(): string
    {
        $savedLang = ConfigValue::whereHas('key', function ($q) {
            $q->where('key_name', 'app_locale');
        })->value('value');

        return $savedLang ?? env('APP_LOCALE', 'en');
    }
}