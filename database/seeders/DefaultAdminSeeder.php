<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Role;
use Modules\Admin\Models\User;
use Modules\Admin\Models\Resource; 
use Modules\Admin\Models\Role\Resource as RoleResource;
use Illuminate\Support\Facades\DB;

class DefaultAdminSeeder extends Seeder
{
    public function run()
    {
        $role = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Full system access']
        );

        $user = User::firstOrCreate(
            ['email' => 'admin@server.com'],
            [
                'first_name' => 'Master Admin',
                'last_name'  => 'Master Admin',
                'username'   => 'Admin',
                'status'     => 1,
                'password'   => Hash::make('ccc123'),
            ]
        );

        DB::table('admin_user_role')->updateOrInsert(
            [
                'user_id' => $user->id,
                'role_id' => $role->id,
            ],
            []
        );


        $resourceCodes = [
            'admin.system.admin.listing',
            'admin.system.role.listing',
            'admin.system.role.save',
            'admin.system.role.edit',
        ];

        $resourceIds = Resource::whereIn('code', $resourceCodes)->pluck('id')->toArray();

        foreach ($resourceIds as $resId) {
            RoleResource::updateOrInsert(
                [
                    'role_id'     => $role->id,
                    'resource_id' => $resId,
                ],
                []
            );
        }
    }
}
