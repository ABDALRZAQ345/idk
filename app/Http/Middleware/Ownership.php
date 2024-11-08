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

            $studentId = $request->route()->originalParameter('student');

            if ($studentId != $auth->id) {
                throw new AccessDeniedException;
            }

        } else {

            $userId = $request->route()->originalParameter('user');

            if ($userId != $auth->id) {
                throw new AccessDeniedException;
            }
        }

        return $next($request);
    }
}
