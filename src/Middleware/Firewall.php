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
        $ip = $request->ip();

        if ($this->firewall->isBlocked($ip)) {
            return $this->blockedResponse($request, $ip);
        }

        return $next($request);
    }

    protected function blockedResponse(Request $request, string $ip)
    {
        $config = config('firewall.response', []);

        $status  = $config['status'] ?? 403;
        $message = $config['message'] ?? 'Access denied by firewall.';

        if ($request->expectsJson() || ($config['type'] ?? 'auto') === 'json') {
            return new JsonResponse([
                'message' => $message,
                'ip'      => $ip,
            ], $status);
        }

        abort($status, $message);
    }
}
