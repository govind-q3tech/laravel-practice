<?php

namespace App\Http\Middleware;
use App\Models\RedirectUrl;
use Illuminate\Http\RedirectResponse;
use Closure;


class RedirectUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {    
        $currentUrl = url()->current();
        
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
       // header("Content-Type: application/xml; charset=utf-8");
        if(isset($currentUrl) && $currentUrl !=''){ 
            $getPage = RedirectUrl::where('old_url', $currentUrl)->first();   
            
            if(!empty($getPage->new_url)){

                return redirect()->to($getPage->new_url, 301); 
            }
        }
        return $next($request); 
    }
}
