<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Settings\Models\ConfigKey;

class ConfigGroup extends Model
{
    protected $table = 'config_groups';

    protected $fillable = [
        'name',
        'description',
    ];

     /** Group can have many keys */
     public function keys()
     {
         return $this->belongsToMany(
             ConfigKey::class,
             'config_key_group',
             'group_id',
             'config_key_id'
         );
     }
     
}
