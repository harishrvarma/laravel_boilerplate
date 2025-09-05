<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Admin\Models\Role;

class RoleController extends BackendController
{

     public function listing(Request $request)
    {
        
        $listing = new \Modules\Admin\View\Components\Role\Listing();
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $role = new Role();
        $edit = new \Modules\Admin\View\Components\Role\Listing\Edit();
        $edit->row($role);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            if($request->get('tab') == 'resource'){
                $role = Role::findOrFail($request->id);

                $resourceIds = $request->input('resources', []);

                $role->resources()->sync($resourceIds);

                return redirect()->to(urlx('admin.role.edit'))->with('success','Record saved');
            }
            else{
                $params = $request->post('role');

                if($id = $request->get('id')){
                    $role = Role::find($id);
                    if(!$role->id){
                        throw new Exception("Invalid Request ID");
                    }
                    $role->update($params);
                }
                else{
                    $role = Role::create($params);
                }
                
                if($role->id){
                    return redirect()->route('admin.role.listing')->with('success','Record saved');
                }
                else{
                    throw new Exception('Something went wrong');
                }
            }
        }
        catch(Exception $e){
            return redirect()->back()->with('error',$e);
        }
    }

    public function edit($id)
    {
        try{
            $role = Role::find($id);
            if(!$role->id){
                throw new Exception("Invalid Request");
            }
            
            $edit = new \Modules\Admin\View\Components\Role\Listing\Edit();
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

            $role = Role::find($request->id);
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
            Role::destroy($ids);
            return redirect()->route('admin.role.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}
