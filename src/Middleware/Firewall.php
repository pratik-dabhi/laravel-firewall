<?php

namespace Pratik\Firewall\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Pratik\Firewall\Services\FirewallManager;

class Firewall
{
    public function __construct(protected FirewallManager $firewall) {}

    public function handle(Request $request, Closure $next)
    {
        $decision = $this->firewall->evaluate($request);
        $ip       = $request->ip();

        if ($decision['allowed']) {
            return $next($request);
        }

        $config  = config('firewall.response', []);
        $status  = $config['status'] ?? 403;
        $message = $config['message'] ?? 'Access denied by firewall.';

        if ($request->expectsJson() || ($config['type'] ?? 'auto') === 'json') {
            return new JsonResponse([
                'message' => $message,
                'ip'      => $ip,
                'reason'  => $decision['reason'] ?? null,
                'rule'    => $decision['rule'] ?? null,
            ], $status);
        }

        abort($status, $message);
    }
}
