<?php

namespace Modules\Eav\Models\Eav\Attribute;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Entity;
use Modules\Eav\Models\Eav\Attribute;

class Value extends Model
{
    protected $table = 'table_eav_entity_attribute_value';
    protected $primaryKey = 'value_id';
    protected $fillable = [
        'entity_id',
        'attribute_id',
        'lang_id',
        'value',
        'created_at',
        'updated_at'
    ];

    /**
     * The entity this value belongs to.
     */
    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'entity_id');
    }

    /**
     * The attribute this value belongs to.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'attribute_id');
    }
}
