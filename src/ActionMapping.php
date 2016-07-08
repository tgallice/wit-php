<?php

namespace Tgallice\Wit;

use Tgallice\Wit\Model\Context;
use Tgallice\Wit\Model\Step;

abstract class ActionMapping
{
    /**
     * @param string $sessionId
     * @param string $actionName
     * @param Context $context
     * @param array $entities
     * 
     * @return Context
     */
    abstract public function action($sessionId, $actionName, Context $context, array $entities = []);

    /**
     * @param string $sessionId
     * @param string $message
     * @param Context $context
     * @param array $entities
     */
    abstract public function say($sessionId, $message, Context $context, array $entities = []);

    /**
     * @param string $sessionId
     * @param Context $context
     * @param \Exception|string $error
     * @param array $stepData
     */
    abstract public function error($sessionId, Context $context, $error = 'Unknown Error', array $stepData = []);

    /**
     * @param string $sessionId
     * @param Context $context
     * @param array $entities
     *
     * @return Context
     */
    abstract public function merge($sessionId, Context $context, array $entities = []);

    /**
     * @param string $sessionId
     * @param Context $context
     */
    abstract public function stop($sessionId, Context $context);
}
