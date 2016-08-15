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

    /**
     * @param string $sessionId
     * @param string|null $text
     * @param Context|null $context
     *
     * @return array
     */
    public function converse($sessionId, $text = null, Context $context = null)
    {
        $query = [
            'session_id' => $sessionId,
        ];

        if (!empty($text)) {
            $query['q'] = $text;
        }

        $body = null;

        // Don't send empty array
        if (null !== $context && !$context->isEmpty()) {
            $body = $context;
        }

        $response = $this->client->send('POST', '/converse', $body, $query);

        return $this->decodeResponse($response);
    }
}
