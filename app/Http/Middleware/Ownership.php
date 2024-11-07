<?php

namespace App\Http\Middleware;

use App\Exceptions\AccessDeniedException;
use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Ownership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = Auth::user();

        if ($auth instanceof Student) {

            $student = $request->route('student');

            if ($student->id !== $auth->id) {
                throw new AccessDeniedException;
            }

        } else {

            $user = $request->route('user');

            if ($user->id !== $auth->id) {
                throw new AccessDeniedException;
            }
        }

        return $next($request);
    }
}
