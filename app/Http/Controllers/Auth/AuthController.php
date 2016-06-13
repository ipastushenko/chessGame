<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $authService;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->authService = $authService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function register(Request $request) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $user = $this->create($request->all());
        $this->authService->sendConfirmationInfo($user);

        Flash::success(trans('auth.successRegistration'));
        return redirect($this->redirectPath());
    }

    public function authenticated($request, $user) {
        if (!$user->confirmed) {
            Auth::logout();

            Flash::error(trans('auth.emailNotConfirmed'));
            return back();
        }

        return redirect()->intended($this->redirectPath());
    }

    public function confirmation($token) {
        $user = $this->authService->confirmUser($token);
        if ($user) {
            Auth::guard($this->getGuard())->login($user);
            Flash::success(trans('auth.successConfirmation'));
        } else {
            Flash::error(trans('auth.errorConfirmation'));
        }

        return redirect($this->redirectPath());
    }
}
