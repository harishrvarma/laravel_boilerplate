<?php

namespace Modules\Eav\Models\Eav\Attribute\Group;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'table_eav_attribute_group_translation';
    protected $primaryKey = 'translation_id';

    protected $fillable = [
        'group_id',
        'lang_id',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * The parent attribute group this translation belongs to.
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'group_id');
    }
}
