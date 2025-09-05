<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\User\Role;
use Modules\Admin\View\Components\Admin\Listing\Edit;
 

class AdminController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = $this->block(\Modules\Admin\View\Components\Admin\Listing::class);
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $admin = $this->model(User::class);
        $edit =  $this->block(Edit::class);
        $edit->row($admin);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            $params = $request->post('admin');
            if(isset($params['password'])){
                $params['password'] = Hash::make($params['password']);
            }

            if($id = $request->get('id')){
                $admin = User::find($id);
                if(!$admin->id){
                    throw new Exception("Invalid Request ID");
                }
                $admin->update($params);
            }
            else{
                $admin = User::create($params);
            }
            $params = $request->post('role');
            
            Role::where('user_id', $admin->id)->delete();
            
            if (!is_null($params) && isset($params['role_id'])) {
                $resourceIds = array_unique($params['role_id']);
                foreach ($resourceIds as $value) {
                    Role::create([
                        'user_id' => $admin->id,
                        'role_id' => $value,
                    ]);
                }
            }
            if($admin->id){
                return redirect()->route('admin.admin.listing')->with('success','Record saved');
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
            $admin = User::find($id);
            if(!$admin->id){
                throw new Exception("Invalid Request");
            }
            $edit = $this->model(Edit::class);
            $edit->row($admin);
            $layout  = $this->layout();
            $content = $layout->child('content');
            $content->Child('edit', $edit);
            return $layout->render();
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }
    }

    public function delete(Request $request){
        try{

            $admin = User::find($request->id);
            if(!$admin->id){
                throw new Exception("Invalid Request");
            }
            $admin->delete();
            return redirect()->route('admin.admin.listing')->with('success','Record deleted');
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
            User::destroy($ids);
            return redirect()->route('admin.admin.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}
