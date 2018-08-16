<?php

namespace Spatie\DevelopmentDashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\DevelopmentDashboard\DevelopmentDashboard;

class Authorize
{
    public function handle(Request $request, Closure $next)
    {
        return DevelopmentDashboard::check($request) ? $next($request) : abort(403);
    }
}
