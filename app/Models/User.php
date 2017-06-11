<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'User';

    public static function exist($account) {
        return User::where('account', $account)->exists();
    }

    public static function create($account, $password) {
        $user = new User();
        $user->account = $account;
        $user->password = User::createPwd($password);
        $user->save();
        return Token::create($user->id);
    }

    public static function checkUserPwd($account, $password) {
        $record = User::where('account', $account)
            ->where('password', User::createPwd($password))
            ->first();
        if (empty($record)) {
            return false;
        }
        return $record;
    }

    private static function createPwd($password) {
        return md5(md5($password));
    }

    public static function changePwd($uid, $oldPassword, $newPassword) {
        $record = User::where('uid', $uid)
            ->where('password', User::createPwd($oldPassword))
            ->get()
            ->toArray();
        if (empty($record)) {
            return false;
        } else {
            return User::where('uid', $uid)->update(['password' => User::createPwd($newPassword)]);
        }
    }

}
