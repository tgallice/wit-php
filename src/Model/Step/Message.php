<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Message extends AbstractStep
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $quickreplies;

    /**
     * @param string $message
     * @param float $confidence
     * @param array $entities
     * @param array $quickreplies
     */
    public function __construct($message, $confidence, array $entities = [], array $quickreplies = [])
    {
        parent::__construct(Step::TYPE_MESSAGE, $confidence, $entities, $quickreplies);
        $this->message = $message;
        $this->quickreplies = $quickreplies;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getQuickReplies()
    {
        return $this->quickreplies;
    }
}