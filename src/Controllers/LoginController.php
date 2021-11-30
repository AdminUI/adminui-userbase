<?php

namespace AdminUI\AdminUIUserBase\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use AdminUI\AdminUI\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /**
     * Try login the user
     *
     * @param Request $request
     *
     * @return [type]
     */
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

    /**
     * Log out the user
     *
     * @param Request $request
     *
     * @return [type]
     */
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
        return Redirect::route('login')->with('success', 'User verify with success!');
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
