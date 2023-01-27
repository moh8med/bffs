<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GeoIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $config = config('bff.geo');

        if (! $config['enabled']) {
            return $next($request);
        }

        $visitor = geoip($request->ip());

        // countries

        $only = $config['countries']['only'];
        if ($only && ! in_array(strtoupper($visitor->iso_code), $only)) {
            return abort(403, 'Access Denied');
        }

        $except = $config['countries']['except'];
        if ($except && in_array(strtoupper($visitor->iso_code), $except)) {
            return abort(403, 'Access Denied');
        }

        // continents

        $only = $config['continents']['only'];
        if ($only && ! in_array(strtoupper($visitor->continent), $only)) {
            return abort(403, 'Access Denied');
        }

        $except = $config['continents']['except'];
        if ($except && in_array(strtoupper($visitor->continent), $except)) {
            return abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
