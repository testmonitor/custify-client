<?php

namespace TestMonitor\Custify\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Client;
use TestMonitor\Custify\Resources\Event;

class EventTest extends TestCase
{
    protected $token;

    protected $company;

    protected $person;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = '12345';
        $this->company = ['id' => '1', 'company_id' => 'abcde', 'name' => 'Company'];
        $this->person = ['id' => '1', 'user_id' => 'abcde', 'name' => 'Krab'];
    }

    public function tearDown(): void
    {
        Mockery::close();
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
        $response = $custify->createEvent(new Event([
            'user_id' => $this->person->user_id,
            'name' => 'Event',
        ]));

        // Then
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
        $response = $custify->createEvent(new Event([
            'company_id' => $this->person->company_id,
            'name' => 'Event',
        ]));

        // Then
    }
}
