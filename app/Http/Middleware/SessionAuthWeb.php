<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

use App\Models\LoginAttempt;
use App\Models\User;
use Closure;
use Session;

class SessionAuthWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $sessionNotRequired = [
            "LoginPage",
            "LoginUser",
            'UserRegister',
            'Register',
            "password.forget",
            "ResetPasswordRequest",
            "resetPasswordOtp",
            "GetTokenPage",
            "verifyToken"
        ];

        if ($this->is_valid_token($request)) {
            $user = User::where('id', $request->login_attempt->user_id)->first();
			if ($user) {
                $request->user = $user;
                return $next($request);
            }

        } else if (in_array($request->route()->getName(), $sessionNotRequired)) {
            return $next($request);
        }
        return redirect(route('LoginPage'))->with(['req_error' => 'Invalid Credentials']);
    }

    public function is_valid_token(&$request) {
        $token = getTokenWeb();
        if (!$token) {
            return false;
        }

        $request->login_attempt = LoginAttempt::where("access_token", $token)->get()->first();
        $is_expired = "is_access_expired";

        return $request->login_attempt && !($request->login_attempt->toArray())[$is_expired];
    }
}
