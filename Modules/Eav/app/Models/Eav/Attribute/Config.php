<?php

namespace Modules\Eav\Models\Eav\Attribute;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Attribute;
use Modules\Eav\Models\Eav\Entity\Type;

class Config extends Model
{
    protected $table = 'table_entity_attribute_config';
    protected $primaryKey = 'config_id';

    protected $fillable = [
        'entity_type_id',
        'attribute_id',
        'show_in_grid',
        'default_in_grid',
        'is_sortable',
        'is_filterable',
    ];

    protected $casts = [
        'show_in_grid'   => 'boolean',
        'is_sortable'    => 'boolean',
        'is_filterable'  => 'boolean',
    ];

    // Relations
    public function entityType()
    {
        return $this->belongsTo(Type::class, 'entity_type_id', 'entity_type_id');
    }

    public function getEntityTypeNameAttribute()
    {
        return $this->entityType?->name ?? '-';
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'attribute_id');
    }

    public function getAttributeNameAttribute()
    {
        return $this->attribute?->code ?? '-';
    }
}
