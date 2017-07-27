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
        ], [
            'email.required' => 'REGISTER_EMAIL_NEEDED',
            'email.email' => 'REGISTER_EMAIL_ILLEGAL',
            'password.required' => 'REGISTER_PASSWORD_NEEDED',
            'password.max' => 'REGISTER_PASSWORD_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        if (User::emailExisted($email)) {
            return response()->json(['error' => 'REGISTER_EMAIL_EXISTED'], 403);
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
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' =>  'required|max:50',
        ], [
            'email.required' => 'EMAIL_NEEDED',
            'email.email' => 'EMAIL_ILLEGAL',
            'password.required' => 'PASSWORD_NEEDED',
            'password.max' => 'PASSWORD_NEEDED',
        ]); 
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
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
        $newPassword = $request->input('newPassword');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:50',
            'newpassword' => 'required|max:50',
        ], [
            'email.required' => 'EMAIL_NEEDED',
            'email.email' => 'EMAIL_ILLEGAL',
            'password.required' => 'PASSWORD_PASSWORD_NEEDED',
            'password.max' => 'PASSWORD_PASSWORD_NEEDED',
            'newPassword.required' => 'PASSWORD_NEW_PASSWORD_NEEDED',
            'newPassword.max' => 'PASSWORD_NEW_PASSWORD_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $res = User::changePwd($email, $password, $newPassword);
        if ($res === true) {
            return response()->json();
        }
        return response()->json(['error' => $res], 403);       
    }
}








