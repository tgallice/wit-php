<?php

namespace Tgallice\Wit\Model\Step;

use Tgallice\Wit\Model\Step;

class Merge implements Step
{
    /**
     * @var float
     */
    private $confidence;

    /**
     * @var array
     */
    private $entities;

    /**
     * @param array $entities
     * @param float $confidence
     */
    public function __construct(array $entities, $confidence)
    {
        $this->confidence = (float) $confidence;
        $this->entities = $entities;
    }

    /**
     * @return array
     */
    public function getEntities()
    {
        return $this->entities;
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
        return Step::TYPE_MERGE;
    }
}
