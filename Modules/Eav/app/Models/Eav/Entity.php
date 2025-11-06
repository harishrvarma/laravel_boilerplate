<?php

namespace Modules\Eav\Models\Eav;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Eav\Models\Eav\Attribute\Value;

class Entity extends Model
{
    protected $table = 'table_eav_entity';
    protected $primaryKey = 'entity_id';
    protected $fillable = ['entity_type_id', 'created_at', 'updated_at'];

    /**
     * Get the entity type (e.g., Product, Category, Service)
     */
    public function entityType()
    {
        return $this->belongsTo(Type::class, 'entity_type_id', 'entity_type_id');
    }

    /**
     * Get all EAV attribute values linked to this entity
     */
    public function attributeValues()
    {
        return $this->hasMany(Value::class, 'entity_id', 'entity_id');
    }
}
