<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has("app.locale"))
        {
            $default=config("app.locale");
            Session::put("app.locale", $default);
            App::setLocale($default);
        }
        else
            App::setLocale (Session::get("app.locale"));
        return $next($request);
    }
}
