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

  public static function create($email, $password) {
      $subject = '激活邮件';
      $html = static::generateLink($email);
      if (User::where('email', $email)->where('status', 0)->exists()) {
          Mail::send($email, $subject, $html);
      } else {
        $user = new User();
        $user->name = static::generateUsername($email);
        $user->password = User::createPwd($user, $password);
        $user->email = $email;
        $user->status = 0;
        $user->save();
        Mail::send($email, $subject, $html);
      }
      return true;
  }

  public static function generateLink($email) {
      $link = 'email=' . $email . '&activation=' . md5(md5($email));
      return 'http://192.168.33.20/activation?' . $link;
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
    if (empty($res)) {
      return false;
    }
    return $res;
  }

    public static function reviseProfile($param) {
    $res = User::where('id', $param['id'])->update(
      ['first_name' => $param['firstname'], 
      'last_name'   => $param['lastname'], 
      'user_name'   => $param['username'], 
      'gender'      => $param['gender'], 
      'phone'       => $param['phone'], 
      'email'       => $param['email'], 
      'avatar'      => $param['avatar'], 
      'infomation'  => $param['infomation']]
      );
    if (empty($res)) {
      return "false";
    }
    return "true";
  }
}
