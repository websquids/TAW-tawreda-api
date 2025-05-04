<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissions {
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next, ...$permissions): Response {
    $roles = $request->user()->roles;
    foreach ($roles as $role) {
      foreach ($permissions as $permission) {
        if ($role->hasPermissionTo($permission)) {
          return $next($request);
        }
      }
    }
    return response()->json(['error' => 'Forbidden'], 403);
  }
}
