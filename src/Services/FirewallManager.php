<?php

namespace Pratik\Firewall\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pratik\Firewall\Services\Rules\RuleInterface;

class FirewallManager
{
    protected array $config;
    /** @var RuleInterface[] */
    protected array $rules = [];

    public function __construct(array $config, array $rules = [])
    {
        $this->config = $config;
        $this->rules  = $rules;
    }

    /**
     * Add a rule at runtime.
     */
    public function addRule(RuleInterface $rule): void
    {
        $this->rules[] = $rule;
    }

    /**
     * Evaluate the current request against firewall rules.
     *
     * @return array{
     *     allowed: bool,
     *     action: string,
     *     reason: string|null,
     *     rule: string|null
     * }
     */
    public function evaluate(Request $request): array
    {
        if (!($this->config['enabled'] ?? true)) {
            return [
                'allowed' => true,
                'action'  => 'allow',
                'reason'  => null,
                'rule'    => null,
            ];
        }

        $ip = $request->ip() ?? 'unknown';
        $whitelist = $this->config['whitelist'] ?? [];

        if (in_array($ip, $whitelist, true)) {
            return [
                'allowed' => true,
                'action'  => 'allow',
                'reason'  => null,
                'rule'    => null,
            ];
        }
        
        foreach ($this->rules as $rule) {
            if ($rule->isBlocked($ip, $this->config)) {
                $reason = $rule->reason();
                $action = $this->mapReasonToAction($reason);

                $result = [
                    'allowed' => false,
                    'action'  => $action,
                    'reason'  => $reason,
                    'rule'    => (new \ReflectionClass($rule))->getShortName(),
                ];

                $this->logIfNeeded($request, $result);

                return $result;
            }
        }
        
        return [
            'allowed' => true,
            'action'  => 'allow',
            'reason'  => null,
            'rule'    => null,
        ];
    }

    /**
     * Map a rule "reason" to a firewall action column value.
     */
    protected function mapReasonToAction(string $reason): string
    {
        if ($reason === 'rate_limit') {
            return 'ratelimit';
        }
        
        return 'block';
    }

    /**
     * Log a blocked or rate-limited request to the database.
     */
    protected function logIfNeeded(Request $request, array $result): void
    {
        if (!($this->config['logging']['enabled'] ?? false)) {
            return;
        }

        if ($result['allowed']) {
            return;
        }

        $table = $this->config['logging']['table'] ?? 'firewall_logs';

        $path = $request->path();
        if (strlen($path) > 2048) {
            $path = substr($path, 0, 2048);
        }

        DB::table($table)->insert([
            'ip_address'      => $request->ip(),
            'user_agent'      => (string) ($request->userAgent() ?? ''),
            'method'          => $request->getMethod(),
            'path'            => $path,
            'action'          => $result['action'],
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}
