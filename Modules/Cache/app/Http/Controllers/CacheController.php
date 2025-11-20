<?php

namespace Modules\Cache\Http\Controllers;

use Exception;
use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Cache\Models\CacheRegistry;
use Modules\Cache\View\Components\Cache\Listing;

class CacheController extends BackendController
{
    /**
     * Show cache listing
     */
    public function listing(Request $request)
    {
        $listing = $this->block(Listing::class);

        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing', $listing);

        return $layout->render();
    }

    /**
     * Clear a single cache entry
     */
    public function clear(Request $request, $id)
    {
        try {
            $entry = CacheRegistry::findOrFail($id);
            $entry->clear();

            return redirect()->route('admin.system.cache.listing')->with('success','Cache cleared successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Clear all cache entries
     */
    public function clearAll(Request $request)
    {
        try {
            CacheRegistry::all()->each(function ($entry) {
                $entry->clear();
            });
    
            \Cache::store('translations')->flush();
    
            return redirect()->route('admin.system.cache.listing')->with('success', 'All caches cleared successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
}
