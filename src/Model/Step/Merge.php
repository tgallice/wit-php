<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Merge extends AbstractStep
{
    /**
     * @param array $entities
     * @param float $confidence
     */
    public function __construct(array $entities, $confidence)
    {
        parent::__construct(Step::TYPE_MERGE, $confidence, $entities);
    }
}
