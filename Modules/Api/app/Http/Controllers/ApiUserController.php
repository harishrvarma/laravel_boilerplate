<?php

namespace Modules\Api\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Api\Models\ApiUser;
use Modules\Api\Models\ApiUserRole;
Use Modules\Core\Http\Controllers\BackendController;


class ApiUserController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = new \Modules\Api\View\Components\ApiUser\Listing();
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $apiUser = new ApiUser();
        $edit = new \Modules\Api\View\Components\ApiUser\Listing\Edit();
        $edit->row($apiUser);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            $params = $request->post('apiuser');

            if($id = $request->get('id')){
                $apiUser = ApiUser::find($id);
                if(!$apiUser->id){
                    throw new Exception("Invalid Request ID");
                }
                $apiUser->update($params);
            }
            else{
                $apiUser = ApiUser::create($params);
            }
            $params = $request->post('apirole');
            
            ApiUserRole::where('user_id', $apiUser->id)->delete();

            if (!is_null($params) && isset($params['role_id'])) {
                $roleIds = array_unique($params['role_id']);
                foreach ($roleIds as $value) {
                    ApiUserRole::create([
                        'user_id' => $apiUser->id,
                        'role_id' => $value,
                    ]);
                }
            }
            if($apiUser->id){
                return redirect()->route('admin.system.apiuser.edit',['id'=>$apiUser->id])->with('success','Record saved');
            }
            else{
                throw new Exception('Something went wrong');
            }
        }
        catch(Exception $e){
            return redirect()->back()->with('error',$e);
        }
    }

    public function edit($id)
    {
        try{
            $apiUser = ApiUser::find($id);
            if(!$apiUser->id){
                throw new Exception("Invalid Request");
            }
            
            $edit = new \Modules\Api\View\Components\ApiUser\Listing\Edit();
            $edit->row($apiUser);
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

            $apiUser = ApiUser::find($request->id);
            if(!$apiUser->id){
                throw new Exception("Invalid Request");
            }
            $apiUser->delete();
            return redirect()->route('admin.system.apiuser.listing')->with('success','Record deleted');
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
            ApiUser::destroy($ids);
            return redirect()->route('admin.system.apiuser.listing')->with('success','Records deleted');
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
        $tableColumns = (new ApiUser)->getConnection()->getSchemaBuilder()->getColumnListing((new ApiUser)->getTable());
    
        // Keep only columns that exist in DB
        $columns = array_intersect($columns, $tableColumns);
    
        // Build query
        $query = ApiUser::query();
    
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
