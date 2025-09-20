<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SomeTest extends TestCase
{
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_example2(): void
    {
        $this->assertEquals(2, -1+3);
    }
}
