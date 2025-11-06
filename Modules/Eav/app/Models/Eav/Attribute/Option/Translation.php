<?php

namespace Modules\Eav\Models\Eav\Attribute\Option;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Attribute\Option;

class Translation extends Model
{
    protected $table = 'table_eav_entity_attribute_option_translation';
    protected $primaryKey = 'translation_id';
    protected $fillable = [
        'option_id',
        'lang_id',
        'display_name',
        'description',
        'created_at',
        'updated_at'
    ];

    /**
     * The option this translation belongs to.
     */
    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id', 'option_id');
    }
}
