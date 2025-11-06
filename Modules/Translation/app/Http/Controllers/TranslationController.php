<?php

namespace Modules\Translation\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Translation\Models\Translation;
use Modules\Translation\Models\TranslationLocale;
use Modules\Translation\View\Components\Translation\Listing\Edit;
use Modules\Translation\View\Components\TranslationLocale\Edit as LocaleEdit;
 
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
                throw new Exception("Please select at least one module");
            }
    
            $modules = is_array($params['module']) ? $params['module'] : [$params['module']];
    
            // ✅ Ensure locale_id exists
            $locale = TranslationLocale::find($params['locale_id']);
            if (!$locale) {
                throw new Exception("Invalid locale selected");
            }
    
            $localeId = $locale->id;
    
            if ($id = $request->get('id')) {
                $translation = Translation::find($id);
                if (!$translation) {
                    throw new Exception("Invalid Request ID");
                }
    
                $group = $params['group'];
                $key   = $params['key'];
    
                foreach ($modules as $moduleName) {
                    Translation::updateOrCreate(
                        [
                            'locale_id' => $localeId,   // ✅ use locale_id instead of locale
                            'group'     => $group,
                            'key'       => $key,
                            'module'    => $moduleName,
                        ],
                        [
                            'value'     => $params['value'],
                        ]
                    );
                }
            } else {
                foreach ($modules as $moduleName) {
                    Translation::create([
                        'locale_id' => $localeId,   // ✅ use locale_id instead of locale
                        'group'     => $params['group'],
                        'key'       => $params['key'],
                        'value'     => $params['value'],
                        'module'    => $moduleName,
                    ]);
                }
            }
    
            return redirect()
                ->route('admin.translation.listing')
                ->with('success', 'Record(s) saved');
    
        } catch (Exception $e) {
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
                return redirect()->route('admin.translation.listing')
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

    public function delete(Request $request){
        try{

            $translation = Translation::find($request->id);
            if(!$translation->id){
                throw new Exception("Invalid Request");
            }
            $translation->delete();
            return redirect()->route('admin.translation.listing')->with('success','Record deleted');
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
            Translation::destroy($ids);
            return redirect()->route('admin.admin.listing')->with('success','Records deleted');
        }
        catch (Exception $e){
            return redirect()->back()->with('error',$e);
        }
    }

    public function massExport(Request $request)
    {
        $ids = $request->input('mass_ids', []);
    
        // Get visible columns
        $columns = $request->input('visible_columns', '');
        $columns = $columns ? explode(',', $columns) : ['id'];
        $columns = array_unique($columns);
    
        // Get table columns from DB
        $tableColumns = (new Translation)->getConnection()->getSchemaBuilder()->getColumnListing((new Translation)->getTable());
    
        // Keep only columns that exist in DB
        $columns = array_intersect($columns, $tableColumns);
    
        // Build query
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
    
            // Header row
            fputcsv($file, $columns);
    
            // Data rows
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
