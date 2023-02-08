<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\Membership\Subscription;

class PlanChecker
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
        $active_sub = Subscription::where(['user_id' => Auth::user()->id, 'status' => 1])
                    ->whereDate('start_date', '<=', date("Y-m-d"))
                    ->whereDate('end_date', '>=', date("Y-m-d"))
                    // ->with('plans.plan_features')
                    ->exists();
                    // dd($active_sub);
        if($active_sub)
        {
            return $next($request);
        }
        return redirect()->route('frontend.subscription.plan')->with('error', 'Please purchase atleast one plan');
    }
}
