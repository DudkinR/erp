<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Если пользователь аутентифицирован, перенаправляем его на главную страницу или куда-то еще
            return redirect('/home');
        }

        // Если пользователь не аутентифицирован, он будет перенаправлен на страницу входа
        return $next($request);
    }
}
