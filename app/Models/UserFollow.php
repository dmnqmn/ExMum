<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;
use General;

class UserFollow extends Model
{
    protected $table = 'user_follow';

    const FOLLOW_USERS = 10;

	public static function create($uid, $follow_uid) {
        $isExist = static::exists($uid, $follow_uid);
        if ($isExist == true) {
            return $isExist;
        }
        $user_follow = new UserFollow;
        $user_follow->uid = $uid;
        $user_follow->follow_uid = $follow_uid;
        return $user_follow->save();
    }

    public static function unFollow($uid, $follow_uid) {
        $res = UserFollow::where('uid', $uid)
                         ->where('follow_uid', $follow_uid)
                         ->delete();
        return $res;
    }

    public static function exists($uid, $follow_uid) {
        return UserFollow::where('uid', $uid)
                            ->where('follow_uid', $follow_uid)
                            ->exists();
    }

    public static function selectFollowByUid($uid, $last_id) {
        $user_follow = UserFollow::where('uid', $uid)
                                 ->where('id', '<', $last_id)
                                 ->select('id', 'follow_uid')
                                 ->orderBy('id', 'desc')
                                 ->limit(self::FOLLOW_USERS)
                                 ->get()
                                 ->toArray();
        $follow_uids = [];
        foreach ($user_follow as $v) {
            $follow_uids[] = $v['follow_uid'];
        }
        $user_name = User::whereIn('id', $follow_uids)
                         ->select('id', 'user_name')
                         ->get();
        $res = [];
        foreach ($user_name as $value) {
            foreach ($user_follow as $v) {
                if ($value['id'] == $v['follow_uid']) {
                    $res[] = [
                        'id' => $v['id'],
                        'user_name' => $value['user_name'],
                    ];
                }
            }
        }
        return $res;
    }
}
