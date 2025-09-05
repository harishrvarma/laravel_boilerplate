<?php

namespace Modules\Settings\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Settings\Models\ConfigGroup;
use Modules\Settings\Models\ConfigKey;
use Modules\Settings\Models\ConfigValue;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Settings\View\Components\Config;

class ConfigController extends BackendController
{
    public function listing(Request $request)
    {
        try 
        {
            $configBlock = $this->block(Config::class);
            $configBlock->row($this->model(ConfigGroup::class));
            $layout  = $this->layout();
            $content = $layout->child('content');
            $content->child('configBlock', $configBlock);
            return $layout->render();
        } catch (Exception $te) {
            
        }
    }

    public function add()
    {
        $configGroup = $this->block(ConfigGroup::class);

        $edit = new \Modules\Settings\View\Components\Listing\Edit();
        $edit->row($configGroup);

        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);

        return $layout->render();
    }

    public function addFields()
    {
        $configKey = $this->block(ConfigKey::class);
        $editFields = $this->model(\Modules\Settings\View\Components\Listing\EditFields::class);
        $editFields->row($configKey);

        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('editFields', $editFields);

        return $layout->render();
    }


    public function save(Request $request)
    {
        try {
            $params = $request->post('config');
            if ($id = $request->get('id')) {
                $configGroup = ConfigGroup::find($id);
                if (!$configGroup || !$configGroup->id) {
                    throw new \Exception("Invalid Request ID");
                }
                $configGroup->update($params);
            } else {
                $configGroup = ConfigGroup::create($params);
            }
    
            if ($configGroup && $configGroup->id) {
                return redirect()
                    ->route('admin.config.listing')
                    ->with('success', 'Configuration Group saved successfully');
            } else {
                throw new \Exception('Something went wrong while saving the Config Group');
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function saveFields(Request $request)
    {
        try {
            $params = $request->post('config');
            $tab = $request->get('activeTab');

            if (!$tab) {
                throw new \Exception('Invalid Config Group');
            }

            $group = ConfigGroup::where('id', $tab)->first();
            if (!$group) {
                throw new \Exception("Config Group '{$tab}' not found");
            }

            if ($id = $request->get('id')) {
                $configKey = ConfigKey::find($id);
                if (!$configKey || !$configKey->id) {
                    throw new \Exception("Invalid Config Key ID");
                }

                $configKey->update([
                    'key_name'      => $params['key_name'] ?? $configKey->key_name,
                    'label'         => $params['label'] ?? $configKey->label,
                    'input_type'    => $params['input_type'] ?? $configKey->input_type,
                    'is_required'   => isset($params['is_required']) ? 1 : 0,
                    'default_value' => $params['default_value'] ?? $configKey->default_value,
                ]);
            } else {
                $configKey = ConfigKey::create([
                    'key_name'      => $params['key_name'] ?? null,
                    'label'         => $params['label'] ?? null,
                    'input_type'    => $params['input_type'] ?? 'text',
                    'is_required'   => isset($params['is_required']) ? 1 : 0,
                    'default_value' => $params['default_value'] ?? null,
                ]);
            }

            if ($configKey && $configKey->id) {
                $configKey->groups()->syncWithoutDetaching([$group->id]);
            }

            return redirect()
                ->to(urlx('admin.config.listing', ['tab' => $tab],true));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function saveConfig(Request $request)
    {
        try {
            $params = $request->post('config');
            $groupId = $request->get('id');

            if (!$params) {
                throw new \Exception("No Data available to update");
            }

            if (!$groupId) {
                throw new \Exception("Invalid Config Group ID");
            }

            foreach ($params as $keyName => $value) {
                $configKey = ConfigKey::where('key_name', $keyName)
                    ->whereHas('groups', fn($q) => $q->where('config_groups.id', $groupId))
                    ->first();
    
                if (!$configKey) {
                    continue;
                }
    
                ConfigValue::updateOrCreate(
                    ['config_key_id' => $configKey->id,],['value' => $value,]
                );
            }
    
            return redirect()
                ->route('admin.config.listing', ['tab' => $groupId])
                ->with('success', 'Configuration saved successfully');
    
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $config = ConfigKey::find($id);
            if (!$config || !$config->id) {
                throw new Exception("Invalid Request");
            }

            $edit = new \Modules\Settings\View\Components\Config\Listing\Edit();
            $edit->row($config);

            $layout  = $this->layout();
            $content = $layout->getChild('content');
            $content->addChild('edit', $edit);
            return $layout->render();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $params = $request->post();
            $configGroup = ConfigGroup::findOrFail($params['id']);
            $configGroup->name = $params['title'];
            $configGroup->save();

            return redirect()->back()->with('success', 'Config group updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            dd($id);
            $config = ConfigGroup::find($id);
            if (!$config || !$config->id) {
                throw new Exception("Invalid Request");
            }
            $config->delete();

            return redirect()->route('admin.config.listing')->with('success', 'Config deleted');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // public function massDelete(Request $request)
    // {
    //     try {
    //         $ids = $request->post('mass_ids');
    //         if (is_null($ids)) {
    //             throw new Exception('Invalid Ids');
    //         }

    //         ConfigKey::destroy($ids);

    //         return redirect()->route('admin.config.listing')->with('success', 'Configs deleted');
    //     } catch (Exception $e) {
    //         return redirect()->back()->with('error', $e->getMessage());
    //     }
    // }
}
