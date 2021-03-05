<?php

namespace TestMonitor\Custify\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Client;
use TestMonitor\Custify\Exceptions\UnauthorizedException;

class ClientTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_throw_an_exception_when_there_is_no_token_provided()
    {
        // Given
        $custify = new Client('');

        $this->expectException(UnauthorizedException::class);

        // When
        $custify->people();
    }
}
