<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Admin\Models\AdminRole;
use Modules\Admin\Models\AdminRoleResource;

class RoleController extends BackendController
{

     public function listing(Request $request)
    {
        
        $listing = new \Modules\Admin\View\Components\AdminRole\Listing();
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $role = new AdminRole();
        $edit = new \Modules\Admin\View\Components\AdminRole\Listing\Edit();
        $edit->row($role);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            $params = $request->post('role');

            if($id = $request->get('id')){
                $role = AdminRole::find($id);
                if(!$role->id){
                    throw new Exception("Invalid Request ID");
                }
                $role->update($params);
            }
            else{
                $role = AdminRole::create($params);
            }
            $params = $request->post('resource');

            AdminRoleResource::where('role_id', $role->id)->delete();
            
            if (!is_null($params) && isset($params['resource_id'])) {
                $resourceIds = array_unique($params['resource_id']);
                foreach ($resourceIds as $value) {
                    AdminRoleResource::create([
                        'role_id' => $role->id,
                        'resource_id' => $value,
                    ]);
                }
            }
            if($role->id){
                return redirect()->route('admin.role.listing')->with('success','Record saved');
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
            $role = AdminRole::find($id);
            if(!$role->id){
                throw new Exception("Invalid Request");
            }
            
            $edit = new \Modules\Admin\View\Components\AdminRole\Listing\Edit();
            $edit->row($role);
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

            $role = AdminRole::find($request->id);
            if(!$role->id){
                throw new Exception("Invalid Request");
            }
            $role->delete();
            return redirect()->route('admin.role.listing')->with('success','Record deleted');
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
            AdminRole::destroy($ids);
            return redirect()->route('admin.role.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}
