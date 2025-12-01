<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (\Auth::guard('admin')->check()) {
        return redirect()->route('admin.syatem.admin.listing');
    }
    return redirect()->route('admin.login');
});

Route::fallback(function () {
    if (\Auth::guard('admin')->check()) {
        return redirect()->route('admin.system.admin.listing');
    }
    return redirect()->route('admin.login');
});