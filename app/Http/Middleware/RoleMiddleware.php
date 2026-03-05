<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      if (!$request->user()) {
        return redirect('/login');
      }

      elseif ($request->user()->role !== $role) {
          abort(403, 'No tienes permiso para acceder.');
      } else {
        return $next($request);
      }
    }
}
