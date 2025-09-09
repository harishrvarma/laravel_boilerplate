<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Modules\Api\Models\ApiResource;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Passport::routes();

        $resources = ApiResource::pluck('name','code')->toArray();

        Passport::tokensCan($resources);
    }
}
