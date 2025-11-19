<?php

namespace Modules\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Translation\Services\DatabaseLoader;

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


    public function locale()
    {
        return $this->belongsTo(TranslationLocale::class, 'locale_id');
    }

    public function getLocaleCodeAttribute()
    {
        return $this->locale?->code;
    }

    public function save(array $options = [])
    {
        $result = parent::save($options);

        if ($this->module && $this->group && $this->locale_code) {
            DatabaseLoader::clearCache($this->module, $this->group, $this->locale_code);
        }

        return $result;
    }

    public function delete()
    {
        $module = $this->module;
        $group = $this->group;
        $locale = $this->locale_code;

        $result = parent::delete();

        if ($module && $group && $locale) {
            DatabaseLoader::clearCache($module, $group, $locale);
        }

        return $result;
    }

    public static function massDelete(array $ids)
    {
        $translations = self::whereIn('id', $ids)->get();

        foreach ($translations as $translation) {
            $translation->delete(); // triggers cache clear
        }
    }
}
