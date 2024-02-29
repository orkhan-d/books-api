<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    use ResponseTrait;

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (User::query()->firstWhere('token', $token)->admin===1)
            return $next($request);
        return $this->error('Forbidden for you!', 403);
    }
}
