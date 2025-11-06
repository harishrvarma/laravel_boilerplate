<?php

namespace Modules\Eav\Http\Controllers\Eav;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Eav\View\Components\Eav\Entities\Listing as Listing;
use Modules\Eav\View\Components\Eav\Entities\Listing\Edit as Edit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Modules\Eav\Models\Eav\Attribute\Group;

class EntityController extends BackendController
{
    public function listing(Request $request)
    {
        try {
            $layout  = $this->layout();
            $layout->title('Manage Entities');

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

            $attribute = $this->model(Type::class);
            $row       = $this->block(Edit::class)->row($attribute);

            $content = $layout->child('content')->child('edit', $row);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th);
        }
    }

    public function edit($id)
    {
        try{
            $layout  = $this->layout();
            $layout->title('Add/Edit Type');
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $row = Type::find($id);
            if(!$row){
                throw new Exception("Invalid Request");
            }
            $edit = $this->model(Edit::class)->row($row);
            $content = $layout->child('content')->Child('edit', $edit);
            return $layout->render();
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
        }
    }

    public function save(Request $request)
    {
        try {
            $data = $request->post('Types');

            $entityType = !empty($data['entity_type_id'])
                ? Type::findOrFail($data['entity_type_id'])
                : new Type();

            $entityType->fill($data);
            $entityType->save();

            if (empty($data['entity_type_id'])) {
                Group::create([
                    'entity_type_id' => $entityType->entity_type_id,
                    'code' => 'General',
                    'position' => 1,
                ]);
            }


            return redirect()->route('admin.eav.entities.listing')
                             ->with('success', 'Enttity Type saved');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            if(!$request->id){
                throw new Exception("Invalid Request");
            }
            $row = Type::find($request->id);
            if(!$row){
                throw new Exception("Invalid Request");
            }
            $row->delete();

            return redirect()->route('admin.eav.entities.listing')
                             ->with('success', 'Entity deleted');
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
            Type::destroy($ids);
            return redirect()->route('admin.eav.entities.listing')->with('success','Records deleted');
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
    
        $tableColumns = (new Type)->getConnection()->getSchemaBuilder()->getColumnListing((new Type)->getTable());
    
        $columns = array_intersect($columns, $tableColumns);
    
        $query = Type::query();
    
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
    public function structure($id)
    {
        try {
            $entityType = Type::findOrFail($id);
            $code = strtolower($entityType->code);
    
            $sourcePath = storage_path('Json/Eav.json');
            $targetPath = storage_path("Json/" . ucfirst($code) . ".json");
    
            if (!File::exists($sourcePath)) {
                return back()->with('error', 'Eav.json template not found at: ' . $sourcePath);
            }
    
            if (!File::exists($targetPath)) {
                $json = File::get($sourcePath);
                $data = json_decode($json, true);
    
                if (empty($data['tables'])) {
                    return back()->with('error', 'Invalid Eav.json structure.');
                }
    
                $data['module'] = ucfirst($code);
    
                foreach ($data['tables'] as &$table) {
                    if (!empty($table['name'])) {
                        $table['name'] = str_replace('table_', "{$code}_", $table['name']);
                    }
                }
    
                File::put($targetPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
    
            $command = "php8.4 artisan module:scaffold storage/Json/" . ucfirst($code) . ".json";
    
            return redirect()->back()->with([
                'success_message' => "Schema JSON file has been created successfully.",
                'module_code' => $code,
                'command' => $command
            ]);
    
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
}
