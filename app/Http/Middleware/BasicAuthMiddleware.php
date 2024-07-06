<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if Authorization header exists
        if (!$request->header('Authorization')) {
            return errorResponse(Response::HTTP_UNAUTHORIZED, "You are unauthorised to perform this operation.");
        }

        // Extract credentials from Authorization header
        $authorization = $request->header('Authorization');
        $credentials = base64_decode(substr($authorization, 6)); // Remove 'Basic ' prefix and decode

        // Split username and password
        list($username, $password) = explode(':', $credentials);

        // Validate credentials
        if ($username !== config('app.username') || $password !== config('app.password')) {
            return errorResponse(Response::HTTP_UNAUTHORIZED, "You are unauthorised to perform this operation.");
        }

        return $next($request);
    }
}
