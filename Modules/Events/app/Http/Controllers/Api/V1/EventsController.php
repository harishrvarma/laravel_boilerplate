<?php

namespace Modules\Events\Http\Controllers\Api\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Events\Models\Event;
use Modules\Events\View\Components\Event\Listing\Edit;

class EventsController extends Controller
{
    public function listing(){
        $events = Event::all();
        return response()->json($events);
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
                return response()->json($event);
            }
            else{
                throw new Exception('Something went wrong');
            }
        }
        catch(Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }

    public function delete(Request $request){
        try{

            $event = Event::find($request->id);
            if(!$event->id){
                throw new Exception("Invalid Request");
            }
            $event->delete();
            return response()->json(['success'=>'Record deleted']);
        }
        catch (Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }

    }
}

