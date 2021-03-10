<?php

namespace TestMonitor\Custify;

use Psr\Http\Message\ResponseInterface;
use TestMonitor\Custify\Exceptions\Exception;
use TestMonitor\Custify\Exceptions\NotFoundException;
use TestMonitor\Custify\Exceptions\ValidationException;
use TestMonitor\Custify\Exceptions\FailedActionException;
use TestMonitor\Custify\Exceptions\UnauthorizedException;

class Client
{
    use Actions\ManagesPeople,
        Actions\ManagesCompanies,
        Actions\ManagesEvents;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $baseUrl = 'https://api.custify.com';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Create a new client instance.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Returns an Guzzle client instance.
     *
     * @throws \TestMonitor\Custify\Exceptions\UnauthorizedException
     * @return \GuzzleHttp\Client
     */
    protected function client()
    {
        if (empty($this->token)) {
            throw new UnauthorizedException();
        }

        return $this->client ?? new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl . '/',
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function setClient(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Make a GET request to Custify servers and return the response.
     *
     * @param string $uri
     *
     * @param array $payload
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Custify\Exceptions\FailedActionException
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\UnauthorizedException
     * @throws \TestMonitor\Custify\Exceptions\ValidationException
     * @return mixed
     */
    protected function get($uri, array $payload = [])
    {
        return $this->request('GET', $uri, $payload);
    }

    /**
     * Make a POST request to Custify servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Custify\Exceptions\FailedActionException
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\UnauthorizedException
     * @throws \TestMonitor\Custify\Exceptions\ValidationException
     * @return mixed
     */
    protected function post($uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make a DELETE request to Custify servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Custify\Exceptions\FailedActionException
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\UnauthorizedException
     * @throws \TestMonitor\Custify\Exceptions\ValidationException
     * @return mixed
     */
    protected function delete($uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make request to Custify servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \TestMonitor\Custify\Exceptions\FailedActionException
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\UnauthorizedException
     * @throws \TestMonitor\Custify\Exceptions\ValidationException
     * @return mixed
     */
    protected function request($verb, $uri, array $payload = [])
    {
        $response = $this->client()->request(
            $verb,
            $uri,
            $payload
        );

        var_dump($response->getBody()->getContents());
        var_dump($response->getStatusCode());

        if (! in_array($response->getStatusCode(), [200, 201, 202, 203, 204, 206])) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * @param  \Psr\Http\Message\ResponseInterface $response
     *
     * @throws \TestMonitor\Custify\Exceptions\ValidationException
     * @throws \TestMonitor\Custify\Exceptions\NotFoundException
     * @throws \TestMonitor\Custify\Exceptions\FailedActionException
     * @throws \Exception
     * @return void
     */
    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 401 || $response->getStatusCode() == 403) {
            throw new UnauthorizedException();
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string) $response->getBody());
        }

        throw new Exception((string) $response->getStatusCode());
    }
}
