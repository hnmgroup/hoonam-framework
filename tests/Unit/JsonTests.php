<?php

namespace Tests\Unit;

use Hoonam\Framework\Utilities\Json;
use PHPUnit\Framework\TestCase;

class JsonTests extends TestCase
{
    public function test_encode_properly(): void
    {
        $json = Json::encode('string');

        $this->assertEquals('"string"', $json);
    }

    public function test_encode_object_properly(): void
    {
        $json = Json::encode(['a' => 1, 'b' => 2]);

        $this->assertEquals('{"a":1,"b":2}', $json);
    }

    public function test_decode_properly(): void
    {
        $value = Json::decode('"str"');

        $this->assertEquals('str', $value);
    }

    public function test_decode_object_properly(): void
    {
        $arr = Json::decode('{"a":1,"b":2}');
        $obj = Json::decode('{"a":1,"b":2}', associative: false);

        $this->assertIsArray($arr);
        $this->assertIsObject($obj);
    }
}
