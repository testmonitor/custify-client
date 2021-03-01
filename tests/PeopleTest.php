<?php

namespace TestMonitor\Custify\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Client;
use TestMonitor\Custify\Resources\Person;
use TestMonitor\Custify\Exceptions\Exception;
use TestMonitor\Custify\Exceptions\NotFoundException;
use TestMonitor\Custify\Exceptions\ValidationException;
use TestMonitor\Custify\Exceptions\FailedActionException;
use TestMonitor\Custify\Exceptions\UnauthorizedException;

class PeopleTest extends TestCase
{
    protected $token;

    protected $person;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = '12345';
        $this->person = ['id' => '1', 'user_id' => 'abcde', 'email' => 'email@server.com'];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_people()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode([['people' => [$this->person]]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $people = $custify->people();

        // Then
        $this->assertIsArray($people);
        $this->assertCount(1, $people);
        $this->assertInstanceOf(Person::class, $people[0]);
        $this->assertEquals($this->person['id'], $people[0]->id);
        $this->assertIsArray($people[0]->toArray());
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_people()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(FailedActionException::class);

        // When
        $custify->people();
    }

    /** @test */
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_people()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(NotFoundException::class);

        // When
        $custify->people();
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_people()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(UnauthorizedException::class);

        // When
        $custify->people();
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_people()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['message' => 'invalid']));

        $this->expectException(ValidationException::class);

        // When
        $custify->people();
    }

    /** @test */
    public function it_should_return_an_error_message_when_client_provides_invalid_data_while_a_getting_list_of_people()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['errors' => ['invalid']]));

        // When
        try {
            $custify->people();
        } catch (ValidationException $exception) {

            // Then
            $this->assertIsArray($exception->errors());
            $this->assertEquals('invalid', $exception->errors()['errors'][0]);
        }
    }

    /** @test */
    public function it_should_throw_a_generic_exception_when_client_suddenly_becomes_a_teapot_while_a_getting_list_of_people()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(418);
        $response->shouldReceive('getBody')->andReturn(json_encode(['rooibos' => 'anyone?']));

        $this->expectException(Exception::class);

        // When
        $custify->people();
    }

    /** @test */
    public function it_should_return_a_person()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode($this->person));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $person = $custify->person('12345');

        // Then
        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($this->person['id'], $person->id);
    }

    /** @test */
    public function it_should_not_return_a_person_when_the_id_doesnt_exists()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(404);

        $service->shouldReceive('request')->once()->andReturn($response);

        $this->expectException(NotFoundException::class);

        // When
        $custify->person('12346');
    }

    /** @test */
    public function it_should_return_a_person_when_using_a_user_id()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode([['people' => [$this->person]]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $person = $custify->personByUserId('12345');

        // Then
        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($this->person['id'], $person->id);
    }

    /** @test */
    public function it_should_not_return_a_person_when_using_a_non_existing_user_id()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode([['people' => []]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        $this->expectException(NotFoundException::class);

        // When
        $custify->personByUserId('12346');
    }

    /** @test */
    public function it_should_create_a_person()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(201);
        $response->shouldReceive('getBody')->andReturn(json_encode($this->person));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $person = $custify->createOrUpdatePerson(new Person([
            'user_id' => $this->person['user_id'],
            'email' => $this->person['email'],
        ]));

        // Then
        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals($this->person['id'], $person->id);
    }
}
