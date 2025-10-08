<?php
namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
Use Modules\Core\Http\Controllers\BackendController;

class CoreController extends BackendController
{
    public function saveColumn(Request $request)
    {
        $column = $request->input('column');
        $visible = filter_var($request->input('visible'), FILTER_VALIDATE_BOOLEAN);

        $module = $request->input('module', 'default');
        $sessionKey = "hidden_columns_{$module}";
        dd($sessionKey);

        $hidden = session()->get($sessionKey, []);

        if ($visible) {
            // Remove from hidden list
            $hidden = array_diff($hidden, [$column]);
        } else {
            // Add to hidden list
            if (!in_array($column, $hidden)) {
                $hidden[] = $column;
            }
        }

        session([$sessionKey => array_values($hidden)]);

        return response()->json([
            'status' => 'ok',
            'hidden_columns' => $hidden,
        ]);
    }
}
