<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'admin_resource';

    protected $fillable = [
        'code',
        'name',
        'route_name',
        'level',
        'parent_id',
        'method',
        'path_ids',
        'label',
        'uri',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Roles that have this resource
    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'admin_role_resource',
            'resource_id',
            'role_id'
        );
    }

    // Menus linked to this resource
    public function menus()
    {
        return $this->hasMany(\Modules\Menu\Models\Menu::class, 'resource_id');
    }

    // Parent resource
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    // Child resources
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('label');
    }

    /*
    |--------------------------------------------------------------------------
    | Tree helpers
    |--------------------------------------------------------------------------
    */

    // Build dropdown options tree
    public static function buildTreeOptions($parentId = null, $prefix = '')
    {
        $options = [];
        $resources = self::where('parent_id', $parentId)
            ->orderBy('label')
            ->get();

        foreach ($resources as $res) {
            $options[$res->id] = $prefix . $res->label . " ({$res->route_name})";
            $childOptions = self::buildTreeOptions($res->id, $prefix . '— ');
            $options = $options + $childOptions;
        }

        return $options;
    }

    // Get all descendants recursively
    public function allDescendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->allDescendants());
        }

        return $descendants;
    }
}
