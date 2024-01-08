<?php

namespace TestMonitor\Custify\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use TestMonitor\Custify\Client;
use TestMonitor\Custify\Resources\Company;
use TestMonitor\Custify\Exceptions\Exception;
use TestMonitor\Custify\Resources\CustomAttributes;
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
        $this->company = [
            'id' => '1',
            'company_id' => 'abcde',
            'name' => 'Company',
            'website' => null,
            'size' => null,
            'signed_up_at' => null,
            'industry' => null,
            'plan' => null,
            'churned' => null,
            'owners_account' => null,
            'owners_csm' => null,
        ];
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
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['companies' => [$this->company]])));

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
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(null));

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
    public function it_should_throw_a_notfound_exception_when_getting_a_company_that_doesnt_exists()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $service->shouldReceive('request')->once()->andReturn($response = Mockery::mock('Psr\Http\Message\ResponseInterface'));
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['companies' => []])));

        $this->expectException(NotFoundException::class);

        // When
        $custify->company('1');
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
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['message' => 'invalid'])));

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
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['errors' => ['invalid']])));

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
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['companies' => [$this->company]])));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $company = $custify->company('1');

        // Then
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals($this->company['id'], $company->id);
    }

    /** @test */
    public function it_should_not_return_a_company_when_the_id_doesnt_exists()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(404);

        $service->shouldReceive('request')->once()->andReturn($response);

        $this->expectException(NotFoundException::class);

        // When
        $custify->company('12346');
    }

    /** @test */
    public function it_should_return_a_company_when_using_a_company_id()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['companies' => [$this->company]])));

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
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(['companies' => []])));

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
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode($this->company)));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $company = $custify->createOrUpdateCompany(new Company([
            'company_id' => $this->company['company_id'],
            'name' => $this->company['name'],
        ]));

        // Then
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals($this->company['id'], $company->id);
    }

    /** @test */
    public function it_should_update_custom_atributes_for_a_company()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(201);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(array_merge(
            $this->company,
            ['custom_attributes' => ['krusty' => 'krab']]
        ))));

        $service->shouldReceive('request')->once()->andReturn($response);

        $company = new Company([
            'company_id' => $this->company['company_id'],
            'name' => $this->company['name'],
        ]);

        // When
        $company->customAttributes = new CustomAttributes(['krusty' => 'krab']);

        $response = $custify->createOrUpdateCompany($company);

        // Then
        $this->assertInstanceOf(Company::class, $response);
        $this->assertEquals('krab', $response->customAttributes->krusty);
    }

    /** @test */
    public function it_should_alter_the_custom_attributes_of_a_company()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(201);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(array_merge(
            $this->company,
            ['custom_attributes' => ['krusty' => 'krab']]
        ))));

        $service->shouldReceive('request')->once()->andReturn($response);

        $company = new Company([
            'company_id' => $this->company['company_id'],
            'name' => $this->company['name'],
        ]);

        // When
        $customAttributes = new CustomAttributes();
        $customAttributes->krusty = 'krab';

        $company->customAttributes = $customAttributes;

        $response = $custify->createOrUpdateCompany($company);

        // Then
        $this->assertInstanceOf(Company::class, $response);
        $this->assertEquals('krab', $response->customAttributes->krusty);
    }

    /** @test */
    public function it_should_delete_a_company()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(201);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(
            ['deleted' => 1]
        )));

        $service->shouldReceive('request')->once()->andReturn($response);

        $company = new Company([
            'id' => $this->company['id'],
            'name' => $this->company['name'],
        ]);

        // When
        $response = $custify->deleteCompany($company);

        // Then
        $this->assertIsBool($response);
        $this->assertTrue($response);
    }

    /** @test */
    public function it_should_delete_a_company_using_its_company_id()
    {
        // Given
        $custify = new Client($this->token);

        $custify->setClient($service = Mockery::mock('\GuzzleHttp\Client'));

        $response = Mockery::mock('Psr\Http\Message\ResponseInterface');
        $response->shouldReceive('getStatusCode')->andReturn(201);
        $response->shouldReceive('getBody')->andReturn(\GuzzleHttp\Psr7\Utils::streamFor(json_encode(
            ['deleted' => 1]
        )));

        $service->shouldReceive('request')->once()->andReturn($response);

        // When
        $response = $custify->deleteCompanyByCompanyId($this->company['company_id']);

        // Then
        $this->assertIsBool($response);
        $this->assertTrue($response);
    }
}
