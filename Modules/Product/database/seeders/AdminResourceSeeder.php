<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Modules\Admin\Models\Resource;
use Illuminate\Support\Facades\DB;

class AdminResourceSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('admin_resource')) {
            return;
        }

        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return str_starts_with($route->uri(), 'admin/') && $route->getName();
        });

        $newCodes = [];

        foreach ($routes as $route) {
            $code = $route->getName();
            $methods = implode('|', $route->methods());

            $label = $route->defaults['label'] ?? ucfirst(last(explode('.', $code))) ?? $code;
            $newCodes[] = $code;

            $this->createResourceTree($route);
        }

        Resource::whereNotIn('code', $newCodes)->update(['status' => '0']);
    }

    private function createResourceTree($route)
    {
        $routeName = $route->getName();
        $parts = explode('.', $routeName);

        if ($parts[0] !== 'admin') {
            return;
        }

        $parentId = null;
        $code = '';
        $pathIds = '';

        foreach ($parts as $level => $part) {
            $code = $code ? $code . '.' . $part : $part;
            $isLast = $level + 1 === count($parts);

             if ($level === 0 && $part === 'admin') {
                continue;
            }

            $name = $isLast ? ucfirst(str_replace('_', ' ', $part)) : null;

            if (!$isLast) {
                $label = ucfirst(str_replace('_', ' ', $parts[count($parts) - 2] ?? $part));
            } else {
                $label = $route->defaults['label'] ?? ucfirst(str_replace('_', ' ', $part)) ?? $routeName;
            }

            $resource = Resource::updateOrCreate(
                ['code' => $code],
                [
                    'name'       => $isLast ? $name : ucfirst(str_replace('_', ' ', $parts[count($parts) - 2] ?? $part)),
                    'label'      => $label,
                    'route_name' => $isLast ? $routeName : $code . '.*',
                    'uri'        => $isLast ? $route->uri() : null,
                    'method'     => $isLast ? implode('|', $route->methods()) : 'ANY',
                    'level'      => $level + 1,
                    'parent_id'  => $parentId,
                    'status'     => '1',
                ]
            );

            $pathIds = $parentId
                ? Resource::find($parentId)->path_ids . $resource->id . '/'
                : '/' . $resource->id . '/';

            $resource->update([
                'path_ids'  => $pathIds,
                'parent_id' => $parentId,
                'level'     => $level + 1,
            ]);

            $exists = DB::table('admin_role_resource')
                ->where('role_id', 1)
                ->where('resource_id', $resource->id)
                ->exists();

            if (!$exists) {
                DB::table('admin_role_resource')->insert([
                    'role_id'     => 1,
                    'resource_id' => $resource->id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
            $exists = DB::table('admin_role_resource')
            ->where('role_id', 1)
            ->where('resource_id', $resource->id)
            ->exists();

            if (!$exists) {
                DB::table('admin_role_resource')->insert([
                    'role_id'     => 1,
                    'resource_id' => $resource->id,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

        }
    }
}