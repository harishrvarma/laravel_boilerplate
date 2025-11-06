<?php

namespace Modules\Eav\Http\Controllers\Eav\Attribute;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Eav\Models\Eav\Attribute\Group;
use Modules\Eav\View\Components\Eav\Attributes\Group\Listing as Listing;
use Modules\Eav\View\Components\Eav\Attributes\Group\Listing\Edit as Edit;
use Modules\Translation\Models\TranslationLocale;

class GroupController extends BackendController
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

    /**
     * Add Attribute
     */
    public function add()
    {
        try {
            $layout = $this->layout();
            $layout->title('Add/Edit Attribute Group');

            $attribute = $this->model(Group::class);
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
            $layout->title('Add/Edit Attribute Group');
    
            if (!$id) {
                throw new \Exception("Invalid Request");
            }
    
            $row = Group::with([
                'translations',
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
            $data = $request->post('groups', []);
            $labels = $request->post('label', []);
    
            if (empty($data['entity_type_id']) || empty($data['code'])) {
                return back()->with('error', 'Entity Type and Code are required.');
            }
    
            $group = !empty($data['group_id'])
                ? Group::findOrFail($data['group_id'])
                : new Group();
    
            $exists = Group::where('entity_type_id', $data['entity_type_id'])
                ->where('code', $data['code'])
                ->when(!empty($data['group_id']), fn($q) => $q->where('group_id', '!=', $data['group_id']))
                ->exists();
    
            if ($exists) {
                return back()->with('error', 'Duplicate group code for this entity type.');
            }
    
            $group->fill($data);
            $group->save();
    
            foreach ($labels as $langCode => $translationData) {
                $displayName = trim($translationData['name'] ?? '');
                if ($displayName === '') continue;
    
                $locale = TranslationLocale::where('code', $langCode)->first();
                if (!$locale) continue;
    
                $group->translations()->updateOrCreate(
                    ['lang_id' => $locale->id],
                    ['name' => $displayName]
                );
            }
    
            return redirect()
                ->route('admin.eav.attributes.group.listing')
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
            $row = Group::find($request->id);
            if(!$row){
                throw new Exception("Invalid Request");
            }
            $row->delete();

            return redirect()->route('admin.eav.attributes.group.listing')
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
            Group::destroy($ids);
            return redirect()->route('admin.eav.attributes.group.listing')->with('success','Records deleted');
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
    
        $tableColumns = (new Group)->getConnection()->getSchemaBuilder()->getColumnListing((new Group)->getTable());
    
        $columns = array_intersect($columns, $tableColumns);
    
        $query = Group::query();
    
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
}
