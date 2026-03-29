<?php

namespace Pratik\Firewall\Services\Rules;

use Illuminate\Support\Facades\RateLimiter;

class RateLimitRule implements RuleInterface
{
    public function isBlocked(string $ip, array $config): bool
    {
        $limit = $config['rate_limit']['max_attempts'] ?? 0;

        if ($limit <= 0) {
            return false;
        }

        $decay = $config['rate_limit']['decay_seconds'] ?? 60;
        $key = 'fw_rate_' . $ip;

        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return true;
        }

        RateLimiter::hit($key, $decay);

        return false;
    }

    public function reason(): string
    {
        return 'rate_limit';
    }
}
