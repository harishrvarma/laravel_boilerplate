<?php
namespace Modules\Events\Event;

use Modules\Events\Models\Event;

class EventDispatcher
{
    public function dispatch(string $eventCode, $payload = [])
    {
        $event = Event::where('code', $eventCode)->where('status', 1)->first();

        if (! $event) {
            return false;
        }

        $listeners = $event->listeners()
            ->where('status', 1)
            ->orderBy('order_no')
            ->get();

        foreach ($listeners as $listener) {
            $this->callListener($listener, $payload);
        }

        return true;
    }

    protected function callListener($listener, $payload)
    {
        try {
            if (!class_exists($listener->component)) {
                return;
            }
    
            $class = app()->make($listener->component);
    
            if (method_exists($class, $listener->method)) {
                call_user_func_array([$class, $listener->method], [$payload]);
            }
        } catch (\Throwable $e) {
            return;
        }
    }
}