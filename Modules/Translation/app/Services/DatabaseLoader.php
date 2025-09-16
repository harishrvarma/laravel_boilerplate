<?php

namespace Modules\Translation\Services;

use Illuminate\Translation\FileLoader;
use Modules\Translation\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Modules\Cache\Models\CacheRegistry;

class DatabaseLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null)
    {
        if (!Schema::hasTable('translations') || !Schema::hasTable('translations_locale')) {
            return parent::load($locale, $group, $namespace);
        }
    
        // Load file translations first
        $lines = parent::load($locale, $group, $namespace);
    
        $module = $namespace ?: 'app';
    
        // Skip inline or wildcard groups
        if ($group === '*' || $module === '*') {
            return $lines;
        }
    
        $cacheKey = "{$module}.{$group}.{$locale}";
    
        return Cache::store('translations')->rememberForever(
            $cacheKey,
            function () use ($module, $group, $locale, $lines) {
                $dbTranslations = \Modules\Translation\Models\Translation::with('locale')
                    ->whereHas('locale', fn($q) => $q->where('code', $locale))
                    ->where('module', $module)
                    ->where('group', $group)
                    ->pluck('value', 'key')
                    ->toArray();
    
                $translations = array_merge($lines, $dbTranslations);
    
                // register this cache in registry
                \Modules\Cache\Models\CacheRegistry::updateOrCreate(
                    [
                        'area'  => 'module',
                        'type'  => $module,
                        'key'   => "{$module}.{$group}.{$locale}",
                        'store' => 'translations',
                    ],
                    ['last_generated' => now()]
                );
    
                return $translations;
            }
        );
    }
    
}
