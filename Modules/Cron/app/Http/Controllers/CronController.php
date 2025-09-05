<?php

namespace Modules\Cron\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Cron\Models\Cron;
use Modules\Cron\View\Components\Cron\Listing\Edit;

class CronController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = $this->block(\Modules\Cron\View\Components\Cron\Listing::class);
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {
        $event = $this->model(Cron::class);
        $edit =  $this->block(Edit::class);
        $edit->row($event);
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

     public function save(Request $request){
        try{
            $params = $request->post('cron');

            if($id = $request->get('id')){
                $event = Cron::find($id);
                if(!$event->id){
                    throw new Exception("Invalid Request ID");
                }
                $event->update($params);
            }
            else{
                $event = Cron::create($params);
            }
            if($event->id){
                return redirect()->route('admin.cron.listing')->with('success','Record saved');
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
            $event = Cron::find($id);
            if(!$event->id){
                throw new Exception("Invalid Request");
            }
            $edit = $this->model(Edit::class);
            $edit->row($event);
            $layout = $this->layout();
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

            $event = Cron::find($request->id);
            if(!$event->id){
                throw new Exception("Invalid Request");
            }
            $event->delete();
            return redirect()->route('admin.cron.listing')->with('success','Record deleted');
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
            Cron::destroy($ids);
            return redirect()->route('admin.core.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }

    }
}

