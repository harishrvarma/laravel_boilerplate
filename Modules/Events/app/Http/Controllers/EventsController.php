<?php

namespace Modules\Events\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Events\Models\Event;
use Modules\Events\View\Components\Event\Listing\Edit;

class EventsController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = $this->block(\Modules\Events\View\Components\Event\Listing::class);
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {

        $event = $this->model(Event::class);
        $edit =  $this->block(Edit::class);
        $edit->row($event);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            $params = $request->post('event');

            if($id = $request->get('id')){
                $event = Event::find($id);
                if(!$event->id){
                    throw new Exception("Invalid Request ID");
                }
                $event->update($params);
            }
            else{
                $event = Event::create($params);
            }
            if($event->id){
                return redirect()->route('admin.event.listing')->with('success','Record saved');
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
            $event = Event::find($id);
            if(!$event->id){
                throw new Exception("Invalid Request");
            }
            $edit = $this->model(Edit::class);
            $edit->row($event);
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

            $event = Event::find($request->id);
            if(!$event->id){
                throw new Exception("Invalid Request");
            }
            $event->delete();
            return redirect()->route('admin.event.listing')->with('success','Record deleted');
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
            Event::destroy($ids);
            return redirect()->route('admin.event.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}

