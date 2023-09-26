<?php

namespace Tests\Unit;

use Hoonam\Framework\Utilities\Str;
use PHPUnit\Framework\TestCase;

class StrTests extends TestCase
{
    public function test_split_works_properly(): void
    {
        $this::assertEquals([], Str::splitNonEmpty(null, ','));
        $this::assertEquals([], Str::splitNonEmpty('', ','));
        $this::assertEquals([' '], Str::splitNonEmpty(' ', ','));
        $this::assertEquals([], Str::splitNonEmpty(',,', ','));
        $this::assertEquals(['1'], Str::splitNonEmpty(',1', ','));
        $this::assertEquals(['1', '2'], Str::splitNonEmpty('1,2,', ','));
    }

    public function test_valueOf_works_properly(): void
    {
        $this::assertEquals(null, Str::valueOf(null));
        $this::assertEquals(null, Str::valueOf(''));
        $this::assertEquals(null, Str::valueOf(' '));
        $this::assertEquals('a', Str::valueOf(' a '));
        $this::assertEquals('a', Str::valueOf('a'));
    }

    public function test_isBlank_works_properly(): void
    {
        $this::assertTrue(Str::isBlank(null));
        $this::assertTrue(Str::isBlank(''));
        $this::assertTrue(Str::isBlank(' '));
        $this::assertFalse(Str::isBlank(' a '));
        $this::assertFalse(Str::isBlank('a'));
    }

    public function test_nonBlank_works_properly(): void
    {
        $this::assertFalse(Str::nonBlank(null));
        $this::assertFalse(Str::nonBlank(''));
        $this::assertFalse(Str::nonBlank(' '));
        $this::assertTrue(Str::nonBlank(' a '));
        $this::assertTrue(Str::nonBlank('a'));
    }

    public function test_isEmpty_works_properly(): void
    {
        $this::assertTrue(Str::isEmpty(null));
        $this::assertTrue(Str::isEmpty(''));
        $this::assertFalse(Str::isEmpty(' '));
        $this::assertFalse(Str::isEmpty(' a '));
        $this::assertFalse(Str::isEmpty('a'));
    }

    public function test_nonEmpty_works_properly(): void
    {
        $this::assertFalse(Str::nonEmpty(null));
        $this::assertFalse(Str::nonEmpty(''));
        $this::assertTrue(Str::nonEmpty(' '));
        $this::assertTrue(Str::nonEmpty(' a '));
        $this::assertTrue(Str::nonEmpty('a'));
    }

    public function test_sanitizeText_convert_persian_numbers_properly(): void
    {
        $this::assertEquals('abc', Str::sanitizeText('abc'));
        $this::assertEquals('0123456789', Str::sanitizeText('0123456789'));
        $this::assertEquals('0123456789', Str::sanitizeText('٠١٢٣٤٥٦٧٨٩'));
        $this::assertEquals('0123456789', Str::sanitizeText('۰۱۲۳۴۵۶۷۸۹'));
        $this::assertEquals('ک', Str::sanitizeText('ك', arabicKafYa: true));
        $this::assertEquals('ی', Str::sanitizeText('ي', arabicKafYa: true));
    }
}
