<?php

namespace Pratik\Firewall\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Pratik\Firewall\Services\Rules\CidrRule;

class CidrRuleTest extends TestCase
{
    public function test_cidr_rule_blocks_ip_in_range()
    {
        $rule = new CidrRule();

        $config = ['cidr' => ['192.168.0.0/24']];

        $this->assertTrue($rule->isBlocked('192.168.0.5', $config));
        $this->assertFalse($rule->isBlocked('10.0.0.1', $config));
    }
}
