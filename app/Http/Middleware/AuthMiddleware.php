<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $user = User::query()->firstWhere('token', $token);
        if ($token!==null && $user) {
            Auth::login($user);
            return $next($request);
        }
        return $this->error('Login failed!', 403);
    }
}
