<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Action implements Step
{
    /**
     * @var string
     */
    private $action;

    /**
     * @var float
     */
    private $confidence;

    /**
     * @param string $action
     * @param float $confidence
     */
    public function __construct($action, $confidence)
    {
        $this->action = $action;
        $this->confidence = (float) $confidence;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
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
        return Step::TYPE_ACTION;
    }
}
