<?php

namespace Modules\Product\Models\Product;

use Modules\Core\Models\Eav\Model;
use Modules\Product\Models\Product\Value;
use Modules\Translation\Models\TranslationLocale;
use Modules\Settings\Models\ConfigKey;

class Entity extends Model
{
    protected $table = 'product_entity';
    protected $primaryKey = 'entity_id';
    public $timestamps = true;

    protected $fillable = [
        'entity_type_id',
    ];

    public function values()
    {
        return $this->hasMany(Value::class, 'entity_id', 'entity_id');
    }

    public function joinAttr($query, array $attributeCodes, $joinType = 'left')
    {
        if (empty($attributeCodes)) {
            return $query;
        }
        foreach ($attributeCodes as $attr) {
            $alias = $attr->code;
            $effectiveLangId = ((int) $attr->lang_type === 1) ? config_locale_id() : current_locale_id();

            $query->{$joinType . 'Join'}("product_entity_attribute_value as {$alias}", function ($join) use ($alias, $attr, $effectiveLangId) {
                $join->on('e.entity_id', '=', "{$alias}.entity_id")
                     ->where("{$alias}.attribute_id", '=', $attr->attribute_id)
                     ->where("{$alias}.lang_id", '=', $effectiveLangId);
            });

            $query->addSelect("{$alias}.value as {$alias}");
        }
        return $query;
    }
}

