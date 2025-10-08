<?php

namespace Modules\Cron\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Cron\Models\Cron;
use Modules\Cron\Models\Cron\Schedule;
use Modules\Cron\View\Components\Cron\Listing\Edit;
use Illuminate\Support\Facades\Artisan;
use Cron\CronExpression;

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

    
    public function save(Request $request)
    {
        try {
            $params = $request->post('cron');
    
            if ($cronId = $request->get('id')) {
                $cron = Cron::find($cronId);
    
                if (!$cron || !$cron->cron_id) {
                    throw new Exception("Invalid Request ID");
                }
    
                $cron->update($params);
            } else {
                $cron = Cron::create($params);
            }
    
            if ($cron && $cron->cron_id) {
                return redirect()->route('admin.cron.listing')
                                 ->with('success', 'Record saved successfully');
            } else {
                throw new Exception('Something went wrong while saving the record');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    

    public function edit($id)
    {
        try {
            $event = Cron::find($id);
    
            if (!$event || !$event->cron_id) {
                throw new Exception("Invalid Request");
            }
    
            $edit = $this->model(Edit::class);
            $edit->row($event);
    
            $layout = $this->layout();
            $content = $layout->child('content');
            $content->child('edit', $edit);
    
            return $layout->render();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    

    public function delete(Request $request)
    {
        try {
            $cron = Cron::find($request->id);
    
            if (!$cron || !$cron->cron_id) {
                throw new Exception("Invalid Request");
            }
    
            $cron->delete(); // cron_schedules will be deleted automatically if FK is cascade
    
            return redirect()->route('admin.cron.listing')->with('success', 'Record deleted');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function massDelete(Request $request)
    {
        try {
            $ids = $request->input('mass_ids');
    
            if (empty($ids) || !is_array($ids)) {
                throw new Exception('Invalid IDs');
            }
    
            $crons = Cron::whereIn('cron_id', $ids)->get();
    
            foreach ($crons as $cron) {
                $cron->delete();
            }
    
            return redirect()->route('admin.cron.listing')->with('success', 'Records deleted');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    

    public function run($id)
    {
        $cron = Cron::find($id);
    
        if (!$cron || !$cron->cron_id) {
            return redirect()->back()->with('error', 'Invalid Cron ID');
        }
    
        // Create schedule/log entry (Pending)
        $schedule = Schedule::create([
            'cron_id'   => $cron->cron_id,
            'status'    => 0, // Pending
            'started_at'=> now(),
            'log'       => null,
        ]);
    
        try {
            $output = '';
    
            if ($cron->class && $cron->method) {
                if (!class_exists($cron->class)) {
                    throw new Exception("Class {$cron->class} not found");
                }
                $object = new $cron->class();
    
                if (!method_exists($object, $cron->method)) {
                    throw new Exception("Method {$cron->method} not found in class {$cron->class}");
                }
    
                $result = $object->{$cron->method}();
                $output = is_string($result) ? $result : json_encode($result);
            } elseif ($cron->command) {
                Artisan::call($cron->command);
                $output = Artisan::output();
            } else {
                throw new Exception("No valid command or class-method defined for this cron");
            }
    
            // Update schedule with result
            $schedule->update([
                'finished_at' => now(),
                'status'      => 1, // Success
                'log'         => $output,
            ]);
    
            // Calculate and update next run
            $nextRun = CronExpression::factory($cron->expression)->getNextRunDate();
            $cron->update([
                'last_run_at' => now(),
                'next_run_at' => $nextRun->format('Y-m-d H:i:s'),
            ]);
    
            return redirect()->back()->with('success', 'Cron executed successfully');
    
        } catch (Exception $e) {
            // Update schedule as failure
            $schedule->update([
                'finished_at' => now(),
                'status'      => 2, // Failure
                'log'         => $e->getMessage(),
            ]);
    
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
}

