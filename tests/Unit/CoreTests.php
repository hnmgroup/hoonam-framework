<?php

namespace Tests\Unit;

use stdClass;
use Stringable;
use Hoonam\Framework\Utilities\Core;
use PHPUnit\Framework\TestCase;

class CoreTests extends TestCase
{
    public function test_transform_works_properly_with_primitive_values(): void
    {
        $intValue = 1;

        Core::manipulate($intValue, fn ($value) => $value + 1);

        $this->assertEquals(2, $intValue);
    }

    public function test_transform_works_properly_with_arrays(): void
    {
        $arr = ['abc', 10, true];

        Core::manipulate(
            $arr,
            fn ($value) => is_string($value) ? strtoupper($value) : (is_integer($value) ? $value + 1 : $value));

        $this->assertEquals(['ABC', 11, true], $arr);
    }

    public function test_transform_works_properly_with_objects(): void
    {
        $obj = (object)[
            'name' => 'abc',
            'id' => 10,
            'isBit' => true
        ];

        Core::manipulate(
            $obj,
            fn ($value) => is_string($value) ? strtoupper($value) : (is_integer($value) ? $value + 1 : $value));

        $this->assertEquals((object)[
            'name' => 'ABC',
            'id' => 11,
            'isBit' => true
        ], $obj);
    }

    public function test_isEnum_works_properly(): void
    {
        $this->assertTrue(Core::isEnum(FakeEnum::B));
        $this->assertTrue(Core::isEnum(FakeIntBackedEnum::B));
        $this->assertTrue(Core::isEnum(FakeStrBackedEnum::B));
        $this->assertFalse(Core::isEnum(1));
        $this->assertFalse(Core::isEnum('a'));
        $this->assertFalse(Core::isEnum(new stdClass));
        $this->assertFalse(Core::isEnum([]));
    }

    public function test_transformValue_works_properly(): void
    {
        $this->assertEquals(1, Core::transformValue(0, fn ($i) => $i + 1));
        $this->assertEquals(2, Core::transformValue(1, fn ($i) => $i + 1));
        $this->assertEquals(null, Core::transformValue('', fn () => 'x'));
        $this->assertEquals('A', Core::transformValue('a', fn ($v) => strtoupper($v)));
        $this->assertEquals(null, Core::transformValue(null, fn () => 1));
    }

    public function test_merge_arrays_works_properly(): void
    {
        $this->assertEquals(
            [1 => 10, 2 => 20],
            Core::mergeArraysByKey([1 => 10], [2 => 20]));

        $this->assertEquals(
            [1 => 100],
            Core::mergeArraysByKey([1 => 10], [1 => 100]));

        $this->assertEquals(
            [1 => 1000],
            Core::mergeArraysByKey([1 => 10], [1 => 100], [1 => 1000]));
    }

    public function test_getValue_works_properly(): void
    {
        $this->assertEquals(-1, Core::getValue(null, 'key', -1));
        $this->assertEquals(115, Core::getValue(['arrayKey' => 115], 'arrayKey'));
        $this->assertEquals(115, Core::getValue((object)['objectKey' => 115], 'objectKey'));
        $this->assertEquals(120, Core::getValue([5 => 120], 5));
        $this->assertEquals(150, Core::getValue(['func' => fn () => 150], 'func()'));
        $this->assertEquals(2, Core::getValue(['root' => ['level2' => [1,2,3]]], 'root.level2.1'));
        $this->assertEquals('fake', Core::getValue('fake', key: ''));
        $this->assertEquals('100', Core::getValue(new FakeClass, 'getInt'));
    }

    public function test_omitNulls_works_properly(): void
    {
        $this->assertEquals([1,2], Core::omitNulls([1, null, 2]));
    }

    public function test_arrayDiffByKey_works_properly(): void
    {
        $this->assertEquals([1,2], Core::arrayDiffByKey([1, 2, 3], [3, 4], key: ''));

        $array = [
            ['id' => 1, 'name' => 'a'],
            ['id' => 2, 'name' => 'b'],
            ['id' => 3, 'name' => 'c'],
        ];
        $this->assertEquals([0 => $array[0], 2 => $array[2]], Core::arrayDiffByKey($array, [
            ['id' => 2, 'name' => 'cc'],
            ['id' => 4, 'name' => 'e'],
        ], key: 'id'));
    }

    public function test_arrayIntersectByKey_works_properly(): void
    {
        $this->assertEquals([2 => 3], Core::arrayIntersectByKey([1, 2, 3], [3, 4], key: ''));

        $array = [
            ['id' => 1, 'name' => 'a'],
            ['id' => 2, 'name' => 'b'],
            ['id' => 3, 'name' => 'c'],
        ];
        $this->assertEquals([1 => $array[1]], Core::arrayIntersectByKey($array, [
            ['id' => 2, 'name' => 'bb'],
            ['id' => 4, 'name' => 'e'],
        ], key: 'id'));
    }

    public function test_toString(): void
    {
        $this->assertEquals('22:10:20.333456', toString(parseTime('22:10:20.333456')));
        $this->assertEquals('C', toString(FakeEnum::C));
        $this->assertEquals('B', toString(FakeIntBackedEnum::B));
        $this->assertEquals('A', toString(FakeStrBackedEnum::A));
        $this->assertEquals('FAKE_CLASS', toString(new FakeClass()));
    }
}

enum FakeEnum { case A; case B; case C; }
enum FakeIntBackedEnum: int { case A = 1; case B = 2; case C = 3; }
enum FakeStrBackedEnum: string { case A = 'a'; case B = 'b'; case C = 'c'; }
class FakeClass implements Stringable
{
    public function getInt(): int { return 100; }

    public function __toString()
    {
        return 'FAKE_CLASS';
    }
}
