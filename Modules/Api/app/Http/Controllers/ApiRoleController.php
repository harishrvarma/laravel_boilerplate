<?php

namespace Modules\Api\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Api\Models\ApiRole;
use Modules\Api\Models\ApiRoleResource;
Use Modules\Core\Http\Controllers\BackendController;


class ApiRoleController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = new \Modules\Api\View\Components\ApiRole\Listing();
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $apiRole = new ApiRole();
        $edit = new \Modules\Api\View\Components\ApiRole\Listing\Edit();
        $edit->row($apiRole);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

    public function save(Request $request)
    {
        try {
            if ($request->get('tab') == 'resource') {
                $role = ApiRole::findOrFail($request->id);
    
                $resourceIds = $request->input('resources', []);
    
                $role->resources()->sync($resourceIds);
    
                return redirect()->to(urlx('admin.apirole.edit'))->with('success', 'Record saved');
            } else {
                $params = $request->post('apirole');
    
                if ($id = $request->get('id')) {
                    $apiRole = ApiRole::find($id);
                    if (!$apiRole || !$apiRole->id) {
                        throw new Exception("Invalid Request ID");
                    }
                    $apiRole->update($params);
                } else {
                    $apiRole = ApiRole::create($params);
                }
    
                $params = $request->post('apiresource');
    
                ApiRoleResource::where('role_id', $apiRole->id)->delete();
    
                if (!is_null($params) && isset($params['resource_id'])) {
                    $resourceIds = array_unique($params['resource_id']);
                    foreach ($resourceIds as $value) {
                        ApiRoleResource::create([
                            'role_id'    => $apiRole->id,
                            'resource_id'=> $value,
                        ]);
                    }
                }
    
                if ($apiRole->id) {
                    return redirect()->route('admin.apirole.edit', ['id' => $apiRole->id])
                                     ->with('success', 'Record saved');
                } else {
                    throw new Exception('Something went wrong');
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try{
            $apiRole = ApiRole::find($id);
            if(!$apiRole->id){
                throw new Exception("Invalid Request");
            }
            
            $edit = new \Modules\Api\View\Components\ApiRole\Listing\Edit();
            $edit->row($apiRole);
            $layout  = $this->layout();
            $content = $layout->child('content');
            $content->child('edit', $edit);
            return $layout->render();
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }
    }

    public function delete(Request $request){
        try{

            $apiRole = ApiRole::find($request->id);
            if(!$apiRole->id){
                throw new Exception("Invalid Request");
            }
            $apiRole->delete();
            return redirect()->route('admin.apirole.listing')->with('success','Record deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }

    public function massDelete(Request $request){
        try{
            $ids = request('mass_ids');
            if(is_null($ids)){
                throw new Exception('Invalid Ids');
            }
            ApiRole::destroy($ids);
            return redirect()->route('admin.apirole.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }
    }

    public function massExport(Request $request)
    {
        $ids = $request->input('mass_ids', []);
    
        // Get visible columns
        $columns = $request->input('visible_columns', '');
        $columns = $columns ? explode(',', $columns) : ['id'];
        $columns = array_unique($columns);
    
        // Get table columns from DB
        $tableColumns = (new ApiRole)->getConnection()->getSchemaBuilder()->getColumnListing((new ApiRole)->getTable());
    
        // Keep only columns that exist in DB
        $columns = array_intersect($columns, $tableColumns);
    
        // Build query
        $query = ApiRole::query();
    
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
    
            // Header row
            fputcsv($file, $columns);
    
            // Data rows
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
