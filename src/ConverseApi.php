<?php

namespace Tgallice\Wit;

use Tgallice\Wit\Model\Context;

class ConverseApi
{
    use ResponseHandler;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function converse($sessionId, $text, Context $context = null)
    {
        $query = [
            'session_id' => $sessionId,
            'q' => $text,
        ];

        $response = $this->client->send('POST', '/converse', $context, $query);

        return $this->decodeResponse($response);
    }
}
