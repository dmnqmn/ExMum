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
        $user = User::create($email, $password);
        $token = AccessToken::create($user->id);
        return response()->json()->withCookie(makeCookie('EXMUM_U', $token));
    }

    public function getActivation(Request $request) {
        $email = $request->input('email');
        $validate = $request->input('validate');
        if (md5(md5($email)) == $validate) {
            User::changeStatus($email);
            return redirect()->route('home');
        }
        return response()->json(['error' => 'Invalid authentication address'], 403);
    }

    public function postLogin(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' =>  'required|max:50',
        ], [
            'email.required' => 'LOGIN_EMAIL_NEEDED',
            'email.email' => 'LOGIN_EMAIL_ILLEGAL',
            'password.required' => 'LOGIN_PASSWORD_NEEDED',
            'password.max' => 'LOGIN_PASSWORD_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'LOGIN_USER_NOT_FOUND'], 404);
        }
        $res = User::checkUserPwd($user, $password);
        if ($res) {
            $token = AccessToken::create($user->id);
            return response()->json()->withCookie(makeCookie('EXMUM_U', $token));
        }
        return response()->json(['error' => 'LOGIN_WRONG_PASSWORD'], 403);
    }

    public function postChangePwd(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $newPassword = $request->input('newPassword');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:50',
            'newPassword' => 'required|max:50',
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

    public function postUserInfo(Request $request) {
        $userInfo = \Globals::$user;
        if (is_null($userInfo)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $uid = $userInfo->id;
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $gender = $request->input('gender');
        $description = $request->input('description');
        $validate = Validator::make($request->all(), [
            'first_name'    => 'max:10',
            'last_name'     => 'max:10',
            'gender'        => 'nullable|integer|between:0,2',
            'description'   => 'max:255',
        ], [
            'first_name.max'  => 'USER_INFO_FIRST_NAME_TOO_LONG',
            'last_name.max'   => 'USER_INFO_LAST_NAME_TOO_LONG',
            'description.max' => 'USER_INFO_DESCRIPTION_TOO_LONG',
            'gender.between'  => 'USER_INFO_GENDER_INVALID',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $param = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'gender' => is_null($gender) ? 0 : $gender,
            'description' => $description,
        ];
        foreach ($param as $k => $v) {
            $data = $request->input($k);
            if (!isset($data)) {
                unset($param[$k]);
            }
        }
        $res = User::UpdateInfoByUid($uid, $param);
        if ($res !== 0) {
            return response()->json();
        }
        return response()->json(['error' => 'UPDATE_FAILED'], 403);
    }

    public function getInfoByUid(Request $request) {
        $userInfo = \Globals::$user;
        if (is_null($userInfo)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $uid = $userInfo->id;
        $param = ['first_name', 'last_name', 'gender', 'description'];
        $res = User::getInfoByUid($uid, $param);
        return response()->json($res);
    }

}
