<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(RegisterRequest $request)
    {
        try {
            $input = $request->all();
            if (Session::get('OTPVerified')) {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'status' => 1,
                    'password' => Hash::make($request->password),
                ]);
                Session::forget('session_otp');
                Session::forget('mobile_number');
                Session::forget('OTPVerified');
                Auth::login($user);
                event(new Registered($user));
                return redirect(RouteServiceProvider::HOME);
            } else {
                return back()->withError('Mobile number not verified.')->withInput();
            }
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
}
