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
}
