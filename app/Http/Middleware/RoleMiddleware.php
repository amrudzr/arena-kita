<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    use ApiResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::guard('api_user')->user();

        if (! $user || ! in_array($user->role, $roles)) {
            return $this->sendError(
                'Anda tidak memiliki izin untuk mengakses ini',
                [],
                Response::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }
}
