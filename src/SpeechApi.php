<?php

namespace Tgallice\Wit;

use Tgallice\Wit\Model\Context;

class SpeechApi
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
     * @param string|resource $file
     * @param array|null $context
     * @param array $queryParams
     *
     * @return array|null
     */
    public function extractMeaning($file, Context $context = null, array $queryParams = [])
    {
        if (!$file || (!is_resource($file) && !is_readable($file))) {
            throw new \InvalidArgumentException('$file argument must be a readable file path or a valid resource');
        }

        if (null !== $context) {
            $queryParams['context'] = json_encode($context);
        }

        $file = is_resource($file) ? $file : fopen($file, 'r');
        $response = $this->client->send('POST', '/speech', $file, $queryParams);

        return $this->decodeResponse($response);
    }
}
