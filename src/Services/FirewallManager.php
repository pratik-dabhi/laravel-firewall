<?php

namespace Pratik\Firewall\Services;

use Illuminate\Support\Facades\DB;
use Pratik\Firewall\Services\Rules\RuleInterface;

class FirewallManager
{
    protected array $config;
    protected array $rules = [];

    public function __construct(array $config, array $rules = [])
    {
        $this->config = $config;

        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    public function addRule(RuleInterface $rule): void
    {
        $this->rules[] = $rule;
    }

    public function isBlocked(string $ip): bool
    {
        if ($this->inWhitelist($ip)) {
            return false;
        }

        foreach ($this->rules as $rule) {
            if ($rule->isBlocked($ip, $this->config)) {
                $this->logBlock($ip, $rule->reason());
                return true;
            }
        }

        return false;
    }

    protected function inWhitelist(string $ip): bool
    {
        return in_array($ip, $this->config['whitelist'] ?? [], true);
    }

    protected function logBlock(string $ip, string $reason): void
    {
        if (!($this->config['logging']['enabled'] ?? false)) {
            return;
        }

        DB::table($this->config['logging']['table'] ?? 'firewall_logs')->insert([
            'ip'         => $ip,
            'reason'     => $reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
