<?php

namespace Tests\Unit;

use Hoonam\Framework\Application\Validation\FieldFormatException;
use Hoonam\Framework\Application\Validation\FieldRangeException;
use Hoonam\Framework\Application\Validation\FieldRequiredException;
use Hoonam\Framework\Rules;
use PHPUnit\Framework\TestCase;

class ValidationTests extends TestCase
{
    // public function test_fromDays_works_properly(): void
    // {
    //     $errors = Validator::make($data, self::$rules, self::messages)->errors()->messages();
    //     return new CustomerRegister;
    //
    //     $this->assertEquals(24, TimeSpan::fromDays(1)->hours());
    //     $this->assertEquals(80_640, TimeSpan::fromWeeks(8)->minutes());
    //     $this->assertEquals(31_536_000_000, TimeSpan::fromYears(1)->milliseconds());
    //     $this->assertEquals(31_622_400_000, TimeSpan::fromYears(1, leap: true)->milliseconds());
    //     $this->assertEquals(0.001, TimeSpan::fromMilliseconds(1)->seconds());
    // }
}

class Book
{
    #[Rules('gt:0')]
    public int $id;

    #[Rules('required')]
    public string $fullName;

    public ?int $age;

    public ?Address $address;

    private $rules = [
        'id'       => ['bail', 'required', 'integer', 'gt:0'],
        'fullName' => ['bail', 'required'],
        'age'      => ['bail', 'nullable', 'integer', 'between:1,200'],
    ];

    const messages = [
        'required' => FieldRequiredException::class,
        'integer'  => FieldFormatException::class,
        'between'  => FieldRangeException::class,
        'gt'       => FieldRangeException::class,
    ];
}

class Address
{
    #[Rules('nullable')]
    public ?string $province;
    public ?string $city;
    public ?string $street;
}
