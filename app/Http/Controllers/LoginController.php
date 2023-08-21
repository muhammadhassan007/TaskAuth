<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\LoginAttempt;
use App\Mail\ResetPasswordLinkMail;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendMailRequest;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public $login_attempt = null;

    function login(LoginRequest $request)
    {
        if (\Request::route()->getName() == "Login") {
            $email = $request->input('type', false);
            $user = User::where("email", $email)->first();

            if ($user && (HASH::check($request->password, $user->password))) {
                if (!$user->active)
                    return  response('Your account is inactive', 400);

                if (!$this->saveLoginAttempt($user))
                    return  response('Something went wrong', 500);

                $data = array(
                    'message' => 'Login Successfully',
                    'detail' => $user,
                    'token_detail' => array(
                        'access_token' => $this->login_attempt->access_token,
                        'token_type' => 'Bearer'
                    )
                );
                return  response($data);
            }
            return response('Incorrect email/password');
        }

        $user = User::where("email", $request->email)->first();
        if ($user && HASH::check($request->password, $user->password)) {
            if (!$this->saveLoginAttempt($user))
                return redirect(route('LoginPage'))->with(['req_error' => 'Email/Password not match']);
            session(['usertoken' => $this->login_attempt->access_token]);
            return view('dashboard')->with(['req_success' => 'User Login Successfully']);
        }
        return redirect(route('LoginPage'))->with(['req_error' => 'Email/Password does not match']);
    }

    public function saveLoginAttempt($user)
    {
        request()->user = $user;

        $this->login_attempt                = new LoginAttempt();
        $this->login_attempt->user_id       = $user->id;
        $this->login_attempt->access_token  = generate_token($user);
        $this->login_attempt->access_expiry = date("Y-m-d H:i:s", strtotime("1 year"));
        return $this->login_attempt->save();
    }

    public function forgetPasswordPage()
    {
        return view('users.forget-page');
    }

    public function forget_password_email_verification(SendMailRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password_verify_token = rand(99999, 999999);
            if ($user->save()) {
                $mailSent =  Mail::to($user->email)->send(new ResetPasswordLinkMail($user));
                if ($mailSent === false) {
                    return response()->json(['error' => 'Email couldn\'t be sent!']);
                }

                return redirect()->route('resetPasswordOtp')
                    ->with('message', 'OTP sent to your email');
            }
        }
        return api_error('Invalid email!');
    }

    public function reset_password(ResetPasswordRequest $request)
    {
        $user = User::where('password_verify_token', $request->password_verify_token)->first();
        if ($user) {
            $user->password_verify_token    = NULL;
            $user->password                 = Hash::make($request->password);
            if ($user->save()) return api_success1('Your password has been updated successfully! You can now log into your profile again!');
        }
        return api_error('Invalid Token');
    }

    public function logout(Request $request)
    {
        if ($request->login_attempt) {
            $request->login_attempt->access_expiry = date("Y-m-d H:i:s");
            $request->login_attempt->save();
        }
        if (prefix())
            return api_success1("Logout Successfully!");
        return redirect(route('LoginPage'))->with(['req_success' => "Logout Successfully"]);
    }

    function ConfirmToeknPage(Request $request)
    {
        return View("users.forget-page");
    }

    public function resetpasswordotp()
    {
        return view('users.otp-page');
    }

    public function login_page()
    {
        if (!isset(request()->user))
            return view('users.login');
    }
}
