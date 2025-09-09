<?php

namespace Modules\Api\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Api\Models\ApiRole;
use Modules\Api\Models\ApiRoleResource;
Use Modules\Core\Http\Controllers\BackendController;


class ApiRoleController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = new \Modules\Api\View\Components\ApiRole\Listing();
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $apiRole = new ApiRole();
        $edit = new \Modules\Api\View\Components\ApiRole\Listing\Edit();
        $edit->row($apiRole);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

    public function save(Request $request)
    {
        try {
            if ($request->get('tab') == 'resource') {
                $role = ApiRole::findOrFail($request->id);
    
                $resourceIds = $request->input('resources', []);
    
                $role->resources()->sync($resourceIds);
    
                return redirect()->to(urlx('admin.apirole.edit'))->with('success', 'Record saved');
            } else {
                $params = $request->post('apirole');
    
                if ($id = $request->get('id')) {
                    $apiRole = ApiRole::find($id);
                    if (!$apiRole || !$apiRole->id) {
                        throw new Exception("Invalid Request ID");
                    }
                    $apiRole->update($params);
                } else {
                    $apiRole = ApiRole::create($params);
                }
    
                $params = $request->post('apiresource');
    
                ApiRoleResource::where('role_id', $apiRole->id)->delete();
    
                if (!is_null($params) && isset($params['resource_id'])) {
                    $resourceIds = array_unique($params['resource_id']);
                    foreach ($resourceIds as $value) {
                        ApiRoleResource::create([
                            'role_id'    => $apiRole->id,
                            'resource_id'=> $value,
                        ]);
                    }
                }
    
                if ($apiRole->id) {
                    return redirect()->route('admin.apirole.edit', ['id' => $apiRole->id])
                                     ->with('success', 'Record saved');
                } else {
                    throw new Exception('Something went wrong');
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try{
            $apiRole = ApiRole::find($id);
            if(!$apiRole->id){
                throw new Exception("Invalid Request");
            }
            
            $edit = new \Modules\Api\View\Components\ApiRole\Listing\Edit();
            $edit->row($apiRole);
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

            $apiRole = ApiRole::find($request->id);
            if(!$apiRole->id){
                throw new Exception("Invalid Request");
            }
            $apiRole->delete();
            return redirect()->route('admin.apirole.listing')->with('success','Record deleted');
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
            ApiRole::destroy($ids);
            return redirect()->route('admin.apirole.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}
