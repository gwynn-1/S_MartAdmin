<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class StaffApiController extends Controller
{
    private $staff;
    public function __construct()
    {
        $this->staff = new User();
    }

    //
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['status' => 'failed',"error"=>"Unauthorized"], 401);
        }

        return response()->json([
            "status"=>"success",
            "data"=>[
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => auth('api')->factory()->getTTL() * 60*12
            ]
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['status' => 'success']);
    }

    public function checkLogin(){
        try {
            $r = auth('api')->check();
            if($r){
                return response()->json([
                    "status"=>"success"
                ]);
            }else{
                return response()->json([
                    "status"=>"failed"
                ]);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            // do whatever you want to do if a token is expired
            dd($e);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            // do whatever you want to do if a token is invalid
            dd($e);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            dd($e);
            // do whatever you want to do if a token is not present
        }
    }

    public function getStaff(Request $request){
        $id = auth('api')->user()->staff_id;
        $oResult = $this->staff->getStaff($id);
//        dd(auth('api')->user());
        return response()->json([
            "status"=>"success",
            "data"=>$oResult->toArray()
        ]);
    }
}
