<?php

namespace Modules\Eav\Http\Controllers\Eav\Attribute;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Eav\Models\Eav\Attribute\Config;
use Modules\Eav\View\Components\Eav\Attributes\Config\Listing as Listing;
use Modules\Translation\Models\TranslationLocale;

class ConfigController extends BackendController
{
    public function listing(Request $request)
    {
        try {
            $layout  = $this->layout();
            $layout->title('Manage Attribute Groups');

            $listing = $this->block(Listing::class);

            $content = $layout->child('content')->child('listing', $listing);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th);
        }
    }

    public function save(Request $request)
    {
        try {
            foreach (['show_in_grid', 'is_filterable', 'is_sortable','default_in_grid'] as $field) {
                $values = $request->post($field, []);
                foreach ($values as $entityTypeId => $attrs) {
                    foreach ($attrs as $attributeId => $value) {
                        Config::updateOrCreate(
                            [
                                'entity_type_id' => $entityTypeId,
                                'attribute_id'   => $attributeId,
                            ],
                            [
                                $field => $value,
                            ]
                        );
                    }
                }
            }
            return redirect()
                ->route('admin.eav.attributes.config.listing', ['id' => $entityTypeId])
                ->with('success', 'Config saved successfully.');
    
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
