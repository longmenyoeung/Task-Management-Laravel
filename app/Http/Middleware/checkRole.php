<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
        public function handle(Request $request, Closure $next, ...$roles): Response
        {
            // Check authentication
            if (!$request->user()) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }



            /*
                $request->user()->role : mean the role log-in user (admin, manager...)
                $roles The list of allowed roles you passed from your route definition ['admin', 'manager'].
                true : $request->user()->role and $roles are matching will return true if not false
            */

             // Check authorization
            if (!in_array($request->user()->role, $roles, true)) {
                return response()->json([
                    'message' => 'You do not have permission.',
                ], 403);
            }


            return $next($request);
            

        }
}
