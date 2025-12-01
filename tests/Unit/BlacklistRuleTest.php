<?php

namespace Pratik\Firewall\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Pratik\Firewall\Services\Rules\BlacklistRule;

class BlacklistRuleTest extends TestCase
{
    public function test_blacklist_blocks_listed_ip()
    {
        $rule = new BlacklistRule();

        $config = ['blacklist' => ['1.2.3.4']];

        $this->assertTrue($rule->isBlocked('1.2.3.4', $config));
        $this->assertFalse($rule->isBlocked('2.3.4.5', $config));
    }
}
