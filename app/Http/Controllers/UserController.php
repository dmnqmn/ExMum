<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function login(Request $request) {
        $account = $request->input('account');
        $password = $request->input('password');
        $phone = $request->input('phone');
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'password' => 'required|max:50',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return "信息不对！";
        }
        return "ok";
    }

    public function tokens(Request $request) {
        $account = $request->input('account');
        $password = $request->input('password');
        if (User::exist($account) == false) {
            $authToken = User::create($account, $password);
        } else {
            $record = User::checkUserPwd($account, $password);
            if ($record == false) {
                return 'Pwd is wrong';
            }
            $authToken = Token::get($record->uid);
        }
        return $authToken;
    }

    public function userPassword(Request $request) {
        $authToken = $request->input('authToken');
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');
        $uid = Token::getUid($authToken);
        if (User::changePwd($uid, $oldPassword, $newPassword) == false) {
            return 'Pwd is wrong';
        }
        return 'Ok';
    }
}
