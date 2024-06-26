<?php

namespace Modules\ProviderManagement\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;

class ProviderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && in_array(auth()->user()->user_type, PROVIDER_USER_TYPES)) {
            return $next($request);
        }
        Toastr::info(translate(ACCESS_DENIED['message']));
        return redirect('provider/auth/login');
    }
}
