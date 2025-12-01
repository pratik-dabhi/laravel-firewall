<?php

namespace Pratik\Firewall\Services\Rules;

class CidrRule implements RuleInterface
{
    public function isBlocked(string $ip, array $config): bool
    {
        $cidrList = $config['cidr'] ?? [];

        foreach ($cidrList as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }

        return false;
    }

    public function reason(): string
    {
        return 'cidr_block';
    }

    private function ipInRange(string $ip, string $cidr): bool
    {
        if (!str_contains($cidr, '/')) {
            return $ip === $cidr;
        }

        [$subnet, $mask] = explode('/', $cidr);
        $ipLong     = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong   = ~((1 << (32 - (int) $mask)) - 1);

        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }
}
