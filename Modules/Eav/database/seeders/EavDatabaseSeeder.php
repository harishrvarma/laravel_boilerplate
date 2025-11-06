<?php

namespace Modules\Eav\Database\Seeders;
use Modules\Eav\Database\Seeders\AdminResourceSeeder;
use Illuminate\Database\Seeder;

class EavDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AdminResourceSeeder::class);
    }
}
