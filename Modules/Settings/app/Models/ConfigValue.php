<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\ConfigKey;
use Modules\Admin\Models\User;

class ConfigValue extends Model
{
    protected $table = 'config_values';

    protected $fillable = [
        'config_key_id',
        'user_id',
        'value',
    ];

    /** Value belongs to a config key */
    public function key()
    {
        return $this->belongsTo(ConfigKey::class, 'config_key_id');
    }

    /** Value may belong to an admin (nullable for global) */
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
