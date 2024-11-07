<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessDeniedException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BelongsToMosque
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = Auth::user();

        $mosque = $request->route('mosque');

        if ($auth instanceof User) {
            $user = $auth;

            if ($user->mosque->id !== $mosque->id) {
                throw new AccessDeniedException;
            }

        } else {
            $student = $auth;

            if (!$student->mosques()->where('mosque_id', $mosque->id)->exists()) {
                throw new AccessDeniedException;
            }
        }

        return $next($request);
    }
}
