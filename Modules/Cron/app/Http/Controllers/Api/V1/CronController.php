<?php

namespace Modules\Cron\Http\Controllers\Api\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Cron\Models\Cron;
use Modules\Cron\View\Components\Cron\Listing\Edit;

class CronController extends Controller
{

    public function listing(){
        $crons = Cron::all();
        return response()->json($crons);
    }

    public function save(Request $request){
        try{
            $params = $request->post('cron');

            if($id = $request->get('id')){
                $cron = Cron::find($id);
                if(!$cron->id){
                    throw new Exception("Invalid Request ID");
                }
                $cron->update($params);
            }
            else{
                $cron = Cron::create($params);
            }
            if($cron->id){
                return response()->json($cron);
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

            $event = Cron::find($request->id);
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

