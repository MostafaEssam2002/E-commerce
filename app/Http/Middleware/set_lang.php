<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\session;
use Symfony\Component\HttpFoundation\Response;

class set_lang
{
    public function handle(Request $request, Closure $next): Response
    {
        if(session::has("locale")){
            app()->setlocale(session::get("locale"));
        }
        return $next($request);
    }
}
