<?php

namespace Tests\Unit;

use Hoonam\Framework\Utilities\URL;
use PHPUnit\Framework\TestCase;

class URLTests extends TestCase
{
    public function test_url_build_set_query_parameters_properly(): void
    {
        $this::assertEquals(
            'http://example.com?sort=%2Bname',
            URL::build('http://example.com?sort=id', ['query' => ['sort' => '+name']]),
        );
    }

    public function test_url_build_set_fragment_properly(): void
    {
        $this::assertEquals(
            'http://example.com#sort=%2Bname',
            URL::build('http://example.com#sort=id', ['fragment' => ['sort' => '+name']]),
        );

        $this::assertEquals(
            'http://example.com#sort=+name',
            URL::build('http://example.com#sort=id', ['fragment' => 'sort=+name']),
        );
    }
}
