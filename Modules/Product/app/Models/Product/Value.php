<?php

namespace Modules\Product\Models\Product;

use Modules\Core\Models\Eav\Model;
use Modules\Eav\Models\Eav\Attribute;
use Modules\Product\Models\Product\Entity;

class Value extends Model
{
    protected $table = 'product_entity_attribute_value';
    protected $primaryKey = 'value_id';
    public $timestamps = true;

    protected $fillable = [
        'entity_id',
        'attribute_id',
        'lang_id',
        'value',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'attribute_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class, 'entity_id', 'entity_id');
    }

    public function getLabelAttribute()
    {
        return $this->attribute->translation?->label ?? $this->attribute->code ?? '';
    }
}

