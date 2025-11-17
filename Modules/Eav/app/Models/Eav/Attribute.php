<?php

namespace Modules\Eav\Models\Eav;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Eav\Models\Eav\Attribute\Translation;
use Modules\Eav\Models\Eav\Attribute\Value;
use Modules\Eav\Models\Eav\Attribute\Option;
use Modules\Eav\Models\Eav\Attribute\Group;
use Modules\Eav\Models\Eav\Attribute\Config;
use Carbon\Carbon;

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
        'lang_type',
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

    public function getLangTypeNameAttribute(): string
    {
        return $this->lang_type ? 'Static' : 'Dynamic';
    }

    public function castValue($value, $defaultValue = null)
    {
        $dataType = strtolower($this->data_type ?? 'null');

        if ($value === null || $value === '') {
            return $defaultValue;
        }

        switch ($dataType) {
            case 'text':
            case 'textarea':
            case 'editor':
            case 'password':
            case 'hidden':
            case 'string':
                return trim((string) $value);

            case 'number':
            case 'int':
            case 'integer':
                return (int) $value;

            case 'float':
            case 'decimal':
            case 'double':
                return (float) $value;

            case 'boolean':
            case 'bool':
                if (in_array(strtolower((string)$value), ['yes', 'true', '1', 'on'])) {
                    return true;
                }
                if (in_array(strtolower((string)$value), ['no', 'false', '0', 'off'])) {
                    return false;
                }
                return (bool) $value;

            case 'select':
            case 'radio':
            case 'checkbox':
                return is_array($value) ? json_encode($value) : (string) $value;

            case 'date':
                try {
                    return Carbon::parse($value)->toDateString();
                } catch (\Exception) {
                    return $defaultValue ?? null;
                }

            case 'datetime':
                try {
                    return Carbon::parse($value)->toDateTimeString();
                } catch (\Exception) {
                    return $defaultValue ?? null;
                }

            case 'time':
                try {
                    return Carbon::parse($value)->format('H:i:s');
                } catch (\Exception) {
                    return $defaultValue ?? null;
                }

            case 'file':
            case 'image':
                return (string) $value;

            default:
                return trim((string) $value);
        }
    }
    
}
