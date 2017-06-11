<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'Token';

    public static function create($uid) {
        $token = new Token();
        $token->uid = $uid;
        $token->auth_token = md5(md5($uid));
        $token->save();
        return $token->token;
    }

    public static function get($uid) {
        return Token::where('uid', $uid)->value('auth_token');
    }

    public static function getUid($token) {
        return Token::where('auth_token', $token)->value('uid');
    }
}
