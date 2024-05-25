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
            // Проверяем роли пользователя
            if (Auth::user()->hasAnyRole(['admin', 'quality-engineer'])) {
                // Если пользователь аутентифицирован и имеет одну из указанных ролей,
                // перенаправляем его на главную страницу или куда-то еще
                return redirect('/home');
            } else {
                // Если пользователь аутентифицирован, но не имеет соответствующих ролей,
                // перенаправляем его на страницу с сообщением об ошибке или куда-то еще
                abort(403, 'Unauthorized action.');
            }
        }

        // Если пользователь не аутентифицирован, он будет перенаправлен на страницу входа
        return $next($request);
    }
}
