<?php

namespace Tgallice\Wit;

use Psr\Http\Message\ResponseInterface;
use Tgallice\Wit\Model\Context;

/**
 * @deprecated This class is deprecated as of 0.1 and will be removed in 1.0.
 */
class Api
{
    /**
     * @var Client client
     */
    private $client;

    /**
     * @var MessageApi
     */
    private $messageApi;

    /**
     * @var SpeechApi
     */
    private $speechApi;

    /**
     * @var ConverseApi
     */
    private $converseApi;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->messageApi = new MessageApi($client);
        $this->speechApi = new SpeechApi($client);
        $this->converseApi = new ConverseApi($client);
    }

    /**
     * @deprecated This method is deprecated as of 0.1 and will be removed in 1.0.
     *             You should use the ConverseApi::converse() instead
     *
     * @param string $sessionId
     * @param string $text
     * @param Context|null $context
     *
     * @return array|null
     */
    public function getConverseNextStep($sessionId, $text, Context $context = null)
    {
        return $this->converseApi->converse($sessionId, $text, $context);
    }

    /**
     * @deprecated This method is deprecated as of 0.1 and will be removed in 1.0.
     *             You should use the MessageApi::extractMeaning() instead
     *
     * @param string $text
     * @param Context|null $context
     * @param array $extraParams
     *
     * @return array|null
     */
    public function getIntentByText($text, Context $context = null, array $extraParams = [])
    {
        return $this->messageApi->extractMeaning($text, $context, $extraParams);
    }

    /**
     * @deprecated This method is deprecated as of 0.1 and will be removed in 1.0.
     *             You should use the SpeechApi::extractMeaning() instead
     *
     * @param string|resource $file
     * @param array|null $context
     * @param array $queryParams
     *
     * @return array|null
     */
    public function getIntentBySpeech($file, Context $context = null, array $queryParams = [])
    {
        return $this->speechApi->extractMeaning($file, $context, $queryParams);
    }

    /**
     * @deprecated This method is deprecated as of 0.1 and will be removed in 1.0.
     *
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
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @deprecated This method is deprecated as of 0.1 and will be removed in 1.0.
     *             You should use the ResponseHandler::decodeResponse() instead
     *
     * @param ResponseInterface $response
     *
     * @return array|null
     */
    protected function decodeResponse(ResponseInterface $response)
    {
        return json_decode((string) $response->getBody(), true);
    }
}
