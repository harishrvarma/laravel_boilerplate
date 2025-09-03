<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\ConfigKey;
use Modules\Settings\Models\ConfigGroup;

class ConfigKeyGroup extends Model
{
    protected $table = 'config_key_group';

    // If you allow mass-assignment
    protected $fillable = [
        'config_key_id',
        'group_id',
    ];

    /** Pivot belongs to a ConfigKey */
    public function key()
    {
        return $this->belongsTo(ConfigKey::class, 'config_key_id');
    }

    /** Pivot belongs to a ConfigGroup */
    public function group()
    {
        return $this->belongsTo(ConfigGroup::class, 'group_id');
    }
}
