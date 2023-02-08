<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Cookie, Session;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        Session::forget('session_otp');
        Session::forget('mobile_number');
        Session::forget('OTPVerified');
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        if (User::where(['email' => $request->email, 'status' => 0])->exists()) {
            return redirect()->back()->with('error', 'Your account has been deactivated. Please contact your site administrator.');
        }
        $request->authenticate();

        $request->session()->regenerate();
        if (!empty($request->remember)) {
            setcookie("email", $request->email, strtotime("+1 year"));
            setcookie("password", $request->password, strtotime("+1 year"));
        } else {
            setcookie("username", "");
            setcookie("password", "");
        }
        return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'User logged in successfully.');
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('frontend.login')->with('success', 'User logout successfully.');
    }
}
