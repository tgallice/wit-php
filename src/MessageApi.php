<?php

namespace Tgallice\Wit;

use Tgallice\Wit\Model\Context;

class MessageApi
{
    use ResponseHandler;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $text
     * @param Context|null $context
     * @param array $queryParams
     *
     * @return mixed
     */
    public function extractMeaning($text, Context $context = null, array $queryParams = [])
    {
        $query = array_merge($queryParams, [
            'q' => $text,
        ]);

        if (null !== $context && !$context->isEmpty()) {
            $query['context'] = json_encode($context);
        }

        $response = $this->client->get('/message', $query);

        return $this->decodeResponse($response);
    }
}
