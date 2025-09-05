<?php

namespace Modules\Admin\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\Resource;
use Modules\Admin\Models\Role\Resource as RoleResource;

class AdminPermission
{
    public function handle($request, Closure $next)
    {

        $admin = Auth::guard('admin')->user();

        $routeName = $request->route()->getName();

        // Find resource for this route
        $resource = Resource::where('route_name', $routeName)->first();

        if (!$resource) {
            return abort(403, 'Resource not defined.');
        }

        // Get all role IDs for this user from AdminUserRole
        $roleIds = $admin->role()->pluck('role_id'); 
        // (make sure you define relation in AdminUser model)

        $hasAccess = RoleResource::whereIn('role_id', $roleIds)
            ->where('resource_id', $resource->id)
            ->exists();

        if (!$hasAccess) {
            return abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
