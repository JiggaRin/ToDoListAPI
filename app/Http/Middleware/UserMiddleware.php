<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, $modelClass)
    {
        if ($request->route('task') instanceof Task) {
            $resourceId = $request->route('task')->id;
        } else {
            $resourceId = $request->route('task');
        }

        try {
            $resource = $modelClass::findOrFail($resourceId);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        if (Auth::check() && $resource->user_id == Auth::id()) {
            return $next($request);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
