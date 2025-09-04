<?php

namespace Modules\Core\View\Components\Listing\Edit;

use Modules\Core\View\Components\Block;
use Exception;

class Tabs extends Block
{
    /**
     * Blade template for this component
     * (Template::render() will use it).
     */
    protected $template = 'core::listing.edit.tabs';

    /**
     * List of all tabs
     */
    protected array $tabs = [];


    protected $tabFormat = [
        'key' => 'null',
        'title' => 'null',
        'tabClassName'=>'null',
    ];
    /**
     * Current active tab key
     */
    protected ?string $activeTabKey = null;

    // ---------------------------
    // Getter / Setter for tabs
    // ---------------------------
    public function __construct(){
        parent::__construct();
        $this->prepareTabs();
    }
    
    public function prepareTabs(){
        return $this;
    }
    
    public function tab(string $key, array $value = null, bool $reset = false)
    {
        if(is_null($key)){
            return $this;
        }

        if($reset == true){
            if(array_key_exists($key,$this->tabs)){
                unset($this->tabs[$key]);
            }
            return $this;
        }

        if(!is_null($value)){
            $this->tabs[$key] = array_merge($this->tabFormat, $value);
            return $this;
        }

        if(array_key_exists($key,$this->tabs)){
            return $this->tabs[$key];
        }
        return null;
    }

    public function tabs(array $tabs = null, bool $reset = false)
    {
        if($reset == true){
            $this->tabs = [];
            return $this;
        }

        if(!is_null($tabs)){
            foreach($tabs as $key => $tab){
                if(is_array($tab)){
                    $this->tab($key, $tab);
                }
            }
            return $this;
        }
        return $this->tabs;
    }
    
    // ---------------------------
    // Active Tab Management
    // ---------------------------

    public function activeTabKey($key = null)
    {
        if(!is_null($this->tabs)){
            $this->activeTabKey = array_key_first($this->tabs);
        }
        if(request('tab')){
             $this->activeTabKey = request('tab');
        }
        if(array_key_exists($key,$this->tabs)){
            $this->activeTabKey = $key;
        }
        return $this->activeTabKey;
    }

    public function activeTab(){
        return $this->tab($this->activeTabKey());
    }

}
