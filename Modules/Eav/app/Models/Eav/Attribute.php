<?php

namespace Modules\Eav\Models\Eav;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Eav\Models\Eav\Attribute\Translation;
use Modules\Eav\Models\Eav\Attribute\Value;
use Modules\Eav\Models\Eav\Attribute\Option;
use Modules\Eav\Models\Eav\Attribute\Group;
use Modules\Eav\Models\Eav\Attribute\Config;

class Attribute extends Model
{
    protected $table = 'table_eav_entity_attribute';
    protected $primaryKey = 'attribute_id';
    protected $fillable = [
        'entity_type_id',
        'group_id',
        'code',
        'data_type',
        'data_model',
        'is_required',
        'position',
        'default_value',
        'created_at',
        'updated_at',
    ];

    public function entityType()
    {
        return $this->belongsTo(Type::class, 'entity_type_id', 'entity_type_id');
    }

    public function getEntityTypeNameAttribute()
    {
        return $this->entityType?->name ?? '-';
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'group_id');
    }

    public function getGroupNameAttribute()
    {
        return $this->group?->code ?? '-';
    }

    public function translations()
    {
        return $this->hasMany(Translation::class, 'attribute_id', 'attribute_id');
    }
    
    public function translation()
    {
        $langId = defined('LANG_ID') ? LANG_ID : 1;
    
        return $this->hasOne(Translation::class, 'attribute_id', 'attribute_id')
                    ->where('lang_id', $langId);
    }

    public function values()
    {
        return $this->hasMany(Value::class, 'attribute_id', 'attribute_id');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'attribute_id', 'attribute_id');
    }

    public function config()
    {
        return $this->hasOne(Config::class, 'attribute_id', 'attribute_id');
    }
}
