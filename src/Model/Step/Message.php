<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Message implements Step
{
    /**
     * @var float
     */
    private $confidence;

    /**
     * @var string
     */
    private $message;

    /**
     * @param string $message
     * @param float $confidence
     */
    public function __construct($message, $confidence)
    {
        $this->confidence = (float) $confidence;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return float
     */
    public function getConfidence()
    {
        return $this->confidence;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Step::TYPE_MESSAGE;
    }
}
