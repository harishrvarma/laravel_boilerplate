<?php

namespace Modules\Menu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'parent_id',
        'title',
        'icon',
        'area',
        'item_type',
        'path_ids',
        'level',
        'resource_id',
        'order_no',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order_no');
    }

    public function resource()
    {
        return $this->belongsTo(\Modules\Admin\Models\Resource::class, 'resource_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Booted events (path & hierarchy handling)
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        // Automatically set parent_id from resource hierarchy
        static::saving(function (Menu $menu) {
            if ($menu->resource_id) {
                // Check if a menu already exists for this resource
                $existingMenu = self::where('resource_id', $menu->resource_id)
                                    ->where('area', $menu->area ?? 'admin')
                                    ->first();
            
                if ($existingMenu && $menu->id !== $existingMenu->id) {
                    // Update the existing menu instead of creating a new one
                    $menu->id = $existingMenu->id;
                }
            
                $resource = $menu->resource;
                if (!$resource) return;
            
                $parts = explode('.', $resource->code);
            
                // Only assign a parent if the resource is not a folder
                $isFolder = $resource->route_name === ($parts[0] . '.' . $parts[1] . '.*');
                if ($isFolder) {
                    $menu->parent_id = null;
                    return;
                }
            
                if (count($parts) > 1) {
                    $parentCode = implode('.', array_slice($parts, 0, -1));
                    $parentResource = \Modules\Admin\Models\Resource::where('code', $parentCode)->first();
            
                    if ($parentResource) {
                        $parentMenu = self::firstOrCreate(
                            ['resource_id' => $parentResource->id, 'area' => $menu->area ?? 'admin'],
                            [
                                'title'     => ucfirst(str_replace('.', ' ', $parentCode)),
                                'icon'      => 'fas fa-folder',
                                'item_type' => 'folder',
                                'is_active' => 1,
                                'order_no'  => 0,
                            ]
                        );
                        $menu->parent_id = $parentMenu->id;
                    }
                }
            }
            
        });
        

        static::created(function (Menu $menu) {
            $path = $menu->parent?->path_ids 
                ? $menu->parent->path_ids . '/' . $menu->id
                : ($menu->parent_id ? $menu->parent_id . '/' . $menu->id : (string)$menu->id);

            $menu->path_ids = $path;
            $menu->level = substr_count($path, '/');
            $menu->saveQuietly();

            app('menu.cache')->clearAll();
        });

        static::updating(function (Menu $menu) {
            if ($menu->isDirty('parent_id')) {
                $newParent = $menu->parent;
                $menu->path_ids = $newParent
                    ? $newParent->path_ids . '/' . $menu->id
                    : (string)$menu->id;
                $menu->level = substr_count($menu->path_ids, '/');
            }
        });

        static::updated(function (Menu $menu) {
            if ($menu->wasChanged('path_ids')) {
                $originalPath = $menu->getOriginal('path_ids');
                $newPath = $menu->path_ids;

                $descendants = self::where('path_ids', 'like', $originalPath . '/%')->get();
                foreach ($descendants as $d) {
                    $d->path_ids = preg_replace('#^' . preg_quote($originalPath) . '#', $newPath, $d->path_ids);
                    $d->level = substr_count($d->path_ids, '/');
                    $d->saveQuietly();
                }
            }

            app('menu.cache')->clearAll();
        });
    
        static::deleted(function (Menu $menu) {
            // check if parent has no more children -> delete parent too
            if ($menu->parent_id) {
                $parent = self::find($menu->parent_id);
                if ($parent && $parent->children()->count() === 0) {
                    $parent->delete();
                }
            }
            app('menu.cache')->clearAll();
        });
        
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes & Helpers
    |--------------------------------------------------------------------------
    */
    public static function forArea($area = 'admin')
    {
        return self::where('area', $area)->orderBy('order_no')->get();
    }

    public static function buildTree($items, $parentId = null)
    {
        $branch = [];

        foreach ($items as $item) {
            if (($item->parent_id === null && $parentId === null) || ($item->parent_id == $parentId)) {
                $children = self::buildTree($items, $item->id);
                $node = $item->toArray();

                // Include the resource relation
                $node['resource'] = $item->resource?->toArray();

                $node['children'] = $children ?? [];
                $branch[] = $node;
            }
        }

        return $branch;
    }

    /**
     * Build menu tree from resources the user has access to
     */
    public static function buildMenuFromResources($user = null)
    {
        if ($user) {
            $resources = $user->allResources();
            $resourceIds = $resources->pluck('id')->toArray();
    
            $menus = self::with('resource')
                ->whereIn('resource_id', $resourceIds)
                ->where('is_active', 1)
                ->get();
        } else {
            // Global menus (all active menus)
            $menus = self::with('resource')
                ->where('is_active', 1)
                ->get();
        }
    
        // Include all parent menus recursively
        $allMenuIds = $menus->pluck('id')->toArray();
        $parents = collect();
    
        foreach ($menus as $menu) {
            $parent = $menu->parent;
            while ($parent) {
                if (!in_array($parent->id, $allMenuIds)) {
                    $parents->push($parent);
                    $allMenuIds[] = $parent->id;
                }
                $parent = $parent->parent;
            }
        }
    
        $menus = $menus->merge($parents);
    
        return self::buildTree($menus);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (shortcut to resource fields)
    |--------------------------------------------------------------------------
    */
    public function getRouteNameAttribute()
    {
        return $this->resource?->route_name;
    }

    public function getPermissionCodeAttribute()
    {
        return $this->resource?->code;
    }
}
