<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;

class User extends Model
{
    protected $table = 'user';

    // Relationships

    public function salt() {
        return $this->hasOne('App\Models\Salt', 'uid', 'id');
    }

    // Relationships end

    public static function sendActivationMail($user) {
        $link = self::generateLink($user->email, $user->salt);
        $html =
        '<html>
        <body>
            <a href = "http://' . $link . '">请点击此处您激活您的账户</a>
        </body>
        </html>';
        Mail::send($user->email, '请您激活您的账户', $html);
    }

    public static function create($email, $password) {
        list($firstName, $lastName) = self::gennerateName();
        $user = new User();
        $user->email = $email;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->user_name = $firstName . $lastName;
        $user->gender = '0';
        $user->password = '';
        $user->save();
        $user->password = self::createPwd($user, $password);
        $user->save();
        self::sendActivationMail($user);
        return $user;
    }

    public static function changeStatus($email) {
        $res = User::where('email', $email)
                   ->update(['status' => 1]);
        return $res;
    }

    public static function generateLink($email, $salt) {
        $validate = md5(md5($email . $salt));
        $link = env('APP_BASE_DOMAIN') . '/activation' . '?email=' . $email . '&validate=' . $validate;
        return $link;
    }

    public static function gennerateName() {
        $firstName = [
            '欧阳', '上官', '东方', '南宫', '诸葛', '宇文', '令狐', '轩辕', '慕容', '独孤',
          ];
        $lastName = [
            '炳武', '筱鸢', '幕霖', '佩瞳', '冉升', '樱雪', '天龙', '月瑶', '江山', '梦心',
          ];
        shuffle($firstName);
        shuffle($lastName);
        return [current($firstName), current($lastName)];
    }

    public static function checkLink($email, $activation) {
        if ($user = User::where('email', $email)->first()) {
            if ($activation == md5(md5($email . $user->salt->salt))) {
                return true;
            }
        }
        return false;
    }

    public static function checkPwd($email, $password) {
        $record = User::where('email', $email)
               ->where('password', User::getPwd($password))
               ->first();
        if (empty($record)) {
            return false;
        }
        return $record;
    }

    public static function emailExisted($email) {
        return User::where('email', $email)->exists();
    }

    private static function createPwd($user, $password) {
        if (!isset($user->salt)) {
            $salt = Salt::create($user->id);
        }
        return static::hashPwd($password, $salt->salt);
    }

    private static function getPwd($user, $password) {
        return static::hashPwd($password, $user->salt->salt);
    }

    private static function hashPwd($password, $salt) {
        return hash('sha256', $password . $salt);
    }

    public static function checkUserPwd($user, $password) {
        return $user->password === self::hashPwd($password, $user->salt->salt);
    }

    public static function changePwd($email, $password, $newPassword) {
        $record = User::where('email', $email)
                    ->get()
                    ->toArray();
        if(empty($record)) {
            return 'NOT_EXISTS';
        }
        $user = head($record);
        if ($user['password'] !== $password) {
            return 'PASSWORD_UNAUTHORIZED';
        }
        User::where('id', $user['id'])->update(['password' => $newPassword]);
            return true;
    }

    public static function getInfoByUid($uid, $param) {
        $res = User::where('id', $uid)
                   ->select($param)
                   ->get()
                   ->toArray();
        return current($res);
    }

    public static function updateInfoById($id, $param) {
        $res = User::where('id', $id)
                   ->update($param);
        return $res;
    }

    public static function selectTagsById($uid) {
        $res = User::where('id', $uid)
                   ->select('tags')
                   ->get()
                   ->toArray();
        $data = current($res);
        $tags = $data['tags'];
        return $tags;
    }

    public static function isFollowing($tags, $uid = 1) {
        $userInfo = User::where('id', $uid)
                        ->select('tags')
                        ->get()
                        ->toArray();
        $tag = $userInfo[0]['tags'];
        $res = json_decode($tag);
        if (in_array($tags, $res)) {
            return 1;
        }
        return 0;
    }

    public static function UpdateInfoByUid($uid, $param) {
        return User::where('id', $uid)
                   ->update($param);
    }
}
