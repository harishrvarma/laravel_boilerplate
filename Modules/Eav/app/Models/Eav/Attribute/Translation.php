<?php

namespace Modules\Eav\Models\Eav\Attribute;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Attribute;

class Translation extends Model
{
    protected $table = 'table_eav_entity_attribute_translation';
    protected $primaryKey = 'translation_id';
    protected $fillable = [
        'attribute_id',
        'lang_id',
        'display_name',
        'description',
        'created_at',
        'updated_at'
    ];

    /**
     * The attribute this translation belongs to.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'attribute_id');
    }
}
