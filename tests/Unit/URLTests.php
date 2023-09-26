<?php

namespace Tests\Unit;

use Hoonam\Framework\Utilities\URL;
use PHPUnit\Framework\TestCase;

class URLTests extends TestCase
{
    public function test_url_query_parameter_add_properly(): void
    {
        $this::assertEquals('http://a.com?a=1', URL::appendParameter('http://a.com', 'a', '1'));
        $this::assertEquals('http://a.com?a=1', URL::appendParameter('http://a.com?', 'a', '1'));
        $this::assertEquals('http://a.com?b=&a=1', URL::appendParameter('http://a.com?b=', 'a', '1'));
        $this::assertEquals('http://a.com?b=5&d=3&a=1', URL::appendParameter('http://a.com?b=5&d=3', 'a', '1'));
    }
}
