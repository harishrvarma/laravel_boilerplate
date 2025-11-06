<?php

namespace Modules\Product\Database\Seeders;
use Modules\Product\Database\Seeders\AdminResourceSeeder;
use Illuminate\Database\Seeder;

class ProductDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AdminResourceSeeder::class);
    }
}
