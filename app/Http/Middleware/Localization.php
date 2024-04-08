<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('provider*')) {
            if (session()->has('provider_local')) {
                App::setLocale(session()->get('provider_local'));
            }
        }elseif($request->is('admin*')){
            if (session()->has('local')) {
                App::setLocale(session()->get('local'));
            }
        }else{
            if (session()->has('landing_local')) {
                App::setLocale(session()->get('landing_local'));
            }else{
                session()->put('landing_local', 'en');
                App::setLocale('en');
            }
        }
        return $next($request);
    }
}
