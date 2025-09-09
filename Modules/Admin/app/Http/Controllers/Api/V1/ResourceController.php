<?php

namespace Modules\Admin\Http\Controllers\Api\V1;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Models\Resource;

class ResourceController extends Controller
{
    public function listing(){
        $resource = Resource::all();
        return response()->json($resource);
    }
}
