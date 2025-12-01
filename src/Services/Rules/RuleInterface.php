<?php

namespace Pratik\Firewall\Services\Rules;

interface RuleInterface
{
    public function isBlocked(string $ip, array $config): bool;
    public function reason(): string;
}
