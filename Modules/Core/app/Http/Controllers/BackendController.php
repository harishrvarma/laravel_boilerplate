<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\View\Components\Layout;
use Illuminate\Support\Facades\App;

class BackendController extends Controller
{
    protected $layout = null;

    public function __construct()
    {
        $locale = session('admin.locale', app()->getLocale());
        App::setLocale($locale);
    }

    protected function layout(){
        if(is_null($this->layout)){
            $this->layout = app()->make(Layout::class);
            $this->layout->layout($this->layout)->init();
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