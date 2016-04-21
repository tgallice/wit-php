<?php

namespace Tgallice\Wit;

use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Model\Context;

class Api
{
    /**
     * @var Client client
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
     * @param string $text
     * @param Context|null $context
     *
     * @return array|null
     */
    public function getConverseNextStep($sessionId, $text, Context $context = null)
    {
        $query = [
            'session_id' => $sessionId,
            'q' => $text,
        ];

        $response = $this->client->send('POST', '/converse', $context, $query);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $text
     * @param Context|null $context
     * @param array $extraParams
     *
     * @return array|null
     */
    public function getIntentByText($text, Context $context = null, array $extraParams = [])
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

    /**
     * @param string|resource $file
     * @param array|null $context
     * @param array $queryParams
     *
     * @return array|null
     */
    public function getIntentBySpeech($file, Context $context = null, array $queryParams = [])
    {
        if (!$file || (!is_resource($file) && !is_readable($file))) {
            throw new \InvalidArgumentException('$file argument must be a readable file path or a valid resource');
        }

        if (null !== $context) {
            $queryParams['context'] = json_encode($context);
        }

        $file = is_resource($file) ? $file : fopen($file, 'r+');
        $response = $this->client->send('POST', '/speech', $file, $queryParams);

        return $this->decodeResponse($response);
    }

    /**
     * @param string $messageId
     *
     * @return array|null
     */
    public function getMessage($messageId)
    {
        $response = $this->client->get(sprintf('/messages/%s', $messageId));

        return $this->decodeResponse($response);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array|null
     */
    protected function decodeResponse(ResponseInterface $response)
    {
        return json_decode((string) $response->getBody(), true);
    }
}
