<?php

namespace TestMonitor\Custify\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Client;
use TestMonitor\Custify\Resources\Event;
use TestMonitor\Custify\Resources\Person;
use TestMonitor\Custify\Resources\Company;

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
        $response->shouldReceive('getBody')->andReturn(json_encode([]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->createEvent(new Event([
            'user_id' => $this->person['user_id'],
            'name' => 'Event',
        ]));

        // Then
        $this->assertInstanceOf(Event::class, $response);
    }

    /** @test */
    public function it_should_send_an_event_for_a_company()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(json_encode([]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->createEvent(new Event([
            'company_id' => $this->company['company_id'],
            'name' => 'Event',
        ]));

        // Then
        $this->assertInstanceOf(Event::class, $response);
    }


    /** @test */
    public function it_should_trigger_an_event_for_a_person()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(json_encode([]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->event("Person created", new Person($this->person));

        // Then
        $this->assertInstanceOf(Event::class, $response);
        $this->assertEquals("Person created", $response->name);
    }

    /** @test */
    public function it_should_trigger_an_event_for_a_company()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(json_encode([]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->event("Company created", new Company($this->company));

        // Then
        $this->assertInstanceOf(Event::class, $response);
        $this->assertEquals("Company created", $response->name);
    }

    /** @test */
    public function it_should_trigger_an_event_and_include_metadata()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(202);
        $response->shouldReceive('getBody')->andReturn(json_encode([]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->event(
            "Company created",
            new Company($this->company),
            ['hello' => 'World']
        );

        // Then
        $this->assertInstanceOf(Event::class, $response);
        $this->assertEquals("Company created", $response->name);
        $this->assertObjectHasAttribute("hello", $response->metadata);
        $this->assertEquals("World", $response->metadata->hello);
    }
}
