<?php

namespace Tests\Unit;

use Hoonam\Framework\Utilities\Convert;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ConvertTests extends TestCase
{
    public function test_a_value_converts_to_integer_properly(): void
    {
        $this->assertEquals(25, Convert::toInteger(25));

        $this->assertEquals(25, Convert::toInteger(25.9));

        $this->assertEquals(1, Convert::toInteger(true));
        $this->assertEquals(0, Convert::toInteger(false));

        $this->assertEquals(25, Convert::toInteger('25'));
        $this->assertEquals(25, Convert::toInteger(' 25'));
        $this->assertEquals(25, Convert::toInteger('25 '));

        $this->assertEquals(null, Convert::toInteger('', throwOnBlank: false));
        $this->assertEquals(null, Convert::toInteger(' ', throwOnBlank: false));
        $this->assertEquals(null, Convert::toInteger("\t", throwOnBlank: false));

        $this->assertEquals(null, Convert::toInteger(null, throwOnBlank: false));
    }

    public function test_converts_to_integer_failed_with_invalid_argument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Convert::toInteger([]);

        $this->expectException(InvalidArgumentException::class);
        Convert::toInteger(new stdClass);

        $this->expectException(InvalidArgumentException::class);
        Convert::toInteger('2 5');

        $this->expectException(InvalidArgumentException::class);
        Convert::toInteger('2.5');

        $this->expectException(InvalidArgumentException::class);
        Convert::toInteger('a');

        $this->expectException(InvalidArgumentException::class);
        Convert::toInteger('25a');
    }

    public function test_a_value_converts_to_float_properly(): void
    {
        $this->assertEquals(25.9, Convert::toFloat(25.9));

        $this->assertEquals(25.0, Convert::toFloat(25));

        $this->assertEquals(1.0, Convert::toFloat(true));
        $this->assertEquals(0.0, Convert::toFloat(false));

        $this->assertEquals(25.0, Convert::toFloat('25'));
        $this->assertEquals(25.0, Convert::toFloat(' 25'));
        $this->assertEquals(25.0, Convert::toFloat('25 '));
        $this->assertEquals(25.0, Convert::toFloat('25.'));
        $this->assertEquals(25.5, Convert::toFloat('25.5'));
        $this->assertEquals(0.5, Convert::toFloat('.5'));

        $this->assertEquals(null, Convert::toFloat('', throwOnBlank: false));
        $this->assertEquals(null, Convert::toFloat(' ', throwOnBlank: false));
        $this->assertEquals(null, Convert::toFloat("\t", throwOnBlank: false));

        $this->assertEquals(null, Convert::toFloat(null, throwOnBlank: false));
    }

    public function test_converts_to_float_failed_with_invalid_argument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat([]);

        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat(new stdClass);

        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat('2 5');

        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat('2.5.');

        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat('.');

        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat('..');

        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat('a');

        $this->expectException(InvalidArgumentException::class);
        Convert::toFloat('25a');
    }

    public function test_a_value_converts_to_boolean_properly(): void
    {
        $this->assertFalse(Convert::toBoolean(0));
        $this->assertTrue(Convert::toBoolean(25));
        $this->assertTrue(Convert::toBoolean(-1));

        $this->assertTrue(Convert::toBoolean('true'));
        $this->assertTrue(Convert::toBoolean('TRUE'));
        $this->assertTrue(Convert::toBoolean(' true'));
        $this->assertFalse(Convert::toBoolean('false '));
        $this->assertTrue(Convert::toBoolean('1'));
        $this->assertTrue(Convert::toBoolean(' 1 '));
        $this->assertFalse(Convert::toBoolean('0'));
        $this->assertFalse(Convert::toBoolean(' 0 '));

        $this->assertTrue(Convert::toBoolean(true));
        $this->assertFalse(Convert::toBoolean(false));

        $this->assertEquals(null, Convert::toBoolean('', throwOnBlank: false));
        $this->assertEquals(null, Convert::toBoolean(' ', throwOnBlank: false));
        $this->assertEquals(null, Convert::toBoolean("\t", throwOnBlank: false));

        $this->assertEquals(null, Convert::toBoolean(null, throwOnBlank: false));
    }

    public function test_converts_to_boolean_failed_with_invalid_argument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Convert::toBoolean([]);

        $this->expectException(InvalidArgumentException::class);
        Convert::toBoolean(new stdClass);

        $this->expectException(InvalidArgumentException::class);
        Convert::toBoolean('1');

        $this->expectException(InvalidArgumentException::class);
        Convert::toBoolean('0');

        $this->expectException(InvalidArgumentException::class);
        Convert::toBoolean('yes');

        $this->expectException(InvalidArgumentException::class);
        Convert::toBoolean('no');
    }

    public function test_a_value_converts_to_string_properly(): void
    {
        $this->assertEquals('0', Convert::toString(0));
        $this->assertEquals('25', Convert::toString(25));
        $this->assertEquals('2.5', Convert::toString(2.5));

        $this->assertEquals('true', Convert::toString(true));
        $this->assertEquals('false', Convert::toString(false));

        $this->assertEquals('', Convert::toString(''));
        $this->assertEquals(' ', Convert::toString(' '));

        $this->assertEquals(null, Convert::toString(null));
    }
}
