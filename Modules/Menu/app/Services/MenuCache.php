<?php

namespace Modules\Menu\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Menu\Models\Menu;
use Modules\Cache\Models\CacheRegistry;

class MenuCache
{
    protected string $store = 'module';

    // User-specific cache
    public function getForUser($user)
    {
        $cacheKey = "module.user.{$user->id}";

        return Cache::store($this->store)->rememberForever($cacheKey, function () use ($user, $cacheKey) {
            $menu = Menu::buildMenuFromResources($user);

            // Register in cache registry
            CacheRegistry::updateOrCreate(
                [
                    'area'  => 'module',
                    'type'  => 'menu',
                    'key'   => $cacheKey,
                    'store' => $this->store,
                ],
                ['last_generated' => now()]
            );

            return $menu;
        });
    }

    // Global menu cache
    public function getGlobal()
    {
        $cacheKey = "module.menu.global";

        return Cache::store($this->store)->rememberForever($cacheKey, function () use ($cacheKey) {
            $menu = Menu::buildMenuFromResources(); // call without user

            // Register in cache registry
            CacheRegistry::updateOrCreate(
                [
                    'area'  => 'module',
                    'type'  => 'menu',
                    'key'   => $cacheKey,
                    'store' => $this->store,
                ],
                ['last_generated' => now()]
            );

            return $menu;
        });
    }

    // Clear all menu caches (user + global)
    public function clearAll()
    {
        $keys = CacheRegistry::where('type', 'menu')
            ->where('store', $this->store)
            ->pluck('key');

        foreach ($keys as $key) {
            Cache::store($this->store)->forget($key);
        }

        CacheRegistry::where('type', 'menu')
            ->where('store', $this->store)
            ->delete();
    }
}