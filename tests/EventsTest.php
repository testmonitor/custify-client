<?php

namespace TestMonitor\Custify\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Client;
use TestMonitor\Custify\Resources\Event;
use TestMonitor\Custify\Resources\Person;
use TestMonitor\Custify\Resources\Company;
use TestMonitor\Custify\Resources\MetaData;

class EventsTest extends TestCase
{
    protected $token;

    protected $company;

    protected $person;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = '12345';
        $this->company = ['id' => '1', 'company_id' => 'abcde', 'name' => 'Company'];
        $this->person = ['id' => '1', 'user_id' => 'abcde', 'name' => 'Krab', 'email' => 'krusty@crab.com'];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_send_an_event_for_a_person()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode([])));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->insertEvent(new Event([
            'name' => 'Event',
            'person' => new Person($this->person),
        ]));

        // Then
        $this->assertTrue($response);
    }

    /** @test */
    public function it_should_send_an_event_for_a_company()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode([])));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->insertEvent(new Event([
            'name' => 'Event',
            'company' => new Company($this->company),
        ]));

        // Then
        $this->assertTrue($response);
    }

    /** @test */
    public function it_should_send_an_event_for_a_company_and_attach_metadata()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode([])));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->insertEvent(new Event([
            'name' => 'Event',
            'company' => new Company($this->company),
            'metadata' => new MetaData(['foo' => 'bar']),
        ]));

        // Then
        $this->assertTrue($response);
    }

    /** @test */
    public function it_should_send_an_event_for_a_person_and_attach_metadata()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode([])));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $metadata = new MetaData();
        $metadata->foo = 'bar';

        $response = $custify->insertEvent(new Event([
            'name' => 'Event',
            'company' => new Company($this->company),
            'metadata' => $metadata,
        ]));

        // Then
        $this->assertTrue($response);
        $this->assertEquals('bar', $metadata->foo);
    }
}
