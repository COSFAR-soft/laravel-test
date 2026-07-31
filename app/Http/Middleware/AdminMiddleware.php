<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AdminMiddleware
 *
 * Проверяет, является ли пользователь администратором.
 * Если нет — возвращает 403.
 *
 * @package App\Http\Middleware
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        //TODO - метка в БД
        if (Auth::user()->email !== 'admin@example.com') {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }

        return $next($request);
    }
}
