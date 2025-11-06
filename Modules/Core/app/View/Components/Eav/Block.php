<?php
namespace Modules\Core\View\Components\Eav;

use Exception;
use Illuminate\View\Component;
use App\View\Components\Urlx;

class Block extends Component
{
    protected $template = 'core::components.template';
    protected $children = [];
    protected $parent;
    protected $row;
    protected $layout;

    public function __construct(){
        $this->prepareChildern();
    }

    public function template($template = null){
        if(!is_null($template)){
            $this->template = $template;
            return $this;
        }

        return $this->template;
    }
    public function render()
    {
        return view($this->template(),array_merge(['me'=>$this],get_object_vars($this)));
    }

    public function children($children = null, $reset = false)
    {
        if($reset == true){
            $this->children = [];
            return $this;
        }

        if(!is_null($children)){
            foreach($children as $key => $child){
                if(is_array($child)){
                    $this->child($key, $child);
                }
            }
            return $this;
        }
        return $this->children;
    }

    public function child(string $key, object $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->children)){
                unset($this->children[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->children[$key] = $value;
            return $this;
        }

        if(array_key_exists($key,$this->children)){
            return $this->children[$key];
        }
        return null;
    }

    public function block($class, array $data = [])
    {
        $block = app()->makeWith($class, ['tabData' => $data]); 
        $block->parent($this)->layout($this->layout());
        return $block;
    }
    

    public function model($class) {
        return app()->make($class);
    }

    public function urlx(?string $route = null, array $params = [], bool $reset = false, ?string $fragment = null){
        return (new Urlx())->url($route, $params, $reset, $fragment);
    }

    public function row($row = null){
        if($row){
            $this->row = $row;
            return $this;
        }
        return $this->row;
    }

    public function parent($parent = null){
        if($parent){
            $this->parent = $parent;
            return $this;
        }
        return $this->parent;
    }

    public function layout($layout = null){
        if($layout){
            $this->layout = $layout;
            return $this;
        }
        return $this->layout;
    }

    public function prepareChildern()  {
        return $this;
    }
}
