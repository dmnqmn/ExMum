<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccessToken;
use App\Models\Helper;
use App\Models\UserFollow;

use General;
use Config;
use Validator;

class UserController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function postRegister(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:50',
        ], [
            'email.required' => 'REGISTER_EMAIL_NEEDED',
            'email.email' => 'REGISTER_EMAIL_ILLEGAL',
            'password.required' => 'REGISTER_PASSWORD_NEEDED',
            'password.max' => 'REGISTER_PASSWORD_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        if (User::emailExisted($email)) {
            return response()->json(['error' => 'REGISTER_EMAIL_EXISTED'], 403);
        }
        $user = User::create($email, $password);
        $token = AccessToken::create($user->id);
        return response()->json()->withCookie(makeCookie('EXMUM_U', $token));
    }

    public function getActivation(Request $request) {
        $email = $request->input('email');
        $validate = $request->input('validate');
        if (md5(md5($email)) == $validate) {
            User::changeStatus($email);
            return redirect()->route('home');
        }
        return response()->json(['error' => 'Invalid authentication address'], 403);
    }

    public function postLogin(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' =>  'required|max:50',
        ], [
            'email.required' => 'LOGIN_EMAIL_NEEDED',
            'email.email' => 'LOGIN_EMAIL_ILLEGAL',
            'password.required' => 'LOGIN_PASSWORD_NEEDED',
            'password.max' => 'LOGIN_PASSWORD_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'LOGIN_USER_NOT_FOUND'], 404);
        }
        $res = User::checkUserPwd($user, $password);
        if ($res) {
            $token = AccessToken::create($user->id);
            return response()->json()->withCookie(makeCookie('EXMUM_U', $token));
        }
        return response()->json(['error' => 'LOGIN_WRONG_PASSWORD'], 403);
    }

    public function postLogout(Request $request) {
        if (is_null(\Globals::$user) || is_null(\Globals::$access_token)) {
            return response()->json(['error' => 'LOGOUT_NOT_AUTHORIZED'], 403);
        }

        AccessToken::removeToken(\Globals::$access_token);
    }

    public function postChangePwd(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');
        $newPassword = $request->input('newPassword');
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|max:50',
            'newPassword' => 'required|max:50',
        ], [
            'email.required' => 'EMAIL_NEEDED',
            'email.email' => 'EMAIL_ILLEGAL',
            'password.required' => 'PASSWORD_PASSWORD_NEEDED',
            'password.max' => 'PASSWORD_PASSWORD_NEEDED',
            'newPassword.required' => 'PASSWORD_NEW_PASSWORD_NEEDED',
            'newPassword.max' => 'PASSWORD_NEW_PASSWORD_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $res = User::changePwd($email, $password, $newPassword);
        if ($res === true) {
            return response()->json();
        }
        return response()->json(['error' => $res], 403);
    }

    public function postUserInfo(Request $request) {
        $userInfo = \Globals::$user;
        if (is_null($userInfo)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $user_name = $request->input('user_name');
        $uid = $userInfo->id;
        $gender = $request->input('gender');
        $description = $request->input('description');
        $validate = Validator::make($request->all(), [
            'user_name'     => 'max:50',
            'gender'        => 'integer|between:0,2',
            'description'   => 'max:255',
        ], [
            'user_name.max'      => 'USER_INFO_USER_NAME_TOO_LONG',
            'description.max'    => 'USER_INFO_DESCRIPTION_TOO_LONG',
            'gender.between'     => 'USER_INFO_GENDER_INVALID',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }

        $param = [
            'user_name'   => $user_name,
            'gender'      => is_null($gender) ? 0 : $gender,
            'description' => $description,
        ];
        foreach ($param as $k => $v) {
            $data = $request->input($k);
            if (!isset($data)) {
                unset($param[$k]);
            }
        }
        $res = User::UpdateInfoByUid($uid, $param);
        if ($res !== 0) {
            return response()->json();
        }
        return response()->json(['error' => 'UPDATE_FAILED'], 403);
    }

    public function getUserInfo(Request $request) {
        $user_info = \Globals::$user;
        if (is_null($user_info)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $uid = $user_info->id;
        $param = ['gender', 'description'];
        $res = User::getInfoByUid($uid, $param);
        return response()->json($res);
    }

    public function getUserPage(Request $request, $uid) {
        $visiting_user = User::find($uid);

        if (\Globals::$user) {
            $visiting_user_rel = [
                'following' => UserFollow::exists(\Globals::$user->id, $uid),
                'followed' => UserFollow::exists($uid, \Globals::$user->id)
            ];
        } else {
            $visiting_user_rel = ['following' => false, 'followed' => false];
        }

        $jsVars = [
            ['user', \Globals::$user],
            ['visiting_user', $visiting_user->publicInfo()],
            ['visiting_user_rel', $visiting_user_rel]
        ];

        return view('user')
            ->with('jsVars', $jsVars)
            ->with('visiting_user', $visiting_user->publicInfo())
            ->with('visiting_user_rel', $visiting_user_rel);
    }

    public function postFollow(Request $request) {
        $follow_uid = $request->input('follow_uid');
        $user_info = \Globals::$user;
        if (is_null($user_info)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $uid = $user_info->id;
        $res = UserFollow::create($uid, $follow_uid);
        return response()->json();
    }

    public function postUnFollow(Request $request) {
        $follow_uid = $request->input('follow_uid');
        $user_info = \Globals::$user;
        if (is_null($user_info)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $uid = $user_info->id;
        $res = UserFollow::unFollow($uid, $follow_uid);
        if ($res != 0) {
            return response()->json();
        }
        return response()->json(['error' => 'UN_FOLLOW_FAILED'], 400);
    }

    public function getFollowing(Request $request) {
        return $this->getFollowingOf($request, \Globals::$user);
    }

    public function getUserFollowing(Request $request, $uid) {
        return $this->getFollowingOf($request, User::find($uid));
    }

    public function getFollowedBy(Request $request) {
        return $this->getFollowedByOf($request, \Globals::$user);
    }

    public function getUserFollowedBy(Request $request, $uid) {
        return $this->getFollowedByOf($request, User::find($uid));
    }

    private function getFollowingOf(Request $request, $user_info) {
        if (is_null($user_info)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $page = is_null($request->page) ? UserFollow::PAGE : $request->page;
        $results_per_page = $request->results_per_page;
        $results_per_page = is_null($results_per_page) ? UserFollow::RESULTS_PER_PAGE : $results_per_page;
        $uid = $user_info->id;
        $res = UserFollow::selectFollowingOfUid($uid, $page, $results_per_page);
        $total = UserFollow::total(['uid' => $uid]);
        $total_page = $total / $results_per_page;
        return [
            'total' => $total,
            'page' => $page,
            'total_page' => $total_page < 1 ? 1 : $total_page,
            'data' => $res,
        ];
    }

    private function getFollowedByOf(Request $request, $user_info) {
        if (is_null($user_info)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $page = is_null($request->page) ? UserFollow::PAGE : $request->page;
        $results_per_page = $request->results_per_page;
        $results_per_page = is_null($results_per_page) ? UserFollow::RESULTS_PER_PAGE : $results_per_page;
        $uid = $user_info->id;
        $res = UserFollow::selectFollowedByOfUid($uid, $page, $results_per_page);
        $total = UserFollow::total(['follow_uid' => $uid]);
        $total_page = $total / $results_per_page;
        return [
            'total' => $total,
            'page' => $page,
            'total_page' => $total_page < 1 ? 1 : $total_page,
            'data' => $res,
        ];
    }

    public function postTag(Request $request) {
        $tag_id = $request->input('tag_id');
        $uid = \Globals::$user->id;
        if (is_null($uid)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        }
        $validate = Validator::make($request->all(), [
            'tag_id' => 'required|integer',
        ],[
            'tag_id.required' => 'USER_TAG_ID_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $followTags = \Globals::$user->tags . ',' . $tag_id;
        $res = User::updateTags($uid, $followTags);
        return response()->json(); 
    }

    public function deleteTag(Request $request) {
        $tag_id = $request->input('tag_id');
        $uid = \Globals::$user->id;
        if (is_null($uid)) {
            return response()->json(['error' => 'NOT_LOGGED'], 403);
        } 
        $validate = Validator::make($request->all(), [
            'tag_id' => 'required|integer',
        ],[
            'tag_id.required' => 'USER_TAG_ID_NEEDED',
        ]);
        if ($validate->fails()) {
            return response()->json(['error' => $validate->messages()->first()], 400);
        }
        $tags = explode(",", \Globals::$user->tags);
        $tag = explode(" ", $tag_id);
        $followTags = implode(",", array_diff($tags, $tag));
        $res = User::updateTags($uid, $followTags);
        return response()->json(); 
    }
}
