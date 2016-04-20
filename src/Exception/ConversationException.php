<?php

namespace Tgallice\Wit\Exception;

use Tgallice\Wit\Model\Context;

class ConversationException extends \RuntimeException
{
    /**
     * @var string|null
     */
    private $sessionId;

    /**
     * @var Context|null
     */
    private $context;

    /**
     * @var array
     */
    private $stepData;

    /**
     * @param string $message
     * @param string|null $sessionId
     * @param Context|null $context
     * @param array $stepData
     */
    public function __construct($message, $sessionId = null, Context $context = null, array $stepData = [])
    {
        parent::__construct($message);

        $this->sessionId = $sessionId;
        $this->context = $context;
        $this->stepData = $stepData;
    }

    /**
     * @return string|null
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return Context|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return array
     */
    public function getStepData()
    {
        return $this->stepData;
    }
}
