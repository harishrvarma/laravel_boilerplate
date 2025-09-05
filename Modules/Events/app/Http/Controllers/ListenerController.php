<?php

namespace Modules\Events\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Events\Models\Event\Listener;
use Modules\Events\View\Components\Listener\Listing\Edit;

class ListenerController extends BackendController
{
    public function add()
    {
        $listener = $this->model(Listener::class);
        $edit =  $this->block(Edit::class);
        $edit->row($listener);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request,$id,$event_id){
        try{
            $params = $request->post('listener');
            if($event_id){
                $params['event_id'] = $event_id;
            }
            if($id){
                $listener = Listener::find($id);

                if(!$listener->id){
                    throw new Exception("Invalid Request ID");
                }
                $listener->update($params);
            }
            else{
                $listener = Listener::create($params);
            }
            if($listener->id){
                return redirect()->to(urlx('admin.event.edit',['id'=>$listener->event_id]));
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
            $listener = Listener::find($id);
            if(!$listener->id){
                throw new Exception("Invalid Request");
            }
            $edit = $this->model(Edit::class);
            $edit->row($listener);
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

            $listener = Listener::find($request->id);
            if(!$listener->id){
                throw new Exception("Invalid Request");
            }
            $listener->delete();
            return redirect()->to(urlx('admin.event.edit',['id'=>$listener->event_id]));
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }

}

