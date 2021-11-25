<?php

namespace AdminUI\AdminUIUserBase\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use AdminUI\AdminUI\Models\User;
use AdminUI\AdminUI\Helpers\UserHelper;
use Illuminate\Validation\Rules\Password;
use AdminUI\AdminUI\Models\Account;
use AdminUI\AdminUI\Models\Address;

class RegisterController extends Controller
{
    /**
     * Register the user in the system and create a account
     *
     * @param Request $request
     *
     * @return [type]
     */
    public function userRegister(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'string', Password::min(8)->uncompromised(), 'confirmed'],
            'address1'   => ['required', 'string', 'max:255'],
            'town'       => ['required', 'string', 'max:255'],
            'postcode'   => ['required', 'string', 'max:255'],
            //'phone'      => ['required', 'string', 'max:255'],
            //'county'     => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () {
            // Create the user
            $user             = new User();
            $user->email      = Request('email');
            $user->first_name = Request('first_name');
            $user->last_name  = Request('last_name');
            $user->password   = Hash::make(Request('password'));
            $user->is_active  = Request('is_active') ?? 1;
            $user->save();

            // Create the account for this new user
            $account             = new Account();
            $account->name       = Request('email');
            $account->vat_number = '';
            $account->save();

            // Create the account addres
            $address             = new Address();
            $address->company    = Request('company') ?? Request('email');
            $address->nickname   = Request('nickname') ?? 'Home';
            $address->country_id = Request('country_id') ?? 222;
            $address->phone      = Request('phone') ?? 0000000000;
            $address->postcode   = Request('postcode');
            $address->lookup     = Request('lookup');
            $address->address    = Request('address1');
            $address->address_2  = Request('address_2');
            $address->town       = Request('town') ?? 'undefine';
            $address->county     = Request('county');
            $address->state      = Request('state') ?? '';
            $address->save();

            // Sync the address to the account
            $account->address()->attach($address->id);

            // Send the user a email to verify the account
            event(new Registered($user));

            // This function will create the user roles and the account with the permissions
            UserHelper::userAccountCreate($user);
        });

        // Send the verification to the user
        //UserVerifyEvent::dispatch($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'Account created successfully, Please check you email for a verification link.',
        ]);
    }
}
