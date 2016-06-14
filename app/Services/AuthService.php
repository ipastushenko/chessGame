<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Bus;
use App\Jobs\EmailConfirmation;
use Carbon\Carbon;
use App\Services\Exceptions\ConfirmTokenIsNotFoundException;
use App\Services\Exceptions\ConfirmTokenExpiredException;
use DB;

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
        if (isset($confirmationInfo)) {
            $confirmationInfo = (array) $confirmationInfo;
            if (!self::isTokenExpired($confirmationInfo)) {
                DB::table('users')
                    ->where('email', '=', $confirmationInfo['email'])
                    ->update(['confirmed' => true]);
                $user = User::where('email', '=', $confirmationInfo['email'])
                    ->first();

                return $user;
            }

            $user = User::where('email', '=', $confirmationInfo['email'])
                ->first();
            self::sendConfirmationInfo($user);

            throw new ConfirmTokenExpiredException();
        }

        throw new ConfirmTokenIsNotFoundException();
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
        $info = DB::table('user_confirmation')
            ->where('email', '=', $user->email)
            ->first();

        if (isset($info)) {
            $info = (array) $info;
            if (!self::isTokenExpired($info)) {
                return $info;
            }

            $info['created_at'] = new Carbon();
            $info['token'] = uuid_create();
            DB::table('user_confirmation')
                ->where('email', '=', $info['email'])
                ->update($info);

            return $info;
        }

        $info = array(
            'token' => uuid_create(),
            'email' => $user->email,
            'created_at' => new Carbon()
        );
        DB::table('user_confirmation')->insert($info);

        return $info;
    }

    private static function isTokenExpired($info) {
        $expiredAt = Carbon::parse($info['created_at'])
            ->addSeconds(config('auth.confirmTokenExpired'));
        return $expiredAt->isPast();
    }
}
