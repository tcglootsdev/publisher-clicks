<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
//use App\Models\User;
//use App\Validators\Model\User as UserValidator;
use App\Helpers\Utils;

class AuthController extends BaseController
{
    public function signin(Request $request)
    {
        // TODO: Set default messages of Validator
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6',
        ], [
            'username.required' => trans('models/user.empty_user_username'),
            'password.required' => trans('models/user.empty_user_password'),
            'password.min' => trans('models/user.invalid_user_password'),
        ]);
        if ($validator->fails()) {
            return Utils::responseJsonError($validator->errors()->first());
        }

//        $referer = $request->header('referer');
//        if (!$referer) {
//            return Utils::responseJsonError(trans('others.invalid_request'));
//        }
//        $referer = parse_url($referer);
//        $mapRoleToReferer = [
//            '/admin/auth/signin' => 'admin',
//        ];
//        if (!key_exists($referer['path'], $mapRoleToReferer)) {
//            return Utils::responseJsonError(trans('others.invalid_request'));
//        }

        $credentials = $request->only('username', 'password');
//        $credentials['role'] = $mapRoleToReferer[$referer['path']];
        $credentials['role'] = 'admin';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return Utils::responseJsonData();
        }

        return Utils::responseJsonError(trans('others.invalid_credential'));
    }

//    public function signup(Request $request)
//    {
//        $body = $request->all();
//
//        /**
//         * =================================
//         * 1. Get Available Fields
//         * =================================
//         */
//        $avFields = User::getAvailableFields('web', 'user', 'c');
//
//        /**
//         * =================================
//         * 2. Request Validation
//         * =================================
//         */
//        $validationError = UserValidator::validate($body, $avFields, 'c');
//        if (!empty($validationError)) {
//            return Utils::responseJsonError($validationError);
//        }
//
//        /**
//         * =================================
//         * 3. Create
//         * =================================
//         */
//        $user = Utils::onlyKeysInArray($body, $avFields);
//        $user['avatar'] = '/assets/web/global/images/svg/user.svg';
//        $user['role'] = 'user';
//        $user['password'] = Hash::make($user['password']);
//        $user['rewards'] = 0;
//        $user['is_blocked'] = '0';
//
//        $created = User::create($user);
//        if ($created) {
//            Auth::login($created);
//            return Utils::responseJsonData();
//        } else {
//            return Utils::responseJsonError(trans('internal_error'));
//        }
//    }

    public function signout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Utils::responseJsonData();
    }
}
