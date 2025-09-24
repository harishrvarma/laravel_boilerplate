<?php

namespace Modules\Translation\Http\Controllers;

use Exception;
Use Modules\Core\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use Modules\Translation\Models\Translation;
use Modules\Translation\Models\TranslationLocale;
use Modules\Translation\View\Components\Translation\Listing\Edit;
 
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
}
