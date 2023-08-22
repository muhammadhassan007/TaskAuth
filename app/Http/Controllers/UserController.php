<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUser;
use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class UserController extends Controller
{

    public function index()
    {
        $users = User::select('name', 'email')->get();
        return response()->json($users);
    }

    public function get()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $user               = new User;
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->addFlag(User::FLAG_ACTIVE);

        if ($user->save()) {
            request()->user                 = $user;
            $login_attempt                  = new LoginAttempt;
            $login_attempt->user_id         = $user->id;
            $login_attempt->access_token    = generate_token($user);
            $login_attempt->access_expiry   = date("Y-m-d H:i:s", strtotime("1 year"));
            if (!$login_attempt->save()) {
                return api_error('There is some error');
            }
            $user = User::where('id', $user->id)->first();
            $data = array(
                'message'           => 'Login Successfully',
                'detail'            => $user,
                'token_detail'      => array(
                    'access_token'  => $login_attempt->access_token,
                    'token_type'    => 'Bearer',
                ),
            );
            return view('dashboard')->with($data);
        }
        return api_error(['There is some error!', 'form' => 'registration']);
    }
}
