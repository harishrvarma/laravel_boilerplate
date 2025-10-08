<?php

namespace Modules\Settings\View\Components\Listing\Edit\Tabs;

use Modules\Core\View\Components\Listing\Edit\Form as CoreForm;
use Modules\Settings\Models\ConfigKey;
use Modules\Settings\Models\ConfigValue;

class General extends CoreForm
{
    public function __construct(){
        parent::__construct();
    }

    public function prepareFields()
    {
        $id = request('tab');
    
        if (!$id) {
            $firstTab = \DB::table('config_groups')->orderBy('id')->first();
            $id = $firstTab ? $firstTab->id : null;
        }
    
        $keys = ConfigKey::whereHas('groups', fn($q) => $q->where('config_groups.id', $id))->get();
    
        foreach ($keys as $key) {
            $value = ConfigValue::where('config_key_id', $key->id)->value('value');
    
            $fieldData = [
                'id'       => $key->key_name,
                'name'     => "config[{$key->key_name}]",
                'label'    => $key->label,
                'type'     => $key->input_type,
                'required' => $key->is_required ? 'required' : '',
                'value'    => $value ?? $key->default_value,
            ];
    
            if (in_array($key->input_type, ['select', 'radio', 'checkbox'])) {
    
                $options = []; // default fallback

                if (!empty($key->options_source)) {
                    try {
                        if (str_contains($key->options_source, '::')) {
                            [$class, $method] = explode('::', $key->options_source, 2);

                            if (class_exists($class)) {
                                $instance = app($class);

                                if (method_exists($instance, $method)) {
                                    // Call non-static method
                                    $options = $instance->{$method}();
                                } elseif (method_exists($class, $method)) {
                                    // Fallback: call static method
                                    $options = $class::$method();
                                } else {
                                    // 🚫 Method not found → just leave options blank
                                    \Log::warning("⚠️ Method {$method} not found in {$class}");
                                    $options = [];
                                }

                                // Normalize result
                                if ($options instanceof \Illuminate\Support\Collection) {
                                    $options = $options->toArray();
                                }

                                if (!is_array($options)) {
                                    \Log::warning("⚠️ Invalid return type from {$key->options_source}");
                                    $options = [];
                                }

                            } else {
                                \Log::warning("⚠️ Class {$class} not found for options source {$key->options_source}");
                            }
                        } else {
                            \Log::warning("⚠️ Invalid format for options source '{$key->options_source}' (expected 'Class::method')");
                        }

                    } catch (\Throwable $e) {
                        \Log::error("⚠️ Failed to load dynamic options for {$key->key_name}: {$e->getMessage()}");
                        $options = [];
                    }
                }

                
    
                if (empty($options)) {
                    $options = $key->options()
                        ->orderBy('position')
                        ->get()
                        ->mapWithKeys(fn($option) => [$option->option_value => $option->option_label])
                        ->toArray();
                }
    
                $fieldData['options'] = $options;
    
                if ($key->input_type === 'select' || $key->input_type === 'radio') {
                    $fieldData['selected'] = $value ?? $key->default_value;
                } elseif ($key->input_type === 'checkbox') {
                    $selectedValues = is_string($value) ? explode(',', $value) : (array) $value;
                    $fieldData['selected'] = $selectedValues;
                }
            }
    
            $this->field($key->key_name, $fieldData);
        }
    
        return $this;
    }
    
}

