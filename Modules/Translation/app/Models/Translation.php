<?php

namespace Modules\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Modules\Translation\Models\TranslationLocale;

class Translation extends Model
{
    protected $table = 'translations';

    protected $fillable = [
        'module',
        'locale_id',
        'group',
        'key',
        'value'
    ];

    /**
     * Relationship: Translation belongs to a locale
     */
    public function locale()
    {
        return $this->belongsTo(TranslationLocale::class, 'locale_id');
    }

    /**
     * Accessor to still get `locale` as code (backward compatibility)
     */
    public function getLocaleAttribute()
    {
        return $this->locale()->value('code');
    }

    /**
     * Override save to clear cache automatically for this translation
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);

        if ($this->module && $this->group && $this->locale) {
            Cache::forget("translations.{$this->module}.{$this->group}.{$this->locale}");
        }

        return $result;
    }

    /**
     * Static method to clear cache for module/group/locale
     */
    public static function clearCache($module, $group, $locale)
    {
        Cache::forget("translations.{$module}.{$group}.{$locale}");
    }
}
