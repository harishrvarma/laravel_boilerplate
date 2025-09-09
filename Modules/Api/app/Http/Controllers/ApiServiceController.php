<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\Api\Models\ApiUser;


class ApiServiceController extends Controller
{
    public function generateToken(Request $request){
        try{
            $apiUser = ApiUser::where('email', $request->email)
                            ->where('password',$request->password)
                            ->first();

            if (!$apiUser) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            $scopes = $apiUser->resources()->pluck('code')->toArray();
            $token = $apiUser->createToken('Client Access Token', $scopes)->accessToken;
            return response()->json(['token'=>$token]);
        }
        catch (Exception $e){
            return response()->json(['error'=>$e->getMessage()]);
        }
    }
}