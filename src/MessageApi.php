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
     * @param array $extraParams
     *
     * @return mixed
     */
    public function extractMeaning($text, Context $context = null, array $extraParams = [])
    {
        $query = array_merge($extraParams, [
            'q' => $text,
        ]);

        if (null !== $context) {
            $query['context'] = json_encode($context);
        }

        $response = $this->client->get('/message', $query);

        return $this->decodeResponse($response);
    }
}
