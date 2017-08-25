<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salt extends Model
{
    protected $table = 'user_salt';

    public static function create($uid)
    {
        $salt = new Salt;
        $salt->uid = $uid;
        $salt->salt = $uid . hash('sha1', uniqid(($uid + 351104) . rand()));
        $salt->save();
        return $salt;
    }
}
