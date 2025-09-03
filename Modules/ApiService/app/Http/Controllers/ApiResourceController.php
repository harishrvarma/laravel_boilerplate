<?php

namespace Modules\ApiService\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\ApiService\Models\ApiResource;
Use Modules\Core\Http\Controllers\BackendController;


class ApiResourceController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = new \Modules\ApiService\View\Components\ApiResource\Listing();
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $apiResource = new ApiResource();
        $edit = new \Modules\ApiService\View\Components\ApiResource\Listing\Edit();
        $edit->row($apiResource);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            $params = $request->post('apiresource');

            if($id = $request->get('id')){
                $apiResource = ApiResource::find($id);
                if(!$apiResource->id){
                    throw new Exception("Invalid Request ID");
                }
                $apiResource->update($params);
            }
            else{
                $apiResource = ApiResource::create($params);
            }
            if($apiResource->id){
                return redirect()->route('admin.apiresource.edit',['id'=>$apiResource->id])->with('success','Record saved');
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
            $apiResource = ApiResource::find($id);
            if(!$apiResource->id){
                throw new Exception("Invalid Request");
            }
            
            $edit = new \Modules\ApiService\View\Components\ApiResource\Listing\Edit();
            $edit->row($apiResource);
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

            $apiResource = ApiResource::find($request->id);
            if(!$apiResource->id){
                throw new Exception("Invalid Request");
            }
            $apiResource->delete();
            return redirect()->route('admin.apiresource.listing')->with('success','Record deleted');
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
            ApiResource::destroy($ids);
            return redirect()->route('admin.apiresource.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}
