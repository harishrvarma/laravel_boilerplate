<?php
namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
Use Modules\Core\Http\Controllers\BackendController;
use Modules\Core\Services\Scaffold\ModuleScaffolder;
use Modules\Core\View\Components\Scaffold\Listing\Edit;
use Modules\Admin\Models\User;
use Illuminate\Support\Facades\Artisan;

class ScaffoldController extends BackendController
{
    public function add()
    {
        try {
            $layout = $this->layout();
            $layout->title('Add Module');
            $scaffold = (object) [];
            $row    =  $this->block(Edit::class)->row($scaffold);
            $content = $layout->child('content')->child('edit', $row);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error',$th);
        }
    }

    public function save(Request $request)
    {
        $options = json_decode($request->input('scaffold'), true);

        if (isset($options['base_path']) && $options['base_path'] === '__DIR__/') {
            $options['base_path'] = base_path();
        }

        if (!$options) {
            return back()->with('error', 'Invalid scaffold definition');
        }
        $scaffolder = new ModuleScaffolder($options);
        $moduleName = $scaffolder->generate();

        // Artisan::call('module:enable', ['module' => $moduleName]);
        // Artisan::call('module:seed', ['module' => $moduleName]);

        return back()->with('success', 'Module scaffold generated successfully!');
    }
}
