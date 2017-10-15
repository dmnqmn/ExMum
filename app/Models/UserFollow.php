<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Salt;
use General;

class UserFollow extends Model
{
    protected $table = 'user_follow';

    const PAGE = 1;
    const RESULTS_PER_PAGE = 10;

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

    public static function selectFollowingByUid($uid, $page, $results_per_page) {
        $user_follow = static::paging(['uid' => $uid], $page, $results_per_page);
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

    public static function selectFollowByByUid($uid, $page, $results_per_page) {
        $user_follow = static::paging(['follow_uid' => $uid], $page, $results_per_page);
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

    private static function paging($sql, $page, $results_per_page) {
        $skip = ($page - 1) * $results_per_page;
        return UserFollow::where($sql)
                        ->skip($skip)
                        ->orderBy('id', 'desc')
                        ->take($results_per_page)
                        ->get()
                        ->toArray();
    }

    public static function total($sql) {
        return UserFollow::where($sql)->count();
    }
}
