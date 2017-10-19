<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;

use Validator;

class SettingController extends BaseController
{
    public function getSettings() {
        $param = ['user_name', 'gender', 'description'];
        $userinfo = User::getInfoByUid(\Globals::$user->id, $param);

        $jsVars = [
            ['user', \Globals::$user],
            ['userinfo', $userinfo]
        ];

        return view('settings')
            ->with('user', \Globals::$user)
            ->with('jsVars', $jsVars);
    }
}
