<?php

namespace Tests\Unit;

use Hoonam\Framework\Utilities\Math;
use PHPUnit\Framework\TestCase;

class MathTests extends TestCase
{
    public function test_truncate_works_properly(): void
    {
        $this->assertEquals(10, Math::truncate(10));
        $this->assertEquals(10, Math::truncate(10.1));
        $this->assertEquals(10, Math::truncate(10.9));
    }

    public function test_compute_percent_works_properly(): void
    {
        $this->assertEquals(40, Math::percent(200, 20));
        $this->assertEquals(0.1, Math::percent(10, 1));
    }
}
