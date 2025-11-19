<?php

namespace Modules\Eav\Models\Eav\Attribute;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Attribute;
use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Eav\Models\Eav\Attribute\Group\Translation as GroupTranslation;

class Group extends Model
{
    protected $table = 'table_eav_attribute_group';
    protected $primaryKey = 'group_id';

    protected $fillable = [
        'entity_type_id',
        'code',
        'position',
        'created_at',
        'updated_at',
    ];

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'group_id', 'group_id');
    }

    public function translations()
    {
        return $this->hasMany(GroupTranslation::class, 'group_id', 'group_id');
    }

    public function translation()
    {
        $langId = current_locale_id();
    
        return $this->hasOne(GroupTranslation::class, 'group_id', 'group_id')
                    ->where('lang_id', $langId);
    }

    public function entityType()
    {
        return $this->belongsTo(Type::class, 'entity_type_id', 'entity_type_id');
    }
    public function getEntityTypeNameAttribute()
    {
        return $this->entityType?->name ?? '-';
    }
}
