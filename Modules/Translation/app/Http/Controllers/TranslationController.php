<?php

namespace Modules\Translation\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Translation\Models\Translation;
use Modules\Translation\Models\TranslationLocale;
use Modules\Translation\View\Components\Translation\Listing\Edit;
use Modules\Translation\View\Components\TranslationLocale\Edit as LocaleEdit;
use Modules\Translation\Services\DatabaseLoader;

 
class TranslationController extends BackendController
{
    public function listing(Request $request)
    {
        $listing = $this->block(\Modules\Translation\View\Components\Translation\Listing::class);
        $layout = $this->layout();
        $content = $layout->child('content');
        $content->child('listing',$listing);
        return $layout->render();
    }

    public function add()
    {
        $admin = $this->model(Translation::class);
        $edit =  $this->block(Edit::class);
        $edit->row($admin);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);
        return $layout->render();
    }

    public function addLocale()
    {
        $admin = $this->model(TranslationLocale::class);
        $edit =  $this->block(LocaleEdit::class);
        $edit->row($admin);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('editLocale', $edit);
        return $layout->render();
    }

    public function save(Request $request)
    {
        try {
            $params = $request->post('translation');
    
            if (empty($params['module'])) {
                throw new \Exception("Please select at least one module");
            }
    
            $modules = is_array($params['module']) ? $params['module'] : [$params['module']];
    
            $locale = TranslationLocale::find($params['locale_id']);
            if (!$locale) {
                throw new \Exception("Invalid locale selected");
            }
            $localeId = $locale->id;
    
            $group = $params['group'];
            $key   = $params['key'];
            $value = $params['value'];
    
            if ($id = $request->get('id')) {
                $translation = Translation::findOrFail($id);
    
                foreach ($modules as $moduleName) {
                    $translation->locale_id = $localeId;
                    $translation->group = $group;
                    $translation->key = $key;
                    $translation->value = $value;
                    $translation->module = $moduleName;
                    $translation->save();
                }
            } else {
                foreach ($modules as $moduleName) {
                    $translation = Translation::firstOrNew([
                        'locale_id' => $localeId,
                        'group'     => $group,
                        'key'       => $key,
                        'module'    => $moduleName,
                    ]);
                    $translation->value = $value;
                    $translation->save();
                }
            }
    
            return redirect()
                ->route('admin.system.translation.listing')
                ->with('success', 'Record(s) saved successfully');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function saveLocale(Request $request)
    {
        try {
            $params = $request->post('translationLocale');
    
            if ($TranslationLocaleId = $request->get('id')) {
                $TranslationLocale = TranslationLocale::find($TranslationLocaleId);
    
                if (!$TranslationLocale || !$TranslationLocale->id) {
                    throw new Exception("Invalid Request ID");
                }
    
                $TranslationLocale->update($params);
            } else {
                $TranslationLocale = TranslationLocale::create($params);
            }
    
            if ($TranslationLocale && $TranslationLocale->id) {
                return redirect()->route('admin.system.translation.listing')
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
        try{
            $translation = Translation::find($id);
            if(!$translation->id){
                throw new Exception("Invalid Request");
            }
            $edit = $this->model(Edit::class);
            $edit->row($translation);
            $layout  = $this->layout();
            $content = $layout->child('content');
            $content->Child('edit', $edit);
            return $layout->render();
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }
    }

    public function delete(Request $request)
    {
        try {
            $translation = Translation::findOrFail($request->id);
            $translation->delete();
    
            return redirect()->route('admin.system.translation.listing')
                             ->with('success', 'Record deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function massDelete(Request $request)
    {
        try {
            $ids = $request->input('mass_ids', []);
            if (empty($ids)) {
                throw new \Exception('Invalid IDs');
            }
    
            Translation::massDelete($ids);
    
            return redirect()->route('admin.system.translation.listing')
                             ->with('success', 'Records deleted');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function massExport(Request $request)
    {
        $ids = $request->input('mass_ids', []);
    
        $columns = $request->input('visible_columns', '');
        $columns = $columns ? explode(',', $columns) : ['id'];
        $columns = array_unique($columns);
    
        $tableColumns = (new Translation)->getConnection()->getSchemaBuilder()->getColumnListing((new Translation)->getTable());
    
        $columns = array_intersect($columns, $tableColumns);
    
        $query = Translation::query();
    
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }
    
        $records = $query->get($columns);
    
        $filename = 'export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
    
        $callback = function() use ($records, $columns) {
            $file = fopen('php://output', 'w');
    
            fputcsv($file, $columns);
    
            foreach ($records as $record) {
                $row = [];
                foreach ($columns as $col) {
                    $row[] = $record->{$col};
                }
                fputcsv($file, $row);
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
}
