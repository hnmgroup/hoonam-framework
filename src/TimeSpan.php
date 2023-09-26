<?php

namespace Hoonam\Framework;

readonly final class TimeSpan implements Equatable
{
    private const MS_PER_SECOND = 1000;
    private const MS_PER_MINUTE = 60 * self::MS_PER_SECOND;
    private const MS_PER_HOUR = 60 * self::MS_PER_MINUTE;
    private const MS_PER_DAY = 24 * self::MS_PER_HOUR;

    private function __construct(private int $_millisecond)
    {
    }

    public static function fromYears(int $years, bool $leap = false): TimeSpan
    {
        return self::fromDays($leap ? $years * 366 : $years * 365);
    }

    public static function fromWeeks(int $weeks): TimeSpan
    {
        return self::fromDays($weeks * 7);
    }

    public static function fromDays(int $days): TimeSpan
    {
        return new TimeSpan($days * self::MS_PER_DAY);
    }

    public static function fromMilliseconds(int $milliseconds): TimeSpan
    {
        return new TimeSpan($milliseconds);
    }

    public static function fromHours(int $hours): TimeSpan
    {
        return self::fromMilliseconds($hours * self::MS_PER_HOUR);
    }

    public static function fromMinutes(int $minutes): TimeSpan
    {
        return self::fromMilliseconds($minutes * self::MS_PER_MINUTE);
    }

    public static function fromSeconds(int $seconds): TimeSpan
    {
        return self::fromMilliseconds($seconds * self::MS_PER_SECOND);
    }

    public function add(TimeSpan $time): TimeSpan
    {
        return new TimeSpan($this->_millisecond + $time->_millisecond);
    }

    public function subtract(TimeSpan $time): TimeSpan
    {
        return new TimeSpan($this->_millisecond - $time->_millisecond);
    }

    public function weeks(): int|float
    {
        return $this->_millisecond / (self::MS_PER_DAY * 7);
    }

    public function days(): int|float
    {
        return $this->_millisecond / self::MS_PER_DAY;
    }

    public function hours(): int|float
    {
        return $this->_millisecond / self::MS_PER_HOUR;
    }

    public function minutes(): int|float
    {
        return $this->_millisecond / self::MS_PER_MINUTE;
    }

    public function seconds(): int|float
    {
        return $this->_millisecond / self::MS_PER_SECOND;
    }

    public function milliseconds(): int
    {
        return $this->_millisecond;
    }

    public function equals(mixed $other): bool
    {
        return $other instanceof TimeSpan && $this->_millisecond === $other->_millisecond;
    }
}
