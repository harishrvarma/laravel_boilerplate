<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\ConfigOption;
use Modules\Settings\Models\ConfigValue;
use Modules\Settings\Models\ConfigGroup;

class ConfigKey extends Model
{
    protected $table = 'config_keys';

    protected $fillable = [
        'key_name',
        'label',
        'input_type',
        'is_required',
        'default_value',
        'options_source',
        'validation_rule',
        'is_user_config',
        'position',
    ];

    /** A setting key can have many options */
    public function options()
    {
        return $this->hasMany(ConfigOption::class);
    }

    /** A Config key can have many values */
    public function values()
    {
        return $this->hasMany(ConfigValue::class);
    }

    /** A Config key can belong to many groups */
    public function groups()
    {
        return $this->belongsToMany(
            ConfigGroup::class,
            'config_key_group',
            'config_key_id',
            'group_id'
        );
    }

}
