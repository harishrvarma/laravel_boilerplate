<?php

namespace Modules\Translation\Services;

use Illuminate\Translation\FileLoader;
use Modules\Translation\Models\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Modules\Cache\Models\CacheRegistry;
use Modules\Translation\Models\TranslationLocale;

class DatabaseLoader extends FileLoader
{
    public function load($locale, $group, $namespace = null)
    {
        if (!Schema::hasTable('translations') || !Schema::hasTable('translations_locale')) {
            return parent::load($locale, $group, $namespace);
        }
    
        $module = $namespace ?: 'app';
        $cacheKey = "translations:{$module}.{$group}.{$locale}";
    
        return Cache::store('translations')->rememberForever($cacheKey, function () use ($module, $group, $locale, $namespace) {
            return $this->loadTranslations($module, $group, $locale, $namespace);
        });
    }
    
    protected function loadTranslations($module, $group, $locale, $namespace)
    {
        $translations = [];
    
        if (Schema::hasTable('translations') && Schema::hasTable('translations_locale')) {
            $localeId = TranslationLocale::where('code', $locale)->value('id');
            if ($localeId) {
                $dbTranslations = Translation::where('module', $module)
                    ->where('group', $group)
                    ->where('locale_id', $localeId)
                    ->pluck('value', 'key')
                    ->toArray();
    
                $translations = $dbTranslations;
            }
        }
    
        $fileTranslations = parent::load($locale, $group, $namespace);
        $translations = array_merge($fileTranslations, $translations);
        return $translations;
    }
    
    public static function clearCache(string $module, string $group, string $locale): void
    {
        $cacheKey = "translations:{$module}.{$group}.{$locale}";
        Cache::store('translations')->forget($cacheKey);

        CacheRegistry::where([
            'area'  => 'module',
            'type'  => $module,
            'key'   => $cacheKey,
            'store' => 'translations',
        ])->delete();
    }

    public static function clearAllCache(): void
    {
        Cache::store('translations')->flush();
        CacheRegistry::where('store', 'translations')->delete();
    }
}
