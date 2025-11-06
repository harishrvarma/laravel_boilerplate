<?php

namespace Modules\Eav\Models\Eav\Attribute;

use Illuminate\Database\Eloquent\Model;
use Modules\Eav\Models\Eav\Attribute;
use Modules\Eav\Models\Eav\Attribute\Option\Translation;

class Option extends Model
{
    protected $table = 'table_eav_entity_attribute_option';
    protected $primaryKey = 'option_id';
    protected $fillable = [
        'attribute_id',
        'position',
        'is_active',
        'created_at',
        'updated_at'
    ];

    /**
     * The attribute this option belongs to.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'attribute_id');
    }

    /**
     * Translations for this option (multilingual labels/descriptions).
     */
    public function translations()
    {
        return $this->hasMany(Translation::class, 'option_id', 'option_id');
    }

    public function translation()
    {
        // Adjust according to how your translations table identifies language
        $langId = defined('LANG_ID') ? LANG_ID : 1;

        return $this->hasOne(Translation::class, 'option_id', 'option_id')
                    ->where('lang_id', $langId);
    }
}
