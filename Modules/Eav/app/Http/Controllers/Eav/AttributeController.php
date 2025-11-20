<?php

namespace Modules\Eav\Http\Controllers\Eav;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Eav\Models\Eav\Attribute;
use Modules\Eav\View\Components\Eav\Attributes\Listing as Listing;
use Modules\Eav\View\Components\Eav\Attributes\Listing\Edit as Edit;
use Modules\Translation\Models\TranslationLocale;
use Modules\Eav\Models\Eav\Attribute\Group;
use Modules\Eav\Models\Eav\Attribute\Config as AttributeConfig;

class AttributeController extends BackendController
{
    public function listing(Request $request)
    {
        try {
            $layout  = $this->layout();
            $layout->title('Manage Attributes');

            $listing = $this->block(Listing::class);

            $content = $layout->child('content')->child('listing', $listing);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th);
        }
    }

    /**
     * Add Attribute
     */
    public function add()
    {
        try {
            $layout = $this->layout();
            $layout->title('Add/Edit Attribute');

            $attribute = $this->model(Attribute::class);
            $row       = $this->block(Edit::class)->row($attribute);

            $content = $layout->child('content')->child('edit', $row);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th);
        }
    }

    public function edit($id)
    {
        try {
            $layout  = $this->layout();
            $layout->title('Add/Edit Attributes');
    
            if (!$id) {
                throw new \Exception("Invalid Request");
            }
    
            $row = Attribute::with([
                'translations',
                'options.translations'
            ])->find($id);
    
            if (!$row) {
                throw new \Exception("Invalid Request");
            }
    
            $edit = $this->model(Edit::class)->row($row);
            $content = $layout->child('content')->child('edit', $edit);
    
            return $layout->render();
    
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function save(Request $request)
    {
        try {
            $data = $request->post('attributes', []);
            $labels = $request->post('label', []);
            $options = $request->post('options', []);
    
            if (empty($data['entity_type_id']) || empty($data['code'])) {
                return back()->with('error', 'Entity Type and Code are required.');
            }
    
            $attribute = !empty($data['attribute_id'])
                ? Attribute::findOrFail($data['attribute_id'])
                : new Attribute();
    
            $exists = Attribute::where('entity_type_id', $data['entity_type_id'])
                ->where('code', $data['code'])
                ->when(!empty($data['attribute_id']), fn($q) => $q->where('attribute_id', '!=', $data['attribute_id']))
                ->exists();
    
            if ($exists) {
                return back()->with('error', 'Duplicate attribute code for this entity type.');
            }
    
            $attribute->fill($data);
            $attribute->save();
    
            foreach ($labels as $langCode => $translationData) {
                $displayName = trim($translationData['display_name'] ?? '');
                if ($displayName === '') continue;
    
                $locale = TranslationLocale::where('code', $langCode)->first();
                if (!$locale) continue;
    
                $attribute->translations()->updateOrCreate(
                    ['lang_id' => $locale->id],
                    ['display_name' => $displayName]
                );
            }
    
            $savedOptionIds = [];
    
            if (!empty($options)) {
                foreach ($options as $optionData) {
                    $hasAnyLabel = collect($optionData['label'] ?? [])
                        ->filter(fn($v) => trim($v) !== '')
                        ->isNotEmpty();
                    if (!$hasAnyLabel) continue;
    
                    $option = !empty($optionData['option_id'])
                        ? \Modules\Eav\Models\Eav\Attribute\Option::find($optionData['option_id'])
                        : new \Modules\Eav\Models\Eav\Attribute\Option();
    
                    if (!$option) $option = new \Modules\Eav\Models\Eav\Attribute\Option();
    
                    $option->attribute_id = $attribute->attribute_id;
                    $option->position = $optionData['position'] ?? 0;
                    $option->is_active = $optionData['is_active'] ?? 1;
                    $option->save();
    
                    $savedOptionIds[] = $option->option_id;
    
                    foreach ($optionData['label'] ?? [] as $langCode => $labelValue) {
                        $labelValue = trim($labelValue ?? '');
                        if ($labelValue === '') continue;
    
                        $locale = TranslationLocale::where('code', $langCode)->first();
                        if (!$locale) continue;
    
                        $option->translations()->updateOrCreate(
                            ['lang_id' => $locale->id],
                            ['display_name' => $labelValue]
                        );
                    }
                }
            }
    
            if (!empty($data['attribute_id'])) {
                \Modules\Eav\Models\Eav\Attribute\Option::where('attribute_id', $attribute->attribute_id)
                    ->whereNotIn('option_id', $savedOptionIds)
                    ->each(function ($opt) {
                        $opt->translations()->delete();
                        $opt->delete();
                    });
            }

            $config = AttributeConfig::where('entity_type_id', $attribute->entity_type_id)
                ->where('attribute_id', $attribute->attribute_id)
                ->first();

            if (!$config) {
                $config = new AttributeConfig();
                $config->entity_type_id = $attribute->entity_type_id;
                $config->attribute_id   = $attribute->attribute_id;
            }

            $config->show_in_grid  = false;
            $config->is_sortable   = true;
            $config->is_filterable = true;

            $config->save();

    
            return redirect()
                ->route('admin.system.eav.attributes.listing')
                ->with('success', 'Attribute and options saved successfully.');
    
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    
    public function delete(Request $request)
    {
        try {
            if(!$request->id){
                throw new Exception("Invalid Request");
            }
            $row = Attribute::find($request->id);
            if(!$row){
                throw new Exception("Invalid Request");
            }
            $row->delete();

            return redirect()->route('admin.system.eav.attributes.listing')
                             ->with('success', 'Attribute deleted');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function massDelete(Request $request){
        try{
            $ids = request('mass_ids');
            if(is_null($ids)){
                throw new Exception('Invalid Ids');
            }
            Attribute::destroy($ids);
            return redirect()->route('admin.system.eav.attributes.listing')->with('success','Records deleted');
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
        }
    }

    public function massExport(Request $request)
    {
        $ids = $request->input('mass_ids', []);
    
        $columns = $request->input('visible_columns', '');
        $columns = $columns ? explode(',', $columns) : ['id'];
        $columns = array_unique($columns);
    
        $tableColumns = (new Attribute)->getConnection()->getSchemaBuilder()->getColumnListing((new Attribute)->getTable());
    
        $columns = array_intersect($columns, $tableColumns);
    
        $query = Attribute::query();
    
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }
    
        $records = $query->get($columns);
    
        $filename = 'export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        $callback = function() use ($records, $columns) {
            $file = fopen('php://output', 'w');
    
            fputcsv($file, $columns);
    
            foreach ($records as $record) {
                $row = [];
                foreach ($columns as $col) {
                    $row[] = $record->{$col};
                }
                fputcsv($file, $row);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }

    public function getGroupsByEntity($entityTypeId)
    {
        $groups = Group::where('entity_type_id', $entityTypeId)
            ->pluck('code', 'group_id');

        return response()->json($groups);
    }

}
