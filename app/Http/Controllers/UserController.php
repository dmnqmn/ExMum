<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccessToken;
use App\Models\Helper;

use General;
use Config;
use Validator;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function postRegister(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:50',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => 'REGISTERS_WRONG_MESSAGE'], 400);
        }
        if (User::emailExisted($email)) {
            return response()->json(['error' => 'EMAIL_EXISTED'], 403);
        }
        User::create($email, $password);
        return response()->json();
    }

    public function getActivation(Request $request) {
        $email = $request->input('email');
        $activation = $request->input('activation');
        if (User::checkLink($email, $activation) == true) {
            return response()->json();
            // $token = AccessToken::create($user->id);
            // $cookie = makeCookie('EXMUM_U', $token, General::ACCESS_TOKEN_MAX_AGE);
            // return response()->json(null)->withCookie($cookie);
        }
        return response()->json(['error' => 'REGISTERS_WRONG_MESSAGE'], 400);
    }

    public function postLogin(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $validate = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' =>  'required|max:50',
            ]); 
        if ($validate->fails()) {
            return response()->json(['error' => 'REGISTERS_WRONG_MESSAGE'], 400);
        }
        $res = User::checkEmailpwd($email, $password);
        if ($res === true) {
            return response()->json();
        }
        return response()->json(['error' => $res], 403);
    }

    public function postChangePwd(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:50',
            ]);
        if ($validate->fails()) {
            return response()->json(['error' => 'REGISTERS_WRONG_MESSAGE'], 400);
        }
        $res = User::changePwd($email, $password);
        if ($res === true) {
            return response()->json();
        }
        return response()->json(['error' => $res], 403);       
    }
}








