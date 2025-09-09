<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (\Auth::guard('admin')->check()) {
        return redirect()->route('admin.admin.listing');
    }
    return redirect()->route('admin.login');
});

Route::fallback(function () {
    if (\Auth::guard('admin')->check()) {
        return redirect()->route('admin.admin.listing');
    }
    return redirect()->route('admin.login');
});