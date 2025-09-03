<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\View\Components\Layout;

class BackendController extends Controller
{
    protected $layout = null;

    protected function layout(){
        if(is_null($this->layout)){
            $this->layout = app()->make(Layout::class);
        }
        return $this->layout;
    }

    protected function render()
    {
        return $this->layout()->render();
    }

    protected function block($class) {
        return app()->make($class);
    }

    protected function model($class) {
        return app()->make($class);
    }
}
