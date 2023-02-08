<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $user = User::where('id', $request->route('id'))->first();
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }
        if ($user->email_change) {
            if ($user->markEmailAsVerified()) {
                DB::table('users')->where('id', $user->id)->update(['email_change' => 0]);
            }
        } else {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                $user->sendWelcomeMail();
            }
        }
        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }
}
