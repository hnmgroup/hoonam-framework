<?php

namespace Hoonam\Framework;

use Illuminate\Support\Carbon;

readonly class TimeInterval implements Equatable
{
    public function __construct(public Carbon $start, public Carbon $end)
    {
    }

    public function equals(mixed $other): bool
    {
        return $other instanceof TimeInterval &&
            $this->start->equalTo($other->start) &&
            $this->end->equalTo($other->end);
    }
}
