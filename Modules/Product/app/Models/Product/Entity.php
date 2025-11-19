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
}

