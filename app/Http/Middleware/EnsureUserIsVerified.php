<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (
            !$request->user() ||
            ($request->user() && !$request->user()->isValid())
        ) {
            return $request->expectsJson()
                ? abort(403, 'Your account is not verified.')
                : Redirect::guest(url('/'));
            //                    : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        }

        return $next($request);
    }
}
