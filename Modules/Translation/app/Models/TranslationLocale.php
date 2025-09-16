<?php

namespace Modules\Translation\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Modules\Translation\Models\Translation;

class TranslationLocale extends Model
{
    protected $table = 'translations_locale';
    protected $fillable = ['code', 'label'];

    public function translations()
    {
        return $this->hasMany(Translation::class, 'locale_id');
    }
}