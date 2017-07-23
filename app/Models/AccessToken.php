<?php

namespace App\Models;

use Request;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
  protected $table = 'user_access_token';

  // Relationships start

  public function user() {
    return $this->hasOne('App\Models\User', 'id', 'uid');
  }

  // Releationships end

  public static function create($uid) {
    $effective = date("Y-m-d H:i:s", time() + 30 * 24 * 60 * 60);
    $ip = Request::getClientIp();
    $token = new AccessToken();
    $token->uid = $uid;
    $token->accessToken = "$uid|$ip|$effective";
    $token->effective = $effective;
    $token->save();
    return $token->accessToken;
  }

  public static function getEffectiveToken($token) {
    $record = static::where('accessToken', $token)
                   ->where('effective', '>', date("Y-m-d H:i:s", time()))
                   ->first();
    return $record;
  }
}
