<?php

namespace Tests\Unit;

use Hoonam\Framework\TimeSpan;
use PHPUnit\Framework\TestCase;

class TimeSpanTests extends TestCase
{
    public function test_fromDays_works_properly(): void
    {
        $this->assertEquals(24, TimeSpan::fromDays(1)->hours());
        $this->assertEquals(80_640, TimeSpan::fromWeeks(8)->minutes());
        $this->assertEquals(31_536_000_000, TimeSpan::fromYears(1)->milliseconds());
        $this->assertEquals(31_622_400_000, TimeSpan::fromYears(1, leap: true)->milliseconds());
        $this->assertEquals(0.001, TimeSpan::fromMilliseconds(1)->seconds());
    }
}
