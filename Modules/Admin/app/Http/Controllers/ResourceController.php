<?php

namespace Modules\Admin\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Admin\Models\Resource;

class ResourceController extends BackendController
{

     public function listing(Request $request)
    {
        
        $listing = new \Modules\Admin\View\Components\Resource\Listing();
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $resource = new Resource();
        $edit = new \Modules\Admin\View\Components\Resource\Listing\Edit();
        $edit->row($resource);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            $params = $request->post('resource');

            if($id = $request->get('id')){
                $resource = Resource::find($id);
                if(!$resource->id){
                    throw new Exception("Invalid Request ID");
                }
                $resource->update($params);
            }
            else{
                $resource = Resource::create($params);
            }
            if($resource->id){
                return redirect()->route('admin.system.resource.listing')->with('success','Record saved');
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
            $resource = Resource::find($id);
            if(!$resource->id){
                throw new Exception("Invalid Request");
            }
            
            $edit = new \Modules\Admin\View\Components\Resource\Listing\Edit();
            $edit->row($resource);
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

            $resource = Resource::find($request->id);
            if(!$resource->id){
                throw new Exception("Invalid Request");
            }
            $resource->delete();
            return redirect()->route('admin.system.resource.listing')->with('success','Record deleted');
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
            Resource::destroy($ids);
            return redirect()->route('admin.system.resource.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}
