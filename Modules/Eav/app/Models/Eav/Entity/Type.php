<?php

namespace Modules\Eav\Models\Eav\Entity;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Entity;
use Modules\Eav\Models\Eav\Attribute;

class Type extends Model
{
    protected $table = 'table_eav_entity_type';
    protected $primaryKey = 'entity_type_id';
    protected $fillable = ['code', 'name', 'model_class', 'created_at', 'updated_at'];

    /**
     * All entities (records) that belong to this entity type.
     */
    public function entities()
    {
        return $this->hasMany(Entity::class, 'entity_type_id', 'entity_type_id');
    }

    /**
     * All attributes defined for this entity type.
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'entity_type_id', 'entity_type_id');
    }
}
