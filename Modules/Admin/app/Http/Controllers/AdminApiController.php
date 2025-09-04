<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\Models\Admin;
use Illuminate\Support\Facades\Hash;
 

class AdminApiController extends Controller
{
    public function listing(){
        $admins = Admin::all();
        return response()->json($admins);
    }

    public function save(Request $request){
        try{
            $params = $request->post('admin');
            if($id = $request->get('id')){
                $admin = Admin::find($id);
                if(!$admin->id){
                    throw new Exception("Invalid Request ID");
                }
                $admin->update($params);
            }
            else{
                $admin = Admin::create($params);
            }
            if($admin->id){
                return response()->json($admin);
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
            $admin = Admin::find($request->id);
            if(!$admin->id){
                throw new Exception("Invalid Request");
            }
            $admin->delete();
            return response()->json(['success'=>'Record deleted']);
        }
        catch (Exception $e){
            return response()->json(['error'=>$e]);
        }
    }
}