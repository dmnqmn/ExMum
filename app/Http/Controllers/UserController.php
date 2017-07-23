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

use General;
use Config;
use Validator;

class UserController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function postRegister(Request $request)
  {
    if (empty($request->input('email'))) {
      return response()->json(['error' => 'REGISTER_EMAIL_NEEDED'], 400);
    }

    if (empty($request->input('password'))) {
      return response()->json(['error' => 'REGISTER_PASSWORD_NEEDED'], 400);
    }

    $email = trim($request->input('email'));
    $password = $request->input('password');

    if (User::emailExisted($email)) {
      return response()->json(['error' => 'REGISTER_EMAIL_EXISTED'], 403);
    }

    $user = User::create($email, $password);

    $token = AccessToken::create($user->id);

    $cookie = makeCookie('EXMUM_U', $token, General::ACCESS_TOKEN_MAX_AGE);
    return response()->json(null)->withCookie($cookie);
  }
}
