<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $role = $user->role;

        if (!$role || !is_array($role->permissions) || !in_array($permission, $role->permissions)) {
            abort(403, "Unauthorized. Missing permission: {$permission}");
        }

        return $next($request);
    }
}
