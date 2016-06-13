<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Bus;
use App\Jobs\EmailConfirmation;
use Carbon\Carbon;
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

    /**
     * Send confirmation info for user
     *
     * @return void
     */
    public static function sendConfirmationInfo($user) {
        $job = (new EmailConfirmation($user))
            ->onQueue(config('app.emailQueue'));
        Bus::dispatch($job);
    }

    /**
     * Create confirmation info
     *
     * @return info
     */
    public static function createConfirmationInfo($user) {
        $info = array(
            'token' => uuid_create(),
            'email' => $user->email,
            'created_at' => new Carbon()
        );
        DB::table('user_confirmation')->insert($info);

        return $info;
    }
}
