<?php

namespace App\Services;

use App\User;
use DB;
use Log;

class AuthService {
    public function __construct() {
    }

    /**
     * Confirm user by token
     *
     * @return user if user has been confirmed;
     */
    public static function confirmUser ($token) {
        $confirmationInfo = DB::table('user_confirmation')
            ->where('token', '=', $token)
            ->first();
        if ($confirmationInfo) {
            $data = DB::table('users')
                ->where('email', '=', $confirmationInfo->email)
                ->update(['confirmed' => true]);
            $user = User::where('email', '=', $confirmationInfo->email)
                ->first();

            return $user;
        }

        return null;
    }
}
