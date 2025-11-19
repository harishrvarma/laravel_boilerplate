<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        Config::set('session.cookie', env('SESSION_COOKIE_ADMIN', 'admin_session'));
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
