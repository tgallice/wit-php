<?php

namespace Tgallice\Wit;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
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
    const API_VERSION = '20160330';

    /**
     * Request default timeout
     */
    const DEFAULT_TIMEOUT = 5;

    /**
     * @var ClientInterface client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ResponseInterface|null
     */
    private $lastResponse;

    /**
     * @var string Wit app token
     */
    private $accessToken;

    public function __construct($accessToken, ClientInterface $httpClient = null, LoggerInterface $logger = null)
    {
        $this->accessToken = $accessToken;
        $this->client = $httpClient ?: new HttpClient([
            'base_uri' => self::API_BASE_URI,
            'timeout' => self::DEFAULT_TIMEOUT,
            'connect_timeout' => self::DEFAULT_TIMEOUT,
        ]);
        $this->logger = $logger;
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function post($uri, array $params = [])
    {
        return $this->send('POST', $uri, [], $params);
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function get($uri, array $params = [])
    {
        return $this->send('GET', $uri, $params);
    }

    /**
     * @param string $uri
     * @param array $params
     *
     * @return ResponseInterface
     */
    public function put($uri, array $params = [])
    {
        return $this->send('PUT', $uri, [], $params);
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
     * @param array $queryParams
     * @param mixed $postParams
     *
     * @return ResponseInterface
     */
    public function send($method, $uri, array $queryParams = [], $postParams = null)
    {
        $options = [
            RequestOptions::QUERY => $queryParams,
            RequestOptions::HEADERS => $this->getDefaultHeaders()
        ];

        if (!empty($postParams)) {
            $type = is_array($postParams) || $postParams instanceof \JsonSerializable ? RequestOptions::JSON : RequestOptions::BODY;
            $options[$type] = $postParams;
        }

        $this->lastResponse = $this->client->request($method, $uri, $options);

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
            'Accept' => 'application/vnd.wit.'.self::API_VERSION.'+json',
        ];
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     *
     * @throws BadResponseException
     */
    private function validateResponse(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== 200) {
            $message = empty($response->getReasonPhrase()) ? 'Bad response status code' : $response->getReasonPhrase();

            throw new BadResponseException($message, $response);
        }

        return true;
    }
}
