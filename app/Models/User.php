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
    $user = new User();
    $user->name = static::generateUsername($email);
    $user->password = '';
    $user->email = $email;
    $user->save();
    $user->password = User::createPwd($user, $password);
    $user->save();
    return $user;
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
    $record = User::where('email', $email)->first();

    return !empty($record);
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
    return hash('sha256', $password.$salt);
  }

  private static function generateUsername($email) {
    if (isset($email)) {
      return explode('@', $email)[0];
    }
    return '用户'.substr(base_convert(rand(1, 9).time().rand(1, 9), 10, 16), 0, 10);
  }
}
