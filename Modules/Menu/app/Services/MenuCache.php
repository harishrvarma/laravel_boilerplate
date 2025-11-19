<?php

namespace Modules\Menu\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Menu\Models\Menu;
use Modules\Cache\Models\CacheRegistry;

class MenuCache
{
    protected string $store = 'module';

    public function getGlobal()
    {
        $cacheKey = "module.menu.global";
    
        $menu = Menu::buildMenuFromResources();
    
        Cache::store($this->store)->forever($cacheKey, $menu);
    
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