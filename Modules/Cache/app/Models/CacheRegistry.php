<?php

namespace Modules\Cache\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheRegistry extends Model
{
    protected $table = 'cache_registry';

    protected $fillable = [
        'area', 'type', 'key', 'store', 'last_generated', 'builder_class'
    ];

    /**
     * Clear this cache entry
     */
    public function clear()
    {
        try {
            Cache::store($this->store)->forget($this->key);

            // Reset last_generated timestamp
            $this->last_generated = null;
            $this->save();

            // Optional: regenerate cache if builder class exists
            if ($this->builder_class && class_exists($this->builder_class)) {
                (new $this->builder_class)->generateCache();
            }
        } catch (\Exception $e) {
            Log::error("Failed to clear cache '{$this->key}' in store '{$this->store}': {$e->getMessage()}");
        }
    }
}
