<?php

if (! function_exists('urlx')) {
    function urlx(?string $route = null, array $params = [], bool $reset = false, ?string $fragment = null): string {
        return (new \App\View\Components\Urlx())->url($route, $params, $reset, $fragment);
    }
}