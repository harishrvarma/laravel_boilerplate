<?php

namespace Modules\Core\Models\Eav;

use Modules\Core\Models\Model as CoreModel;

class Model extends CoreModel
{
    public function joinAttr($query, array $attributeCodes, $joinType = 'left')
    {
        if (empty($attributeCodes)) {
            return $query;
        }

        $baseTable = $this->table;
        $table = $baseTable . '_attribute_value';

        foreach ($attributeCodes as $attr) {
            $alias = $attr->code;
            $effectiveLangId = ((int) $attr->lang_type === 1)
                ? config_locale_id()
                : current_locale_id();

            $query->{$joinType . 'Join'}($table . ' as ' . $alias, function ($join) use ($alias, $attr, $effectiveLangId) {
                $join->on('e.entity_id', '=', "{$alias}.entity_id")
                        ->where("{$alias}.attribute_id", '=', $attr->attribute_id)
                        ->where("{$alias}.lang_id", '=', $effectiveLangId);
            });

            $query->addSelect("{$alias}.value as {$alias}");
        }

        return $query;
    }
}