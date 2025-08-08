<?php

namespace App\Http\Middleware;

use App\Models\Hotel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();

        // Bypass tenant logic for IP addresses and localhost
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

        Config::set('database.connections.tenant.host', $hotel->db_host ?? '127.0.0.1');
        Config::set('database.connections.tenant.database', $hotel->db_database);
        Config::set('database.connections.tenant.username', $hotel->db_username);
        Config::set('database.connections.tenant.password', $hotel->db_password);

        DB::purge('tenant');

        return $next($request);
    }
}
