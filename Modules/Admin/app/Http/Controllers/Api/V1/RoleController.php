<?php

namespace Modules\Admin\Http\Controllers\Api\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Role;

class RoleController extends Controller
{
    public function listing(){
        $role = Role::all();
        return response()->json($role);
    }

    public function save(Request $request){
        try{
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
                return response()->json($role);
            }
            else{
                throw new Exception('Something went wrong');
            }
        }
        catch(Exception $e){
            return response()->json(['error'=>$e]);
        }
    }
    public function delete(Request $request){
        try{
            $role = User::find($request->id);
            if(!$role->id){
                throw new Exception("Invalid Request");
            }
            $role->delete();
            return response()->json(['success'=>'Record deleted']);
        }
        catch (Exception $e){
            return response()->json(['error'=>$e]);
        }
    }
}
