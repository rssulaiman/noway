<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Etudiant
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
        if (!$request->user()) {
            return redirect(url('login'));
        }

        if ($request->user() && !$request->user()->is_etudiant) {
            return redirect(url('not_authorize'));
        }

        return $next($request);
    }
}
