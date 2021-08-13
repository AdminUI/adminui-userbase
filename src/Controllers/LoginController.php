<?php

namespace AdminUI\AdminUIUserBase\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use AdminUI\AdminUIAccounts\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function index()
    {
        return view('content.ecom.auth.login');
    }

    public function register()
    {
        return view('content.ecom.auth.register');
    }

    public function userRegister(Request $request)
    {
        // Validate the user
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user           = new User();
        $user->name     = Request('name');
        $user->email    = Request('email');
        $user->password = Hash::make(Request('password'));
        $user->save();

        // Send the verification to the user
        event(new Registered($user));

        return json_encode([
            'status'  => true,
            'message' => 'success, please verify email',
        ]);
    }

    public function login(Request $request)
    {
        // Validate the user
        $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return json_encode([
                'status'    => "success",
                'user'      => Auth::user(),
                'user_cart' => Auth::user()->cart(),
                'message'   => 'Welcome :)',
            ]);
        } else {
            return json_encode([
                'status'  => "error",
                'message' => 'Credentials do not match',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return json_encode([
            'status'  => true,
            'message' => 'by',
        ]);
    }

    public function verify(Request $request, $userId, $expiration)
    {
        $userId     = decrypt($userId);
        $expiration = decrypt($expiration);
        $nowDate    = Carbon::now();
        $user       = User::findOrFail($userId);

        // Check if is expired
        if ($nowDate > $expiration) {
            return redirect('/user/account/login')->with('error', 'Link Expired!');
        }

        // Check if the user has been verify
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // Return to the the login page as success
        return redirect('/user/account/email-verified')->with('success', 'User verify with success!');
    }

    public function needVerify()
    {
        // Resend the email case the user lost the email
        event(new Registered(Auth::user()));

        // Logout the user and redirect him to the home page
        Auth::logout();

        // Return to the the login page as success
        return Redirect::route('login')->with('error', 'User need to be verify!');
    }
}
