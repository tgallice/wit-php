<?php

namespace Tgallice\Wit\HttpClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Tgallice\Wit\Client;

class GuzzleHttpClient implements HttpClient
{
    /**
     * @var ClientInterface
     */
    private $guzzleClient;

    /**
     * @param ClientInterface|null $guzzleClient
     */
    public function __construct(ClientInterface $guzzleClient = null)
    {
        $this->guzzleClient = $guzzleClient ?: new GuzzleClient([
            'base_uri' => Client::API_BASE_URI,
            'timeout' => Client::DEFAULT_TIMEOUT,
            'connect_timeout' => Client::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function send($method, $uri, $body = null, array $query = [], array $headers = [], array $options = [])
    {
        $options = array_merge($options, [
            RequestOptions::QUERY => $query,
            RequestOptions::HEADERS => $headers,
        ]);

        if (!empty($body) && (is_array($body) || $body instanceof \JsonSerializable)) {
            $options[RequestOptions::JSON] = $body;
        } else {
            $options[RequestOptions::BODY] = $body;
        }

        return $this->guzzleClient->request($method, $uri, $options);
    }
}
