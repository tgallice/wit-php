<?php

namespace Tgallice\Wit;

use Tgallice\Wit\HttpClient\GuzzleHttpClient;
use Tgallice\Wit\HttpClient\HttpClient;
use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Exception\BadResponseException;

class Client
{
    /**
     * API base uri
     */
    const API_BASE_URI = 'https://api.wit.ai/';

    /*
     * API Version
     */
    const DEFAULT_API_VERSION = '20160526';

    /**
     * Request default timeout
     */
    const DEFAULT_TIMEOUT = 5;

    /**
     * @var array
     */
    public static $allowedMethod = ['POST', 'GET', 'PUT', 'DELETE'];

    /**
     * @var string Wit app token
     */
    private $accessToken;

    /**
     * @var string
     */
    private $apiVersion;

    /**
     * @var HttpClient client
     */
    private $client;

    /**
     * @var ResponseInterface|null
     */
    private $lastResponse;

    public function __construct($accessToken, HttpClient $httpClient = null, $apiVersion = self::DEFAULT_API_VERSION)
    {
        $this->accessToken = $accessToken;
        $this->apiVersion = $apiVersion;
        $this->client = $httpClient ?: $this->defaultHttpClient();
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function post($uri, array $params = [])
    {
        return $this->send('POST', $uri, $params);
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function get($uri, array $params = [])
    {
        return $this->send('GET', $uri, null, $params);
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function put($uri, array $params = [])
    {
        return $this->send('PUT', $uri, $params);
    }

    /**
     * @param string $uri
     *
     * @return ResponseInterface
     */
    public function delete($uri)
    {
        return $this->send('DELETE', $uri);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param mixed $body
     * @param array $query
     * @param array $headers
     * @param array $options
     *
     * @return ResponseInterface
     */
    public function send($method, $uri, $body = null, array $query = [], array $headers = [], array $options = [])
    {
        $this->validateMethod($method);
        $headers = array_merge($this->getDefaultHeaders(), $headers);
        $this->lastResponse = $this->client->send($method, $uri, $body, $query, $headers, $options);
        $this->validateResponse($this->lastResponse);

        return $this->lastResponse;
    }

    /**
     * Get the last response from the API
     *
     * @return null|ResponseInterface
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    public function getHttpClient()
    {
        return $this->client;
    }

    /**
     * Get the defaults headers like the Authorization field
     *
     * @return array
     */
    private function getDefaultHeaders()
    {
        return [
            'Authorization' => 'Bearer '.$this->accessToken,
            // Used the accept field is needed to fix the API version and avoid BC break from the API
            'Accept' => 'application/vnd.wit.'.$this->apiVersion.'+json',
        ];
    }

    /**
     * @param ResponseInterface $response
     *
     * @throws BadResponseException
     */
    private function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== 200) {
            $message = empty($response->getReasonPhrase()) ? 'Bad response status code' : $response->getReasonPhrase();

            throw new BadResponseException($message, $response);
        }
    }

    /**
     * @return HttpClient
     */
    private function defaultHttpClient()
    {
        return new GuzzleHttpClient();
    }

    /**
     * @param $method
     *
     * @throw \InvalidArgumentException
     */
    private function validateMethod($method)
    {
        if (!in_array(strtoupper($method), self::$allowedMethod)) {
            throw new \InvalidArgumentException(sprintf('"%s" is not in the allowed methods "%s"', $method, implode(', ', self::$allowedMethod)));
        }
    }
}
