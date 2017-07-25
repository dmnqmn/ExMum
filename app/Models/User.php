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
        $user->phone = 1512456574; // TODO
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

  public static function changePwd($uid, $oldPassword, $newPassword) {
    $record = User::where('uid', $uid)
                  ->where('password', User::getPwd($oldPassword))
                  ->get()
                  ->toArray();
    if (empty($record)) {
        return false;
    } else {
        return User::where('uid', $uid)->update(['password' => User::createPwd($record, $newPassword)]);
    }
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
      $first = config('name.first');
      $last = config('name.last');
      shuffle($first);
      shuffle($last);
      return head($first) . head($last);
  }
}
