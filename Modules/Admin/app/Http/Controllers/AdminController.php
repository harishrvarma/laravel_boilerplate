<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\User\Role;
use Modules\Admin\View\Components\Admin\Listing\Edit;
use Modules\Admin\View\Components\Admin\Listing;
 

class AdminController extends BackendController
{
    public function listing(Request $request)
    {
        try {
            $layout  = $this->layout();
            $layout->title('Manage Admins');
            $listing = $this->block(Listing::class);
            $content = $layout->child('content')->child('listing',$listing);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
        }
        
    }

    public function add()
    {
        try {
            $layout = $this->layout();
            $layout->title('Add/Edit Admins');
            $admin  = $this->model(User::class);
            $row    =  $this->block(Edit::class)->row($admin);
            $content = $layout->child('content')->child('edit', $row);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
        }
    }

    public function edit($id)
    {
        try{
            $layout  = $this->layout();
            $layout->title('Add/Edit Admins');
            if(!$id){
                throw new Exception("Invalid Request");
            }
            $row = User::find($id);
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
            $params = $request->post('admin');
    
            if (!empty($params['password'])) {
                $params['password'] = Hash::make($params['password']);
            }
    
            $row = !empty($params['id'])
            ? User::findOrFail($params['id'])
            : new User();
            
            $row->fill($params);
            $row->save();
    
            Role::where('user_id', $row->getKey())->delete();
    
            $roles = $request->post('role');
            if (!empty($roles['role_id'])) {
                $resourceIds = array_unique($roles['role_id']);
                foreach ($resourceIds as $roleId) {
                    Role::create([
                        'user_id' => $row->getKey(),
                        'role_id' => $roleId,
                    ]);
                }
            }
            if($request->get('continue')){
                return redirect()->route('admin.admin.edit', ['id' => $row->getKey()])->with('success', 'Record saved');
            }
            return redirect()->route('admin.admin.listing')->with('success', 'Record saved');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
        }
    }

    public function delete(Request $request){
        try{
            if(!$request->id){
                throw new Exception("Invalid Request");
            }

            $row = User::find($request->id);
            if(!$row){
                throw new Exception("Invalid Request");
            }
            $row->delete();
            return redirect()->route('admin.admin.listing')->with('success','Record deleted');
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
        }
    }

    public function massDelete(Request $request){
        try{
            $ids = request('mass_ids');
            if(is_null($ids)){
                throw new Exception('Invalid Ids');
            }
            User::destroy($ids);
            return redirect()->route('admin.admin.listing')->with('success','Records deleted');
        }
        catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
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
        $tableColumns = (new User)->getConnection()->getSchemaBuilder()->getColumnListing((new User)->getTable());
    
        // Keep only columns that exist in DB
        $columns = array_intersect($columns, $tableColumns);
    
        // Build query
        $query = User::query();
    
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
