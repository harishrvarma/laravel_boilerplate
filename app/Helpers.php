<?php

use Illuminate\Support\Facades\Auth;

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
