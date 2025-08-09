<?php

namespace App\Http\Middleware;

use App\Models\Hotel;
use Closure;
use Illuminate\Http\Request;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        if (filter_var($host, FILTER_VALIDATE_IP) || $host === 'localhost') {
            return $next($request);
        }

        $subdomain = explode('.', $host)[0];
        $exemptSubdomains = config('tenant.exempt_subdomains', []);

        if (in_array($subdomain, $exemptSubdomains)) {
            return $next($request);
        }

        $hotel = Hotel::where('subdomain', $subdomain)->first();

        if (!$hotel) {
            abort(404, 'Hotel tenant not found.');
        }

        app()->instance('tenant', $hotel);

        return $next($request);
    }
}
