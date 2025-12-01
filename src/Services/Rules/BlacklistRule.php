<?php

namespace Pratik\Firewall\Services\Rules;

class BlacklistRule implements RuleInterface
{
    public function isBlocked(string $ip, array $config): bool
    {
        return in_array($ip, $config['blacklist'] ?? [], true);
    }

    public function reason(): string
    {
        return 'blacklist';
    }
}
