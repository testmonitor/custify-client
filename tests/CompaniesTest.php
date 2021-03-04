<?php

namespace TestMonitor\Custify\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Client;
use TestMonitor\Custify\Resources\Company;
use TestMonitor\Custify\Exceptions\Exception;
use TestMonitor\Custify\Exceptions\NotFoundException;
use TestMonitor\Custify\Exceptions\ValidationException;
use TestMonitor\Custify\Exceptions\FailedActionException;
use TestMonitor\Custify\Exceptions\UnauthorizedException;

class CompaniesTest extends TestCase
{
    protected $token;

    protected $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = '12345';
        $this->company = ['id' => '1', 'company_id' => 'abcde', 'name' => 'Company'];
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_should_return_a_list_of_companies()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['companies' => [$this->company]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $companies = $custify->companies();

        // Then
        $this->assertIsArray($companies);
        $this->assertCount(1, $companies);
        $this->assertInstanceOf(Company::class, $companies[0]);
        $this->assertEquals($this->company['id'], $companies[0]->id);
        $this->assertIsArray($companies[0]->toArray());
    }

    /** @test */
    public function it_should_throw_an_failed_action_exception_when_client_receives_bad_request_while_getting_a_list_of_companies()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(FailedActionException::class);

        // When
        $custify->companies();
    }

    /** @test */
    public function it_should_throw_a_notfound_exception_when_client_receives_not_found_while_getting_a_list_of_companies()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(404);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(NotFoundException::class);

        // When
        $custify->companies();
    }

    /** @test */
    public function it_should_throw_a_unauthorized_exception_when_client_lacks_authorization_for_getting_a_list_of_companies()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getBody')->andReturnNull();

        $this->expectException(UnauthorizedException::class);

        // When
        $custify->companies();
    }

    /** @test */
    public function it_should_throw_a_validation_exception_when_client_provides_invalid_data_while_a_getting_list_of_companies()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['message' => 'invalid']));

        $this->expectException(ValidationException::class);

        // When
        $custify->companies();
    }

    /** @test */
    public function it_should_return_an_error_message_when_client_provides_invalid_data_while_a_getting_list_of_companies()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->andReturn(json_encode(['errors' => ['invalid']]));

        // When
        try {
            $custify->companies();
        } catch (ValidationException $exception) {

            // Then
            $this->assertIsArray($exception->errors());
            $this->assertEquals('invalid', $exception->errors()['errors'][0]);
        }
    }

    /** @test */
    public function it_should_throw_a_generic_exception_when_client_suddenly_becomes_a_teapot_while_a_getting_list_of_companies()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(418);
        $response->shouldReceive('getBody')->andReturn(json_encode(['rooibos' => 'anyone?']));

        $this->expectException(Exception::class);

        // When
        $custify->companies();
    }

    /** @test */
    public function it_should_return_a_company_when_using_an_id()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['companies' => [$this->company]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $company = $custify->company('1');

        // Then
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals($this->company['id'], $company->id);
    }

    /** @test */
    public function it_should_return_a_company_when_using_a_company_id()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['companies' => [$this->company]]));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $company = $custify->companyByCompanyId('12345');

        // Then
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals($this->company['id'], $company->id);
    }

    /** @test */
    public function it_should_not_return_a_company_when_using_a_non_existing_company_id()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(json_encode(['companies' => []]));

        $service->shouldReceive('request')->once()->andReturn($response);

        $this->expectException(NotFoundException::class);

        // When
        $custify->companyByCompanyId('12346');
    }

    /** @test */
    public function it_should_create_a_company()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(201);
        $response->shouldReceive('getBody')->andReturn(json_encode($this->company));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $company = $custify->createOrUpdateCompany(new Company([
            'user_id' => $this->company['company_id'],
            'name' => $this->company['name'],
        ]));

        // Then
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals($this->company['id'], $company->id);
    }
}
