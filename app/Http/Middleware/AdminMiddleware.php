<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Проверяем, что пользователь админ (по email или роли)
        if (auth()->user()->email !== 'admin@example.com') {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }

        return $next($request);
    }
}
