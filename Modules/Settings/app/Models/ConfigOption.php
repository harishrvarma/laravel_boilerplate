<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\ConfigKey;
use Modules\Translation\Models\TranslationLocale;

class ConfigOption extends Model
{
    protected $table = 'config_options';

    protected $fillable = [
        'config_key_id',
        'option_label',
        'option_value',
        'position',
        'is_default',
    ];

    /** Option belongs to a config key */
    public function key()
    {
        return $this->belongsTo(ConfigKey::class, 'config_key_id');
    }

    public function languageOptions()
    {
        return TranslationLocale::pluck('label', 'code')->toArray();
    }
    
}
