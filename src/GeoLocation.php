<?php

namespace Hoonam\Framework;

readonly class GeoLocation implements Equatable
{
    final static function zero(): GeoLocation { return new GeoLocation(0, 0); }

    public function __construct(public float $latitude, public float $longitude)
    {
    }

    public function toString(): string
    {
        return $this->latitude . ',' . $this->longitude;
    }

    public static function parse(string $value): GeoLocation
    {
        [$lat, $lng] = explode(',', $value, limit: 3);
        return new GeoLocation(toFloat(trim($lat)), toFloat(trim($lng)));
    }

    public function equals(mixed $other): bool
    {
        return $other instanceof GeoLocation &&
            $this->latitude === $other->latitude &&
            $this->longitude === $other->longitude;
    }
}
