<?php

namespace TestMonitor\Custify\Tests;

use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Resources\CustomAttributes;

class CustomAttributesTest extends TestCase
{
    /** @test */
    public function it_should_accept_nummeric_values_when_sending_custom_attributes()
    {
        // When
        $result = new CustomAttributes([
            'positive' => 122,
            'negative' => -122,
            'zero' => 0,
        ]);

        // Then
        $this->assertEquals(
            [
                'positive' => 122,
                'negative' => -122,
                'zero' => 0,
            ],
            $result->attributes
        );
    }

    /** @test */
    public function it_should_accept_string_values_when_sending_custom_attributes()
    {
        // When
        $result = new CustomAttributes([
            'string' => 'hello',
            'nummeric_string' => '200',
            'zero_string' => '0',
            'empty_string' => '',
        ]);

        // Then
        $this->assertEquals(
            [
                'string' => 'hello',
                'nummeric_string' => '200',
                'zero_string' => '0',
                'empty_string' => '',
            ],
            $result->attributes
        );
    }

    /** @test */
    public function it_should_accept_boolean_values_when_sending_custom_attributes()
    {
        // When
        $result = new CustomAttributes([
            'true' => true,
            'false' => false,
            '1' => 1,
            '0' => 0,
        ]);

        // Then
        $this->assertEquals(
            [
                'true' => true,
                'false' => false,
                '1' => 1,
                '0' => 0,
            ],
            $result->attributes
        );
    }

    /** @test */
    public function it_should_not_accept_null_values_when_sending_custom_attributes()
    {
        // When
        $result = new CustomAttributes([
            'null' => null,
        ]);

        // Then
        $this->assertEquals([], $result->attributes);
    }
}
