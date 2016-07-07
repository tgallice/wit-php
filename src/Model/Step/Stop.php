<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Stop extends AbstractStep
{
    /**
     * @param float $confidence
     */
    public function __construct($confidence)
    {
        parent::__construct(Step::TYPE_STOP, $confidence);
    }
}
