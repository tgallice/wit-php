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
     * @param string $message
     * @param float $confidence
     * @param array $entities
     */
    public function __construct($message, $confidence, array $entities = [])
    {
        parent::__construct(Step::TYPE_MESSAGE, $confidence, $entities);
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
