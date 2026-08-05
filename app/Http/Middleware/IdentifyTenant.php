<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Merchant;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request by resolving Merchant Tenant from Host Header.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $merchant = null;

        // 1. Check for custom domain CNAME mapping
        $merchant = Merchant::where('custom_domain', $host)->first();

        // 2. If not custom domain, check for subdomain pattern (e.g. store.mybiz.dhaivam.in)
        if (!$merchant) {
            $baseDomain = config('app.base_domain', 'mybiz.dhaivam.in');
            if (str_contains($host, '.' . $baseDomain)) {
                $subdomain = explode('.' . $baseDomain, $host)[0];
                $merchant = Merchant::where('subdomain', $subdomain)->first();
            }
        }

        // 3. Store isolation check
        if ($merchant) {
            if ($merchant->store_status === 'disabled' || $merchant->health_tier === 'suspended') {
                return response()->view('errors.store_disabled', ['merchant' => $merchant], 503);
            }
            
            // Attach resolved merchant tenant to request container
            $request->attributes->set('tenant', $merchant);
            app()->instance('tenant', $merchant);
        }

        return $next($request);
    }
}
