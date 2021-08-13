<?php

namespace AdminUI\AdminUIUserBase\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPassword extends Controller
{
    /**
     * Handle the request to reset user password
     * @param Request $request
     *
     * @return [type]
     */
    public function reset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Password::sendResetLink(
            $request->only('email')
        );

        return json_encode([
            'status'  => true,
            'message' => 'Password link sent with success.',
        ]);
    }

    public function passwordReset(Request $request, $token)
    {
        $email = $request->input('email');
        return redirect('/user/account/reset-password/' . $token . '?email=' . $email);
    }

    /**
     * Change the user password
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function passwordChange(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                $user->setRememberToken(Str::random(60));

                event(new PasswordReset($user));
            }
        );

        return json_encode([
            'status'  => "success",
            'message' => 'Password has been changed.',
        ]);
    }
}
