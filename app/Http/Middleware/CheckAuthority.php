<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckAuthority
{

    public function handle(Request $request, Closure $next, string $authorityName): Response
    {
        $user = $request->user()?->loadMissing('role.authorities');

        // Check if user exists and has the required authority
        if (!$user || !$user->hasAuthority($authorityName)) {
            abort(403, 'You do not have the required authority: ' . $authorityName);
        }

        return $next($request);
    }
}
