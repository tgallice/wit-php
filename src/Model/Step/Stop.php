<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Stop implements Step
{
    /**
     * @var float
     */
    private $confidence;

    /**
     * @param float $confidence
     */
    public function __construct($confidence)
    {
        $this->confidence = (float) $confidence;
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
        return Step::TYPE_STOP;
    }
}
