<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class set_lang
{
    public function handle(Request $request, Closure $next): Response
    {
        if(Session::has("locale")){
            app()->setlocale(Session::get("locale"));
        }
        return $next($request);
    }
}
