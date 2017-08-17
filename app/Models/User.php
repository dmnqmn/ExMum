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

  public static function sendMail($email) {
      $link = self::generateLink($email);
      $html = 
      '<html>
      <body>
        <a href = "http://' . $link . '">请点击此处您激活您的账户</a> 
      </body>
      </html>';
      Mail::send($email, '请您激活您的账户', $html);
  }

  public static function create($email, $password) {
      if (! User::where('email', $email)->where('status', 0)->exists()) {
        list($firstName, $lastName) = self::gennerateName();
        $user = new User();
        $user->email = $email;
        $user->password = $password;
        $user->first_name = $firstName;
        $user->last_name = $lastName;
        $user->user_name = $firstName . $lastName;
        $user->gender = 'custom';
        $user->save();
      }
      self::sendMail($email);
      return true;
  }

  public static function generateLink($email) {
      $validate = md5(md5($email));
      $link = '192.168.33.20' . '/activation' . '?email=' .  $email . '&validate=' . $validate;
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
      if ($activation == md5(md5($email))) {
          return true;
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
      return User::where('email', $email)->where('status', 1)->exists();
  }

  private static function createPwd($user, $password) {
      return $password;
      if (!isset($user->salt)) {
        $salt = Salt::create($user->id);
      }
      return static::hashPwd($password, $salt->salt);
  }

  private static function getPwd($user, $password) {
      return static::hashPwd($password, $user->salt->salt);
  }

  private static function hashPwd($password, $salt) {
      return hash('sha256', $password.$salt);
  }

  private static function generateUsername($email) {
      if (isset($email)) {
        return explode('@', $email)[0];
      }
      return '用户'.substr(base_convert(rand(1, 9).time().rand(1, 9), 10, 16), 0, 10);
  }

  public static function checkEmailpwd($email, $password) {
      $res = User::where('email', $email)
                 ->where('status', 1)
                 ->get()
                 ->toArray();
      if (empty($res)) {
        return 'LOGIN_USER_NOT_FOUND';
      }
      if ($res[0]['password'] == $password) {
        return true;
      }
      return 'LOGIN_WRONG_PASSWORD';
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

  public static function getInfoById($id, $param) {
      $res = User::where('id', $id)
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
}
