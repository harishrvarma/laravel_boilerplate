<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\ConfigKey;
use Modules\Admin\Models\Admin;

class ConfigValue extends Model
{
    protected $table = 'config_values';

    protected $fillable = [
        'config_key_id',
        'admin_id',
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
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
